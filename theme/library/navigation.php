<?php
/**
 * Register Menus
 *
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menus#Examples
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

register_nav_menus(
    array(
        'main-menu'       => __( 'Hauptmenu', 'und' ),
        'secondary-menu'  => __( 'Kategoriemenu', 'und' ),
        'meta-menu-left'  => __( 'Metanavigation Links', 'und' ),
        'meta-menu-left-mobile'  => __( 'Metanavigation Links Mobile', 'und' ),
        'meta-menu-right' => __( 'Metanavigation Rechts', 'und' )
    )
);

/**
 * Desktop navigation - right top bar
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
if ( ! function_exists( 'foundationpress_top_bar_r' ) ) {
    function foundationpress_top_bar_r() {
        wp_nav_menu(
            array(
                'container'      => false,
                'menu_class'     => 'dropdown menu',
                'items_wrap'     => '<ul id="%1$s" class="%2$s desktop-menu" data-dropdown-menu>%3$s</ul>',
                'theme_location' => 'top-bar-r',
                'depth'          => 3,
                'fallback_cb'    => false,
                'walker'         => new Foundationpress_Top_Bar_Walker(),
            )
        );
    }
}


/**
 * Mobile navigation - topbar (default) or offcanvas
 */
if ( ! function_exists( 'foundationpress_mobile_nav' ) ) {
    function foundationpress_mobile_nav() {
        wp_nav_menu(
            array(
                'container'      => false,                         // Remove nav container
                'menu'           => __( 'mobile-nav', 'foundationpress' ),
                'menu_class'     => 'vertical menu',
                'theme_location' => 'mobile-nav',
                'items_wrap'     => '<ul id="%1$s" class="%2$s" data-accordion-menu data-submenu-toggle="true">%3$s</ul>',
                'fallback_cb'    => false,
                'walker'         => new Foundationpress_Mobile_Walker(),
            )
        );
    }
}


/**
 * Add support for buttons in the top-bar menu:
 * 1) In WordPress admin, go to Apperance -> Menus.
 * 2) Click 'Screen Options' from the top panel and enable 'CSS CLasses' and 'Link Relationship (XFN)'
 * 3) On your menu item, type 'has-form' in the CSS-classes field. Type 'button' in the XFN field
 * 4) Save Menu. Your menu item will now appear as a button in your top-menu
*/
if ( ! function_exists( 'foundationpress_add_menuclass' ) ) {
    function foundationpress_add_menuclass( $ulclass ) {
        $find    = array( '/<a rel="button"/', '/<a title=".*?" rel="button"/' );
        $replace = array( '<a rel="button" class="button"', '<a rel="button" class="button"' );

        return preg_replace( $find, $replace, $ulclass, 1 );
    }

    add_filter( 'wp_nav_menu', 'foundationpress_add_menuclass' );
}

/**
 * Adapted for Foundation from http://thewebtaylor.com/articles/wordpress-creating-breadcrumbs-without-a-plugin
 *
 * @param bool $showhome should the breadcrumb be shown when on homepage (only one deactivated entry for home).
 * @param bool $separatorclass should a separator class be added (in case :before is not an option).
 */
