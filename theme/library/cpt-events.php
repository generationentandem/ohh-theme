<?php

require_once('classes/Und_Event.php');
require_once('classes/Und_Event_Instance.php');

// 1. Custom Post Type Registration (Events)

add_action( 'init', 'und_create_event_postype' );

function und_create_event_postype() {
    $labels = array(
        'name'               => _x( 'Events', 'post type general name' ),
        'singular_name'      => _x( 'Event', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'events' ),
        'add_new_item'       => __( 'Add New Event' ),
        'edit_item'          => __( 'Edit Event' ),
        'new_item'           => __( 'New Event' ),
        'view_item'          => __( 'View Event' ),
        'search_items'       => __( 'Search Events' ),
        'not_found'          => __( 'No events found' ),
        'not_found_in_trash' => __( 'No events found in Trash' ),
        'parent_item_colon'  => '',
    );

    $args = array(
        'label'              => __( 'Events' ),
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'query_var'          => false,
        'can_export'         => true,
        'show_ui'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar',
        'menu_position'      => 25,
        'hierarchical'       => false,
        'rewrite'            => array( 'slug' => 'events', 'with_front' => false, 'hierarchical' => true ),
        'supports'           => array( 'title', 'thumbnail', 'excerpt', 'editor', 'und_eventcat' ),
        'show_in_nav_menus'  => true,
        'taxonomies'         => array( 'und_eventcat' ),
        'show_in_rest' => true,
    );

    register_post_type( 'und_eventpost', $args );
}


function und_create_eventcategory_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Kategorien', 'taxonomy general name' ),
        'singular_name'              => _x( 'Event Kategorie', 'taxonomy singular name' ),
        'search_items'               => __( 'Search Categories' ),
        'popular_items'              => __( 'Popular Categories' ),
        'all_items'                  => __( 'All Categories' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Category' ),
        'update_item'                => __( 'Update Category' ),
        'add_new_item'               => __( 'Add New Category' ),
        'new_item_name'              => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items'        => __( 'Add or remove categories' ),
        'choose_from_most_used'      => __( 'Choose from the most used categories' ),
    );

    register_taxonomy( 'und_eventcat', 'und_eventpost', array(
        'label'        => __( 'Event Category' ),
        'labels'       => $labels,
        'hierarchical' => true,
        'show_ui'      => true,
        'has_archive'  => true,
        'query_var'    => false,
        'rewrite'      => array( 'slug' => '%eventcat%', 'hierarchical' => true, 'with_front' => false ),
        'show_in_rest' => true,
    ) );
}

add_action( 'init', 'und_create_eventcategory_taxonomy', 0 );

add_filter( 'rewrite_rules_array', 'mmp_rewrite_rules', 8 );
function mmp_rewrite_rules( $rules ) {
    $newRules                          = array();
    $newRules['^.*/(.*?)/events/?$']   = 'index.php?taxonomy=und_eventcat&term=$matches[1]';
    $newRules['^(.*)/events/?$']       = 'index.php?taxonomy=und_eventcat&term=$matches[1]';
    $newRules['^(.*)/events/(.*?)/?$'] = 'index.php?post_type=und_eventpost&name=$matches[2]';

    return array_merge( $newRules, $rules );
}

function wpa_show_permalinks( $post_link, $post ) {
    if ( is_object( $post ) && $post->post_type == 'und_eventpost' ) {
        $terms = wp_get_object_terms( $post->ID, 'und_eventcat' );
        if ( $terms ) {
            $term = $terms[0];
            $slug = '';
            $slug .= $term->slug;
            while ( $term->parent !== 0 ) {
                $term = get_term( $term->parent );
                $slug = $term->slug . "/" . $slug;
            }

            return str_replace( '/events/', "/{$slug}/events/", $post_link );
        } else {
            return $post_link;
        }
    }

    return $post_link;
}

add_filter( 'post_type_link', 'wpa_show_permalinks', 1, 2 );

