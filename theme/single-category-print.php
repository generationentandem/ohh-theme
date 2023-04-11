<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<div class="archive-pagination wrapper">
		<div class="previous_post"><?php previous_post_link( '&laquo; %link', '%title', true ); ?></div>
		<div class="next_post"><?php next_post_link( '%link &raquo;', '%title', true ); ?></div>
	</div>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'wrapper' ); ?>>
		<?php the_post_thumbnail( 'large', array( 'class' => 'image_cover' ) ) ?>
		<header>
			<h1>
				<?php
				$title          = get_the_title();
				$title_exploded = explode( "–", $title, 2 );
				if ( count( $title_exploded ) > 1 ) {
					echo "<span class='small'>$title_exploded[0]</span><span>$title_exploded[1]</span>";
				} else {
					echo "<span>$title</span>";
				}
				?>
			</h1>
			<?php the_lead() ?>
			<div class="meta-information">
				<time itemscope itemtype="http://schema.org/datePublished"
				      title="<?php echo date_i18n( "l, d. F Y | H:i:s", get_the_date( "U" ) ) ?>"
				      datetime="<?php echo get_the_date( 'U' ) ?>"><?php echo date_i18n( "l, d. F Y", get_the_date( "U" ) ) ?></time>
			</div>

		</header>
		<?php get_template_part( 'template-parts/category-print' ); ?>
	</article>
<?php endwhile; ?>

<?php do_action( 'foundationpress_after_content' ); ?>
<?php get_footer();