if ( ! function_exists( 'foundationpress_breadcrumb' ) ) {
    function foundationpress_breadcrumb( $showhome = true, $separatorclass = false ) {
        // Settings
        $separator  = '&gt;';
        $id         = 'breadcrumbs';
        $class      = 'breadcrumbs';
        $home_title = 'Homepage';

        // Get the query & post information
        global $post, $wp_query;
        $category = get_the_category();

        // Build the breadcrums
        echo '<ul id="' . $id . '" class="' . $class . '">';

        // Do not display on the homepage
        if ( ! is_front_page() ) {
            // Home page
            echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
            if ( $separatorclass ) {
                echo '<li class="separator separator-home"> ' . $separator . ' </li>';
            }

            if(is_single() && !is_attachment()) {
                // Single post (Only display the first category)
                echo '<li class="item-cat item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><a class="bread-cat bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '" href="' . get_category_link( $category[0]->term_id ) . '" title="' . $category[0]->cat_name . '">' . $category[0]->cat_name . '</a></li>';
                if ( $separatorclass ) {
                    echo '<li class="separator separator-' . $category[0]->term_id . '"> ' . $separator . ' </li>';
                }
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
            } elseif(is_category()) {
                // Category page
                echo '<li class="item-current item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><strong class="bread-current bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '">' . $category[0]->cat_name . '</strong></li>';
            } elseif(is_page()) {
                // Standard page
                if ( $post->post_parent ) {
                    // If child page, get parents
                    $anc = get_post_ancestors( $post->ID );

                    // Get parents in the right order
                    $anc = array_reverse( $anc );

                    // Parent page loop
                    $parents = '';
                    foreach ( $anc as $ancestor ) {
                        $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink( $ancestor ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
                        if ( $separatorclass ) {
                            $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                        }
                    }

                    // Display parent pages
                    echo $parents;

                    // Current page
                    echo '<li class="current item-' . $post->ID . '">' . get_the_title() . '</li>';
                } else {
                    // Just display current page if not parents
                    echo '<li class="current item-' . $post->ID . '"> ' . get_the_title() . '</li>';
                }
            } elseif(is_tag()) {
                // Tag page
                // Get tag information
                $term_id  = get_query_var( 'tag_id' );
                $taxonomy = 'post_tag';
                $args     = 'include=' . $term_id;
                $terms    = get_terms( $taxonomy, $args );

                // Display the tag name
                echo '<li class="current item-tag-' . $terms[0]->term_id . ' item-tag-' . $terms[0]->slug . '">' . $terms[0]->name . '</li>';
            } elseif(is_day()) {
                // Day archive
                // Year link
                echo '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>';
                if ( $separatorclass ) {
                    echo '<li class="separator separator-' . get_the_time( 'Y' ) . '"> ' . $separator . ' </li>';
                }

                // Month link
                echo '<li class="item-month item-month-' . get_the_time( 'm' ) . '"><a class="bread-month bread-month-' . get_the_time( 'm' ) . '" href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( 'M' ) . '">' . get_the_time( 'M' ) . ' Archives</a></li>';
                if ( $separatorclass ) {
                    echo '<li class="separator separator-' . get_the_time( 'm' ) . '"> ' . $separator . ' </li>';
                }

                // Day display
                echo '<li class="current item-' . get_the_time( 'j' ) . '">' . get_the_time( 'jS' ) . ' ' . get_the_time( 'M' ) . ' Archives</li>';
            } elseif(is_month()) {
                // Month Archive
                // Year link
                echo '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>';
                if ( $separatorclass ) {
                    echo '<li class="separator separator-' . get_the_time( 'Y' ) . '"> ' . $separator . ' </li>';
                }

                // Month display
                echo '<li class="item-month item-month-' . get_the_time( 'm' ) . '">' . get_the_time( 'M' ) . ' Archives</li>';
            } elseif(is_year()) {
                // Display year archive
                echo '<li class="current item-current-' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</li>';
            } elseif(is_author()) {
                // Auhor archive
                // Get the author information
                global $author;
                $userdata = get_userdata( $author );

                // Display author name
                echo '<li class="current item-current-' . $userdata->user_nicename . '">Author: ' . $userdata->display_name . '</li>';
            } elseif(get_query_var( 'paged' )) {
                // Paginated archives
                echo '<li class="current item-current-' . get_query_var( 'paged' ) . '">' . __( 'Page', 'foundationpress' ) . ' ' . get_query_var( 'paged' ) . '</li>';
            } elseif(is_search()) {
                // Search results page
                echo '<li class="current item-current-' . get_search_query() . '">Search results for: ' . get_search_query() . '</li>';
            } elseif(is_404()) {
                // 404 page
                echo '<li>Error 404</li>';
            } // End if().
        } else {
            if ( $showhome ) {
                echo '<li class="item-home current">' . $home_title . '</li>';
            }
        } // End if().
        echo '</ul>';
    }
}

/**
 * Main Class
 */
class jb_nav_menu {
    // Variables
    private $_menu_items = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize plugin
        add_action( 'init', array( $this, 'init' ), 1 );
    }

    /**
     * Initializes the plugin
     */
    public function init() {
        // Add filters
        add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
    }

    /**
     * Extends the default function
     *
     * @param array $sorted_menu_items
     * @param object $args
     *
     * @return array
     */
    public function wp_nav_menu_objects( $sorted_menu_items, $args ) {
        global $wp_query;

        $this->_menu_items      = array();
        $this->_menu_items[0]   = false;
        $root_menu_items        = [];
        $items_current_parent   = [];
        $items_current_ancestor = [];

        foreach ( $sorted_menu_items as $sorted_menu_item ) {
            $this->_menu_items[ $sorted_menu_item->ID ] = $sorted_menu_item;
            if ( $sorted_menu_item->menu_item_parent == 0 ) {
                $root_menu_items[] = $sorted_menu_item->ID;
            }
            if ( $sorted_menu_item->current_item_parent ) {
                $sorted_menu_item->classes[] = 'current-item-parent';
                $items_current_ancestor[]    = $sorted_menu_item->menu_item_parent;
            }
            if ( $sorted_menu_item->current_item_ancestor ) {
                $sorted_menu_item->classes[] = 'current-item-parent';
                $items_current_ancestor[]    = $sorted_menu_item->menu_item_parent;
            }
        }

        foreach ( $items_current_parent as $sorted_menu_item ) {
            $parent = $this->_menu_items[ $sorted_menu_item ];
            if ( $parent ) {
                $parent->current_item_ancestor         = true;
                $parent->classes[]                     = 'current-item-ancestor';
                $items_current_ancestor[ $parent->ID ] = $parent;
            }
        }

        foreach ( array_unique( $items_current_ancestor ) as $sorted_menu_item ) {
            $this->propagate_ancestor_up( $sorted_menu_item );
        }


        unset( $this->_menu_items[0] );

        // Return updated items
        return $this->_menu_items;
    }

    private function propagate_ancestor_up( $id_parent ) {
        $menu_item = $this->_menu_items[ $id_parent ];
        if ( $menu_item ) {
            if ( ! $menu_item->current_item_ancestor ) {
                $menu_item->current_item_ancestor = true;
                $menu_item->classes[]             = 'current-item-ancestor';
                $this->propagate_ancestor_up( $menu_item->menu_item_parent );
            }
            $menu_item->classes = array_unique( $menu_item->classes );
        }
    }
}

