<?php

/**
 * Sets locale information.
 */
setlocale(LC_ALL, 'de_DE.UTF-8');


add_action( 'init', 'und_rewrite_init' );
function und_rewrite_init() {
    $GLOBALS['wp_rewrite']->use_verbose_page_rules = true;
}

add_filter( 'page_rewrite_rules', 'und_collect_page_rewrite_rules' );
function und_collect_page_rewrite_rules( $page_rewrite_rules ) {
    $GLOBALS['und_page_rewrite_rules'] = $page_rewrite_rules;

    return array();
}

add_filter( 'rewrite_rules_array', 'und_prepend_page_rewrite_rules' );
function und_prepend_page_rewrite_rules( $rewrite_rules ) {
    return $GLOBALS['und_page_rewrite_rules'] + $rewrite_rules;
}

add_action( 'pre_get_posts', 'und_query_vars' );
function und_query_vars( $query ) {
    if ( $query->is_main_query() && $query->is_single() && $query->is_category( 'print' ) ) {
        $query->set( 'type', 'yearly' );
    }
}

function get_category_field( $field, $category = null ) {
    if ( ! $category ) {
        global $wp_query;
        $category = $wp_query->get_queried_object_id();
    }
    if ( is_category() ) {
        return get_field( $field, 'category_' . $category );
    } else {
        return null;
    }
}

function get_taxonomy_field( $field, $taxonomy = null ) {
    if ( ! $taxonomy ) {
        if ( is_archive() ) {
            global $wp_query;
            $taxonomy = $wp_query->get_queried_object_id();
        } else {
            return false;
        }
    }
    if ( is_tax() ) {
        return get_field( $field, $wp_query->get_queried_object() );
    } else {
        return false;
    }
}

function get_taxonomy_color( $id ) {
    $color = get_field( 'farbe', 'term_' . $id );
    if ( $color == "" && $id != 0 ) {
        return get_taxonomy_color( get_term( $id )->parent );
    } else {
        return $color;
    }
}

function get_longest_word( $string ) {
    $longest = 0;
    $words   = explode( ' ', $string );
    foreach ( $words as $word ) {
        $lenght = strlen( $word );
        if ( $lenght > $longest ) {
            $longest = $lenght;
        }
    }

    return $longest;
}

function is_active( $item, $primary_category = false ) {
    if ( $item->current ) {
        return true;
    } else {
        if ( $item->current_item_ancestor ) {
            if ( $primary_category ) {
                return $primary_category == $item->object_id || $item->object == 'category' && is_subcategory( get_category( $item->object_id ), $primary_category );
            } else if ( in_array( 'current-menu-ancestor', $item->classes ) ) {
                return true;
            } else if ( $item->type == 'taxonomy' && count( get_the_category() ) > 0 && $item->object_id == get_the_category()[0]->cat_ID ) {
                return true;
            } else {
                if ( is_single() ) {
                    return in_array( 'current-menu-parent', $item->classes ) || in_array( 'current-page-ancestor', $item->classes ) || in_array( 'current-post-ancestor', $item->classes );
                } else {
                    return in_array( 'current-menu-parent', $item->classes );
                }
            }
        } else {
            if ( in_array( 'current-menu-ancestor', $item->classes ) ) {
                return true;
            } else {
                if ( ! in_array( 'current-menu-item', $item->classes ) && $item->is_doubled_helper ) {
                    return false;
                }
                if ( $item->object == 'page' && is_single() && ! is_attachment() ) {
                    if ( is_subcategory( get_category_by_slug( basename( $item->url ) ), get_the_category()[0] ) ) {
                        return true;
                    } else {
                        return is_active_helper( $item );
                    }
                } else {
                    return is_active_helper( $item );
                }

            }
        }
    }

    return false;
}

function is_active_helper( $item ): bool {
    if ( is_category() && ! is_category( 'online' ) && $item->type == 'taxonomy' && count( get_the_category() ) > 0 && $item->object_id == get_the_category()[0]->cat_ID ) {
        return true;
    } else {
        return in_array( 'current-category-ancestor', $item->classes ) || in_array( 'current-menu-parent', $item->classes ) || in_array( 'current-page-ancestor', $item->classes ) || in_array( 'current-post-ancestor', $item->classes );
    }
}

