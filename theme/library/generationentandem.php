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
