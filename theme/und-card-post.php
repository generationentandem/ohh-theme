<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */
$featured = get_query_var('additional_classes',false);
$classes = 'card post-card ' . (($featured)?$featured:'');
?>
<article id='post-<?php the_ID(); ?>' <?php post_class( $classes ) ?> itemscope itemtype="https://schema.org/Article">
	<a href="<?php the_permalink(); ?>" class="card-image">
		<?php  the_post_thumbnail( $featured == 'featured-card' ? 'und-frontpage_2x': 'und-frontpage' ) ?>
	</a>
		<section class="card-section">
			<div class="card-meta">
				<time itemprop="datePublished" title="<?php echo date_i18n( "l, d. F Y | H:i:s", get_the_date( "U" ) ) ?>"
				      datetime="<?php echo get_the_date( DATE_W3C ) ?>"><?php echo relative_date( DateTime::createFromFormat( "U", get_the_date( 'U' ) ) ) ?></time>
				<?php get_template_part( 'template-parts/category-list' ); ?>
			</div>
			<a href="<?php the_permalink(); ?>" class="card-link">
				<?php
				$title         = get_the_title();
				$has_thumbnail = get_the_post_thumbnail_url() == false;
				echo "<h2 itemprop='name' class='card-title " . ($featured?'h3 ':'h4 ') . ( ! $has_thumbnail && strlen( $title ) < 30 && get_longest_word( $title ) < 14 ? "" : "small" ) . "'>${title}</h2>";
				?>
			</a>
			<?php if ( ! $has_thumbnail && get_post()->post_type != 'page' && strlen( $title ) < 50 ) { ?>
				<div class="card-excerpt" itemprop="description">
					<?php //the_excerpt();
					echo excerpt(30); ?>
				</div>
				<?php
			}
			?>
			<div class="author-part" style="overflow: hidden;"><?php
			get_template_part( 'template-parts/author' ); ?>
			</div>
		</section>
</article>