function get_author_age( $author_id, $before = '', $after = '', $echo = false, $format = 'U' ) {
    $birthday_str = get_field( 'und_birthday', 'user_' . $author_id );
    if ( $birthday_str != null && $birthday_str != "" ) {
        $birthday = DateTime::createFromFormat( $format, $birthday_str );
        if ( $birthday instanceof DateTime ) {
            $age = $birthday->diff( new DateTime() );
            if ( $echo ) {
                echo $before . $age->y . $after;
            } else {
                return $before . $age->y . $after;

            }
        }
    }
}

function get_the_lead( $before = '', $after = '', $echo = true ) {
    global $post;
    $lead = $post->post_excerpt;

    if ( strlen( $lead ) == 0 ) {
        return;
    }

    $lead = $before . $lead . $after;

    if ( $echo ) {
        echo $lead;
    } else {
        return $lead;
    }
}

function the_lead() {
    get_the_lead( '<p class="lead">', '</p>' );
}

function relative_date( $time, $emoji = true ) {
    if ( ! $time instanceof DateTime ) {
        $time = new DateTime( "@{$time}" );
    }

    $now   = new DateTime( 'now' );
    $today = new DateTime( 'today' );
    $today->modify( 'today midnight' );
    $dateDiff = $time->diff( $now );

    $future = $emoji && time() < $time->format( 'U' ) ? '&#9200; ' : '';

    // dateTime is tomorrow, today or yesterday
    if ( $dateDiff->days == 0 && time() - $today->format( 'U' ) >= time() - $time->format( 'U' ) ) {
        return $future . 'Heute, um ' . strftime('%k:%M', $time->getTimestamp());
    } else if ( $dateDiff->days <= 1 && $dateDiff->invert == 1 ) {
        return $future . 'Morgen, um ' . strftime('%k:%M', $time->getTimestamp());
    } else if ( $dateDiff->days <= 1 && $dateDiff->invert == 0 ) {
        return 'Gestern, um ' . strftime('%k:%M', $time->getTimestamp());
    }

    // dateTime is in the next or past 7 days
    if ( $dateDiff->days < 7 ) {
        if ( $dateDiff->invert == 1 ) {
            return $future . 'Nächsten ' . strftime('%A, %k:%M', $time->getTimestamp());
        } else {
            return 'Letzten ' . strftime('%A', $time->getTimestamp());
        }
    }

    // dateTime is in the next 30 days or else the rest
    if ( $dateDiff->days < 30 ) {
        return $future . strftime('%a, %e. %B', $time->getTimestamp());
    } else {
        if ( $now->format( 'Y' ) != $time->format( 'Y' ) ) {
            return $future . strftime('%e. %b. %Y', $time->getTimestamp());
        } else {
            return $future . strftime('%e. %B', $time->getTimestamp());
        }
    }
}

function filter_ptags_on_images( $content ) {
    $content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
    $content = preg_replace( '/<p>&nbsp;<\/p>/iU', '', $content );
    $content = preg_replace( '/<p>\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content ); //removes surrounding <p> tags from <iframe>

    return $content;
}

add_filter( 'the_content', 'filter_ptags_on_images' );

function is_current_subcategory( $category ) {
    if ( $category !== null ) {
        if ( is_category( $category->cat_ID ) ) {
            return true;
        } else if ( $category->parent != 0 ) {
            return is_current_subcategory( get_category( $category->parent ) );
        } else {
            return false;
        }
    }

    return false;
}

function is_subcategory( $parent, $search ) {
    if ( empty( $parent ) && empty( $search ) ) {
        return false;
    } else {
        if ( $parent == $search ) {
            return true;
        } else if ( $search->category_parent != 0 ) {
            if ( $parent->term_id == $search->category_parent ) {
                return true;
            } else {
                return is_subcategory( $parent, get_term( $search->category_parent ) );
            }
        }

        return false;
    }
}

function und_editor_styles( $init ) {

    $style_formats = "[
    {title: 'Paragraph', format: 'p', exact: true},
    {title: 'Überschriften', items: [
      {title: 'Überschriften 1', format: 'h2'},
      {title: 'Überschriften 2', format: 'h3'},
      {title: 'Überschriften 3', format: 'h4'},
    ]},
    {title: 'Zwischentitel', items: [
      {title: 'Zwischentitel 1', block: 'h3', classes: 'subheader'},
      {title: 'Zwischentitel 2', block: 'h4', classes: 'subheader'},
    ]}
  ]";

    $init['style_formats'] = $style_formats;

    $init['style_formats_merge'] = false;

    return $init;
}
add_filter( 'tiny_mce_before_init', 'und_editor_styles' );