add_filter( 'term_link', 'term_link_filter', 10, 3 );
function term_link_filter( $url, $term, $taxonomy ) {

    if ( $taxonomy == 'und_eventcat' ) {
        return str_replace( '%eventcat%/', '', $url ) . "events";
    } else {
        return $url;
    }
}

// 3. Show Columns

add_filter( "manage_und_events_posts_columns", "und_events_columns" );
add_action( "manage_und_events_posts_custom_column", "und_events_custom_columns", 10, 2 );

function und_events_columns( $columns ) {
    $columns = array(
        "cb"                  => "<input type=\"checkbox\" />",
        "title"               => "Event",
        "und_event_datetimes" => "DateTimes",
        "und_eventcat"        => "Kategorie",
        "und_event_place"     => "Ort",
    );

    return $columns;
}

function readableTimespan( $start, $end ) {
    $endNotGiven = false;
    if ( ! is_numeric( $end ) ) {
        $end         = $start;
        $endNotGiven = true;
    }
    $start     = new DateTime( "@{$start}" );
    $end       = new DateTime( "@{$end}" );
    //test for whole day
    if ( '0000' == $start->format( 'Hi' ) && '0000' == $end->format( 'Hi' ) ) {
        if ( $start->diff( $end )->d == 1 ) {
            return strftime('%a %e. %b. %Y', $start->getTimestamp());
        } else {
            return strftime('%a %e. %b.', $start->getTimestamp()) . ' - ' . strftime('%a %e. %b. %Y', $end->getTimestamp());
        }
    } else {
        if ( $endNotGiven ) {
            return strftime('%a %e. %b. %Y %H:%M', $start->getTimestamp());
        } else {
            //test for not the same day
            if ( $start->format( 'Ymd' ) == $end->format( 'Ymd' ) ) {
                return strftime('%a %e. %b. %Y %H:%M', $start->getTimestamp()) . ' - ' . $end->format( 'H:i' );
            } else {
                return $start->format( 'd.m.Y H:i' ) . ' - ' . $end->format( 'd.m.Y H:i' );
            }
        }
    }
}

function und_events_custom_columns( $column, $post_id ) {
    switch ( $column ) {
        case "und_event_datetimes":
            echo '<ul style="margin: 0;list-style-type: disc">';
            while ( have_rows( 'und_event_timetable' ) ) : the_row();
                // Your loop code
                echo '<li class="und_event_instance">' . readableTimespan( get_sub_field( 'und_event_timetable_instancestart' ), get_sub_field( 'und_event_timetable_instanceend' ) ) . '</li>';

            endwhile;
            echo '</ul>';
            break;
        case "und_eventcat":
            // - show taxonomy terms -
            $eventcats      = get_the_terms( $post_id, "und_eventcat" );
            $eventcats_html = array();
            if ( $eventcats ) {
                foreach ( $eventcats as $eventcat ) {
                    array_push( $eventcats_html, $eventcat->name );
                }
                echo implode( $eventcats_html, ", " );
            } else {
                _e( 'None', 'und' );;
            }
            break;
        case "und_event_place":
            echo get_field( 'und_event_place', $post_id );
            break;
    }
}

/**
 * @param $args
 * @param bool $festival
 *
 * @return Und_Event[]
 */
function und_get_events( $args, $festival = true) {
    $std_args = array(
        'post_type'   => 'und_eventpost',
        'numberposts' => - 1,
        'tax_query'   => array(
            array(
                'taxonomy'         => 'und_eventcat',
                'field'            => 'id',
                'include_children' => true
            ),
            'orderby' => 'date',
            'order'   => 'DESC',
        )
    );

    $posts = get_posts( wp_parse_args( $args, $std_args ) );

    $events = [];

    foreach ($posts as $post) {
        $events[] = new Und_Event($post, $festival);
    }

    return $events;
}

function render_event($event, $template = 'tile') {

}
