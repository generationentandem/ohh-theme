<?php
/**
 * Entry meta information for posts
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_entry_meta' ) ) :
    function foundationpress_entry_meta() {
        echo '<time class="updated" datetime="' . get_the_time( 'c' ) . '">'.relative_date(DateTime::createFromFormat("U",get_the_date('U'))).'</time>';
    }
endif;
