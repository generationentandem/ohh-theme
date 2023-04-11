<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>
<?php if(!is_category('online')):?>
    <header class="archive-header" style="border-top: 10px solid <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>; border-bottom: 10px solid <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;
    <?php echo get_field( 'bild', $wp_query->get_queried_object() ) ? 'background: url(' . wp_get_attachment_image_src( get_field( 'bild', $wp_query->get_queried_object() ), 'und-large' )[0] . ') center;' : '' ?>">
        <div class="archive-headercontent <?php echo is_category()?'bg-cat-'.$wp_query->get_queried_object_id():'' ?>">
            <h2><?php echo single_term_title(); ?></h2>
            <?php echo term_description() ?>
        </div>
    </header>
    <?php
    und_get_category_line();
    ?>
<?php endif; ?>
    <div class="wrapper">
        <section class="cards-container" role="main">

            <?php do_action( 'foundationpress_before_content' ); ?>

            <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/block/und-card', 'post' ); ?>
                <?php endwhile; ?>

            <?php else : ?>
                <?php get_template_part( 'template-parts/content', 'none' ); ?>

            <?php endif; ?>


        </section>

        <?php do_action( 'foundationpress_before_pagination' ); ?>

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

        <?php do_action( 'foundationpress_after_content' ); ?>

    </div>
<?php
if(is_category()){
    $wp_query->used_categories[] = $wp_query->get_queried_object_id();
    $acf_term = get_queried_object();
    $footerSuffix = get_field('und_custom_footer', $acf_term);
}
get_footer($footerSuffix);
