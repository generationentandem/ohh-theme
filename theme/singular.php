<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header();
und_get_category_line();
?>
	<main>
		<div class="singular-container">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', is_page() ? 'page' : get_post_format() ); ?>
			<?php endwhile; ?>
		</div>
	</main>
<?php get_footer();