// Initialize Plugin!
new jb_nav_menu();

function und_nav_menu_submenu_css_class( $classes, $args, $depth ) {
    if ( isset( $args->not_subnav ) ) {
        return array( 'menu' );
    } else {
        return $classes;
    }
}

class Und_Walker_Nav_Menu extends Walker_Nav_Menu {
    public $current_item;
    public $active_submenu_item;
    public $style = array();
    public $current_top_item;
    public $submenu = "";
    private $hierarchy_type = null;
    public $primary_category;
    public $primary_term;
    private $child_items;
    public $output_pre_submenu;

    public function walk( $elements, $max_depth, ...$args ) {
        $output = '';

        // Invalid parameter or nothing to walk.
        if ( $max_depth < -1 || empty( $elements ) ) {
            return $output;
        }

        $parent_field = $this->db_fields['parent'];

        // flat display
        if ( - 1 == $max_depth ) {
            $empty_array = array();
            foreach ( $elements as $e ) {
                $this->display_element( $e, $empty_array, 1, 0, $args, $output );
            }

            return $output;
        }

        /*
         * Need to display in hierarchical order.
         * Separate elements into two buckets: top level and children elements.
         * Children_elements is two dimensional array, eg.
         * Children_elements[10][] contains all sub-elements whose parent is 10.
         */
        $top_level_elements          = array();
        $current_top_level_elements  = array();
        $parent_top_level_elements   = array();
        $ancestor_top_level_elements = array();

        $children_elements = array();
        foreach ( $elements as $e ) {
            if ( empty( $e->$parent_field ) ) {
                $top_level_elements[]                  = $e;
                $named_top_level_elements[ $e->title ] = $e;
                if ( $e->current ) {
                    $current_top_level_elements[] = $e;
                } elseif ( $e->current_item_parent ) {
                    $parent_top_level_elements[] = $e;
                } elseif ( $e->current_item_ancestor ) {
                    $ancestor_top_level_elements[] = $e;
                }
            } else {
                $children_elements[ $e->$parent_field ][] = $e;
            }
        }
        $this->child_items = $children_elements;


        if ( is_archive() ) {
            $this->primary_term = get_queried_object();
            if ( isset( $this->primary_term->taxonomy ) ) {
                $this->hierarchy_type = $this->primary_term->taxonomy;
            }
        } elseif ( is_single() && ! is_attachment() ) {
            if ( has_category() ) {
                $this->hierarchy_type = 'category';
                $this->primary_term   = get_the_category()[0];
            } elseif ( has_term( '', 'und_eventcat' ) ) {
                $this->hierarchy_type = 'und_eventcat';
                $this->primary_term = get_terms()[0];
            }
        }

        if ( count( $current_top_level_elements ) > 0 ) {
            $this->current_top_item = $current_top_level_elements[0];
        } elseif ( $args[0]->theme_location == 'main-menu' && ! empty( $this->primary_term ) ) {
            $this->current_top_item = $named_top_level_elements[ $this->associate_term_with_primarynav( $this->hierarchy_type, $this->primary_term ) ];
        } elseif ( count( $parent_top_level_elements ) > 0 ) {
            $this->current_top_item = $parent_top_level_elements[0];
        } elseif ( count( $ancestor_top_level_elements ) > 0 ) {
            if ( count( $ancestor_top_level_elements ) == 1 ) {
                $this->current_top_item = $ancestor_top_level_elements[0];
            } else {
                if ( is_single() && ! is_attachment() ) {
                    foreach ( $ancestor_top_level_elements as $ancestor_top_level_element ) {
                        if ( $this->is_category_menu_items( $ancestor_top_level_element ) ) {
                            $this->current_top_item = $ancestor_top_level_element;
                            break;
                        }
                    }
                }
            }
        } else {
            $this->current_top_item = $top_level_elements[0];
        }

        if ( empty( $this->current_top_item ) ) {
            //$this->current_top_item = $top_level_elements[0];
        }

        /*
         * When none of the elements is top level.
         * Assume the first one must be root of the sub elements.
         */
        if ( empty( $top_level_elements ) ) {

            $first = array_slice( $elements, 0, 1 );
            $root  = $first[0];

            $top_level_elements = array();
            $children_elements  = array();
            foreach ( $elements as $e ) {
                if ( $root->$parent_field == $e->$parent_field ) {
                    $top_level_elements[] = $e;
                } else {
                    $children_elements[ $e->$parent_field ][] = $e;
                }
            }
        }

        foreach ( $top_level_elements as $e ) {
            if ( $e->ID == $this->current_top_item->ID ) {
                $e->classes[] = 'active';
                $this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
            } else {
                $this->display_element( $e, $children_elements, 1, 0, $args, $output );
            }
        }

        /*
         * If we are displaying all levels, and remaining children_elements is not empty,
         * then we got orphans, which should be displayed regardless.
         */
        if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
            $empty_array = array();
            foreach ( $children_elements as $orphans ) {
                foreach ( $orphans as $op ) {
                    $this->display_element( $op, $empty_array, 1, 0, $args, $output );
                }
            }
        }

