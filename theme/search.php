<?php
/**
 * The template for displaying search results pages.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>
    <div class="wrapper">
        <h1 class="page-title"><?php _e( 'Suchresultate für ', 'foundationpress' ); ?> «<?php echo get_search_query(); ?>»</h1>

        <?php if ( have_posts() ) : ?>
        <?php get_search_form(); ?>
        <section class="cards-container" role="main">


            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/block/und-card', 'post' ); ?>
            <?php endwhile; ?>

            <?php else : ?>
            <section class="cards-container" role="main">

                <?php get_template_part( 'template-parts/content', 'none' ); ?>

                <?php endif; ?>


            </section>

            <?php
            if ( function_exists( 'foundationpress_pagination' ) ) :
                foundationpress_pagination();
            elseif ( is_paged() ) :
                ?>

                <nav id="post-nav">
                    <div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
                    <div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
                </nav>
            <?php endif; ?>

    </div>

<?php get_footer();
