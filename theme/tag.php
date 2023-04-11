<?php
/**
 * The template for displaying
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

	<div class="wrapper">
		<main>
			<header class="page-header">
				<h1><?php echo 'Tag: #'.get_queried_object()->name ?></h1>
				<p><?php echo get_queried_object()->description ?></p>
			</header>
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
		</main>
	</div>
<?php get_footer();