        return $output;
    }

    private function get_category_menu_items( $menu_item ) {
        $cat = [];
        if ( isset( $this->child_items[ $menu_item->ID ] ) ) {
            foreach ( $this->child_items[ $menu_item->ID ] as $child_item ) {
                array_merge( $cat, $this->get_category_menu_items( $child_item ) );
            }
        }
        if ( $menu_item->type == 'category' ) {
            $cat[] = $menu_item->object_id;
        }

        return $cat;
    }

    private function is_category_menu_items( $menu_item ) {
        $cat = false;
        if ( isset( $this->child_items[ $menu_item->ID ] ) ) {
            foreach ( $this->child_items[ $menu_item->ID ] as $child_item ) {
                $cat = $cat || $this->is_category_menu_items( $child_item );
            }
        }

        return $cat = $cat || $menu_item->object == 'category' && $menu_item->object_id == $this->primary_term->term_id || is_subcategory( get_category( $menu_item->object_id ), $this->primary_term );
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if ( ! empty( $this->output_pre_submenu ) && $item->type == 'taxonomy' ) {
            $color = get_taxonomy_color( $item->object_id );
            if ( $color ) {
                $this->style[ $item->ID ] = $color;
            }
        }
        parent::start_el( $output, $item, $depth, $args, $id );
        if ( $item == $this->current_top_item ) {
            $this->output_pre_submenu = "" . $output;
            add_filter( 'nav_menu_submenu_css_class', 'und_nav_menu_submenu_css_class', 0, 3 );
        }
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        if ( $this->output_pre_submenu == $output ) {
            $args->not_subnav = true;
        }
        parent::start_lvl( $output, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if ( $item == $this->current_top_item ) {
            $this->submenu            = str_replace( $this->output_pre_submenu, '', $output );
            $output                   = $this->output_pre_submenu;
            $this->output_pre_submenu = "";
        }
        parent::end_el( $output, $item, $depth, $args );
    }

    private $term_primarynav = [
        'und_eventcat' => [
            'generationenfestival' => 'festival',
            'live'                 => 'live',
            null                   => 'online'
        ],
        'category'     => [
            'generationenfestival' => 'festival',
            'live'                 => 'live',
            'print'                => 'print',
            'ueber-und'            => 'about',
            'about'                => 'about',
            null                   => 'online'
        ]
    ];

    private function associate_term_with_primarynav( $type, $term ) {
        if ( $type == 'category' ) {
            $ancestors = get_ancestors( $term->term_id, 'category' );
            if ( empty( $ancestors ) ) {
                $slug = $term->slug;
            } else {
                $slug = get_term( array_reverse( $ancestors )[0] )->slug;
            }
            if ( in_array( $slug, array_keys( $this->term_primarynav[ $type ] ) ) ) {
                return $this->term_primarynav[ $type ][ $slug ];
            } else {
                return 'online';
            }
        } elseif ( $type == 'und_eventcat' ) {
            $slug = ( $term->parent == 0 ? $term->slug : get_term( array_reverse( get_ancestors( $term->term_id, 'und_eventcat' ) )[0] )->slug );
            if ( in_array( $slug, array_keys( $this->term_primarynav[ $type ] ) ) ) {
                return $this->term_primarynav[ $type ][ $slug ];
            } else {
                return 'online';
            }
        } else {
            return 'online';
        }
    }

    private function search_parent_term( $search, $term ) {
        if ( in_array( $term->slug, array_keys( $search ) ) ) {
            return $search[ $term->slug ];
        } else {
            if ( $term->parent == 0 ) {
                return 'online';
            } else {
                return $this->search_parent_term( $search, get_term( $term->parent ) );
            }
        }
    }
}