/**
 * Ersetzt das Absatz Dropdown mit dem Formate Dropdown
 *
 * @param $buttons
 *
 * @return mixed
 */
function und_replace_mce_buttons( $buttons ) {
    array_splice( $buttons, 0, 1, 'styleselect' );

    return $buttons;
}
add_filter( 'mce_buttons', 'und_replace_mce_buttons' );

function hook_typekit_font_load() {
    ?>
    <script>
        (function (d) {
            var config = {
                    kitId: 'ane6ipz',
                    scriptTimeout: 3000,
                    async: true
                },
                h = d.documentElement, t = setTimeout(function () {
                    h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
                }, config.scriptTimeout), tk = d.createElement("script"), f = false, s = d.getElementsByTagName("script")[0], a;
            h.className += " wf-loading";
            tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
            tk.async = true;
            tk.onload = tk.onreadystatechange = function () {
                a = this.readyState;
                if (f || a && a != "complete" && a != "loaded") return;
                f = true;
                clearTimeout(t);
                try {
                    Typekit.load(config)
                } catch (e) {
                }
            };
            s.parentNode.insertBefore(tk, s)
        })(document);
    </script>
    <?php
}
add_action( 'wp_head', 'hook_typekit_font_load' );

function coauthors_remove_avatar_sizes() {
    return array( 64 );
}
add_action( 'coauthors_guest_author_avatar_sizes', 'coauthors_remove_avatar_sizes' );

function und_admin_bar_render() {
    global $wp_admin_bar;
    /** @var WP_Admin_Bar $wp_admin_bar */
    if ( is_author() && ( current_user_can( 'edit_users' ) || get_current_user_id() == get_the_author_meta( 'ID' ) ) ) {
        $wp_admin_bar->add_menu( array(
            'id'    => 'edit_user',
            'title' => __( 'Benutzer bearbeiten' ),
            'href'  => admin_url( 'user-edit.php?user_id=' . get_the_author_meta( 'ID' ) ),
        ) );
    }
    if ( is_category( get_category_by_slug( 'online' ) ) && current_user_can( 'moderator' ) ) {
        $wp_admin_bar->add_menu( array(
            'id'    => 'edit_frontpage',
            'title' => __( 'Startseite bearbeiten' ),
            'href'  => admin_url( 'post.php?action=edit&post=' . get_option( 'page_on_front' ) ),
        ) );
    }
}
add_action( 'wp_before_admin_bar_render', 'und_admin_bar_render' );

