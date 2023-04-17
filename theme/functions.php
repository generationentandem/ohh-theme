<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

function debugging() {
    /* Produces a dump on the state of WordPress when a not found error occurs */
    /* useful when debugging permalink issues, rewrite rule trouble, place inside functions.php */

    ini_set( 'error_reporting', - 1 );
    ini_set( 'display_errors', 'On' );

    echo '<pre>';

    add_action( 'parse_request', 'debug_404_rewrite_dump' );
    function debug_404_rewrite_dump( &$wp ) {
        global $wp_rewrite;

        echo '<h2>rewrite rules</h2>';
        echo var_export( $wp_rewrite->wp_rewrite_rules(), true );

        echo '<h2>permalink structure</h2>';
        echo var_export( $wp_rewrite->permalink_structure, true );

        echo '<h2>page permastruct</h2>';
        echo var_export( $wp_rewrite->get_page_permastruct(), true );

        echo '<h2>matched rule and query</h2>';
        echo var_export( $wp->matched_rule, true );

        echo '<h2>matched query</h2>';
        echo var_export( $wp->matched_query, true );

        echo '<h2>request</h2>';
        echo var_export( $wp->request, true );

        global $wp_the_query;
        echo '<h2>the query</h2>';
        echo var_export( $wp_the_query, true );
    }

    add_action( 'template_redirect', 'debug_404_template_redirect', 99999 );
    function debug_404_template_redirect() {
        global $wp_filter;
        echo '<h2>template redirect filters</h2>';
        echo var_export( $wp_filter[ current_filter() ], true );
    }

    add_filter( 'template_include', 'debug_404_template_dump' );
    function debug_404_template_dump( $template ) {
        echo '<h2>template file selected</h2>';
        echo var_export( $template, true );

        echo '</pre>';
        exit();
    }
}

//debugging();

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Format comments */
require_once( 'library/class-foundationpress-comments.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

require_once( 'library/cpt-events.php' );

/** remove category base */
require_once( 'library/categories.php' );

/** Add menu walkers for top-bar and off-canvas */
require_once( 'library/class-foundationpress-top-bar-walker.php' );
require_once( 'library/class-foundationpress-mobile-walker.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
require_once( 'library/sticky-posts.php' );

/** Configure responsive image sizes */
require_once( 'library/responsive-images.php' );

/** All special scripts for generationentandem */
require_once( 'library/generationentandem.php' );

/** Gutenberg editor support */
require_once( 'library/gutenberg.php' );

/** Limit excerpt length, use echo excerpt($length) instead of the_excerpt() */
function excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return $excerpt;
}

function my_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyDpeWOxbFnIUYvLJtN2HM_7YnpCEVhTKuE');
}

add_action('acf/init', 'my_acf_init');

add_action( 'admin_enqueue_scripts', 'load_custom_script' );
function load_custom_script() {
    wp_enqueue_script('custom_js_script', get_bloginfo('template_url').'/public/js/custom-script.js', array('jquery'));
}
