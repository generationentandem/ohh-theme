<?php
/**
 * The default template for displaying page content
 *
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <div class="post-wrapper">
            <?php
            if ( in_category( 9218 ) ) {
                echo '<h3 class="entry-title h6 text-center">Veranstaltung</h3>';
            }
            ?>
            <h1 class="entry-title alignwide">
                <?php the_title(); ?>
            </h1>
            <?php the_lead() ?>
        </div>
    </header>
    <section class="entry-content">
        <div class="post-wrapper">
            <?php the_content(); ?>
            <?php edit_post_link( __( 'Edit', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>
        </div>
    </section>
</article>
