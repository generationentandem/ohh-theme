<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>


<header style="background-image: url(<?php the_post_thumbnail_url('und-small'); ?>)" class="<?php echo ! get_the_post_thumbnail_url() ? 'no-thumbnail' : ''; ?>">
	<a href="<?php the_permalink(); ?>">
	</a>
</header>

<section class="frontpage-content">
	<div class="meta-information">
		<time title="<?php echo date_i18n( "l, d. F Y | H:i:s", get_the_date( "U" ) ) ?>"
		      datetime="<?php echo get_the_date( 'U' ) ?>"><?php echo relative_date( DateTime::createFromFormat("U",get_the_date( 'U' ) ) )?></time>
		<?php get_template_part( 'template-parts/category-list' ); ?>
	</div>

	<h2 class="<?php echo( ( strlen( get_the_title() ) < 30 && ! get_the_post_thumbnail_url() ) ? '' : 'small' ) ?>"><a
				href="<?php the_permalink(); ?>"><?php echo str_replace('Generationentandem','Generationen&shy;tandem',get_the_title()); ?></a></h2>
	<div class="excerpt">
		<?php
		$title = get_the_title();
		if ( strlen( $title ) < 65 ) {
			the_excerpt();
		}
		?>
	</div>

	<?php get_template_part( 'template-parts/author' ); ?>
</section>
