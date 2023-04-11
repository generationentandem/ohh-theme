<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */


// Check to see if rev-manifest exists for CSS and JS static asset revisioning
//https://github.com/sindresorhus/gulp-rev/blob/master/integration.md

define('DIST_PATH', get_template_directory() . '/public');

if ( ! function_exists( 'foundationpress_asset_path' ) ) :
    function foundationpress_asset_path( $filename ) {
        $manifest = json_decode(file_get_contents(DIST_PATH . '/manifest.json'), true);

        if (is_array($manifest)) {
            foreach ( $manifest as $key => $value) {
                if (basename($key) == $filename) {
                    $filename = $value['file'];
                }

                if (basename($key, 'scss').'css' == $filename) {
                    $filename = $value['file'];
                }
            }
        }

        return $filename;
    }
endif;


if ( ! function_exists( 'foundationpress_scripts' ) ) :
	function foundationpress_scripts() {

		// Enqueue the main Stylesheet.
        wp_enqueue_style( 'main-stylesheet', get_stylesheet_directory_uri() . '/public/' . foundationpress_asset_path( 'app.css' ), array(), false, 'all' );

		// Deregister the jquery version bundled with WordPress.
		wp_deregister_script( 'jquery' );

		// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
        wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', array(), '3.2.1', false );

		// Deregister the jquery-migrate version bundled with WordPress.
		wp_deregister_script( 'jquery-migrate' );

		// CDN hosted jQuery migrate for compatibility with jQuery 3.x
        wp_register_script( 'jquery-migrate', '//code.jquery.com/jquery-migrate-3.0.1.min.js', array('jquery'), '3.0.1', false );

		// Enqueue jQuery migrate. Uncomment the line below to enable.
		// wp_enqueue_script( 'jquery-migrate' );

		// Enqueue Foundation scripts
        wp_enqueue_script( 'foundation', get_stylesheet_directory_uri() . '/public/' . foundationpress_asset_path( 'app.js' ), array( 'jquery' ), false, true );

		// Enqueue FontAwesome from CDN. Uncomment the line below if you need FontAwesome.
        wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/5016a31c8c.js', array(), '4.7.0', true );

		// Add the comment-reply library on pages where it is necessary
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

    add_action( 'wp_enqueue_scripts', 'foundationpress_scripts' );
endif;

add_action( 'wp_ajax_single_posts', 'und_ajax_single_posts' );
add_action( 'wp_ajax_nopriv_single_posts', 'und_ajax_single_posts' );

function get_first_match( $used_posts, $adding_posts ) {
    if ( count( $used_posts ) > 0 ) {
        $used_posts_ids = array_column($used_posts,'ID');
        $posts = array_values(array_filter($adding_posts,function ($var) use ($used_posts_ids){
            return !in_array($var->ID,$used_posts_ids) ;
        }));
        if(empty($posts)) {
            return null;
        }else {
            return $posts[0];
        }
    } else {
        if (empty($adding_posts)){
            return null;
        } else {
            return $adding_posts[0];
        }
    }
}

function und_ajax_single_posts() {
    $id = $_POST['id'];
    $suggested_posts = get_field( 'und_suggested_posts',$id);
    $similar_posts   = yarpp_get_related( array( 'limit' => 3, 'post_type' => 'post' ), $id );
    $shown_posts     = [];
    if ( $suggested_posts == null ) {
        $suggested_posts = array(
            array( 'und_suggestion_type' => 'similar_post' ),
            array( 'und_suggestion_type' => 'similar_post' ),
            array( 'und_suggestion_type' => 'similar_post' )
        );
    }
    foreach ($suggested_posts as $key => $entry){
        if ( $entry['und_suggestion_type'] == 'specific_post' ) {
            $shown_posts[$key] = $entry['und_specific_post'];
        }
    }
    foreach ( $suggested_posts as $key => $post ) {
        if ( $post['und_suggestion_type'] == 'similar_post' ) {
            $similar_post = get_first_match( $shown_posts, $similar_posts );
            if ( $similar_post ) {
                $shown_posts[$key] = $similar_post;
            }
        } elseif ( $post['und_suggestion_type'] == 'category_post' ) {
            $category_posts  = get_posts( array(
                'posts_per_page' => 3,
                'exclude'        => $id,
                'post_type'      => 'post',
                'category'       => $post['und_category_post']
            ) );
            $category_post = get_first_match( $shown_posts, $category_posts );
            if ( $category_post ) {
                $shown_posts[$key] = $category_post;
            }
        }
    }
    foreach ($shown_posts as $post) {
        render_frontpage_block( $post,null, 'post' );
    }
    die();
}