function render_frontpage_block( $post, $category = null, $type = 'post', $classes = '', $echo = true ) {
    if ( $category != null ) {
        $post->overview_category = $category;
    }
    if ( ! $echo ) {
        ob_start();
    }
    if ( $type == 'post' ) {
        set_query_var( 'additional_classes', $classes );
    }
    setup_postdata( $GLOBALS['post'] =& $post );
    get_template_part( 'template-parts/block/und-card', $type );
    wp_reset_postdata();
    if ( ! $echo ) {
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

function replace_schwerpunkt( $string ) {
    if ( $string != 'Schwerpunkt' ) {
        return str_replace( 'Schwerpunkt', 'SP', $string );
    } else {
        return $string;
    }
}

function und_get_parent_template( $term, $taxonomy, $post, $t ) {
    if ( isset( $term->slug ) ) {
        $slug     = $term->slug;
        $posttype = $post->post_type;
        if ( file_exists( TEMPLATEPATH . "/single-{$posttype}-{$slug}.php" ) ) {
            return TEMPLATEPATH . "/single-{$posttype}-{$slug}.php";
        } else if ( file_exists( TEMPLATEPATH . "/single-{$taxonomy}-{$slug}.php" ) ) {
            return TEMPLATEPATH . "/single-{$taxonomy}-{$slug}.php";
        } else if ( isset( $term->parent ) && $term->parent !== 0 ) {
            return und_get_parent_template( get_term( $term->parent, $taxonomy ), $taxonomy, $post, $t );
        } else {
            return $t;
        }
    } else {
        return $t;
    }
}

add_filter( 'single_template', 'und_check_for_category_single_template' );
function und_check_for_category_single_template( $t ) {
    global $post;
    if ( has_term( '', 'und_eventcat' ) ) {
        $taxonomy = $post->post_type == 'und_eventpost' ? 'und_eventcat' : 'category';

        //return und_get_parent_template( get_term( 1, $taxonomy ), $taxonomy, $post, $t );
    } elseif ( has_category() ) {
        $slug = get_the_category()[0]->slug;
        if ( file_exists( TEMPLATEPATH . "/single-category-{$slug}.php" ) ) {
            return TEMPLATEPATH . "/single-category-{$slug}.php";
        }
    }

    return $t;
}

function print_color( $arr ) {
    list( $R, $G, $B, $A ) = $arr;
    if ( count( $arr ) == 4 && $A == 1 ) {
        return "rgb($R,$G,$B)";
    } else {
        return "rgba($R,$G,$B,$A)";
    }
}

function get_color_from_hex( $rgb, $darker = 1, $A = 1 ) {
    $hash = ( strpos( $rgb, '#' ) !== false ) ? '#' : '';
    $rgb  = ( strlen( $rgb ) == 7 ) ? str_replace( '#', '', $rgb ) : ( ( strlen( $rgb ) == 6 ) ? $rgb : false );
    if ( strlen( $rgb ) != 6 ) {
        return $hash . '000000';
    }

    list( $R16, $G16, $B16 ) = str_split( $rgb, 2 );

    return print_color( [
        hexdec( $R16 ) / $darker,
        hexdec( $G16 ) / $darker,
        hexdec( $B16 ) / $darker,
        $A
    ] );
}

function get_contrast_color( $rgb ) {
    $hash = ( strpos( $rgb, '#' ) !== false ) ? '#' : '';
    $rgb  = ( strlen( $rgb ) == 7 ) ? str_replace( '#', '', $rgb ) : ( ( strlen( $rgb ) == 6 ) ? $rgb : false );
    if ( strlen( $rgb ) != 6 ) {
        return $hash . '#000000';
    }

    list( $R16, $G16, $B16 ) = str_split( $rgb, 2 );

    return ( hexdec( $R16 ) * 0.299 + hexdec( $G16 ) * 0.587 + hexdec( $B16 ) * 0.114 ) > 186 ? '#000000' : '#ffffff';
}

add_filter( 'cancel_comment_reply_link', 'und_cancel_comment_reply_link' );
function und_cancel_comment_reply_link( $formatted_link ) {
    return str_replace( 'rel="nofollow"', 'rel="nofollow" class="button secondary"', $formatted_link );
}

function und_the_tags( $before = '', $after = '' ) {
    $tags = get_the_tags();
    if ( ! empty( $tags ) ) {
        global $wp_query;
        echo $before;
        foreach ( $tags as $tag ) {
            $wp_query->used_tags[] = $tag;
            echo '<a href="' . get_tag_link( $tag ) . '" class="button round-tag tag tag-' . $tag->slug . '">' . $tag->name . '</a>';
        }
        echo $after;
    }
}

function und_register_cf7_js() {
    // Dequeue cf5 and recaptcha scripts, preventing them from loading everywhere
    add_filter( 'wpcf7_load_js', '__return_false' ); // Might as well use their filter
    wp_dequeue_script( 'google-recaptcha' );

    // If current post has cf7 shortcode, enqueue!
    global $post;
    if ( isset( $post->post_content ) AND has_shortcode( $post->post_content, 'contact-form-7' ) ) {
        if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
            wpcf7_enqueue_scripts();
            wp_enqueue_script( 'google-recaptcha' );
        }
    }
}

add_action( 'wp_enqueue_scripts', 'und_register_cf7_js', 20, 0 );
