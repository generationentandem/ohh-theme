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
<article id='post-<?php echo $post['id'] ?>' <?php post_class( $classes ) ?> itemscope itemtype="https://schema.org/Article">
	<a href="/aktuelle-news/?post=<?php echo $post['id'] ?>" class="card-image">
        <img style="height: 12em" src="<?php echo $post['_embedded']['wp:featuredmedia'][0]['source_url'] ?>" />
	</a>
	<section class="card-section">
		<div class="card-meta">
			<time itemprop="datePublished" title="<?php echo date_i18n("l, d. F Y | H:i:s", $post['date']) ?>"
                datetime="<?php echo $post['date'] ?>">
                <?php echo relative_date(DateTime::createFromFormat("Y-m-d\TH:i:s", $post['date'])) ?>
            </time>
		</div>
		<a href="/aktuelle-news?post=<?php echo $post['id'] ?>" class="card-link">
			<?php
			$title = $post['title']['rendered'];
            $has_thumbnail = $post['_embedded']['wp:featuredmedia'][0]['source_url'] == false;
			echo "<h2 itemprop='name' class='card-title h4 " . ( ! $has_thumbnail && strlen( $title ) < 30 && get_longest_word( $title ) < 14 ? "" : "small" ) . "'>${title}</h2>";
			?>
		</a>
		<?php if ( ! $has_thumbnail && get_post()->post_type != 'page' && strlen( $title ) < 50 ) { ?>
			<div class="card-excerpt" itemprop="description">
				<?php
				    echo $post['excerpt']['rendered'];
                ?>
			</div>
			<?php
		}
		?>
	</section>
</article>
