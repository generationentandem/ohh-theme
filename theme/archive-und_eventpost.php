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
			<h2><?php echo 'Alle Events'; ?></h2>
			<?php echo term_description() ?>
		</div>
	</header>
	<?php
	und_get_category_line();

	// get events
	$events = und_get_events( array(
		'post_type'   => 'und_eventpost',
		'numberposts' => -1,
			),false );

	$future_instances = [];
	$past_instances   = [];

	/** @var Und_Event[] $events**/
	foreach ( $events as $event ) {
		foreach ( $event->instances as $instance ) {
			if ( isset( $instance->end ) ) {
				if ( $instance->end() < time() ) {
					$past_instances[] = $instance;
				} else {
					$future_instances[] = $instance;
				}
			} else {
				if ( $instance->start() + TWOHOURS < time() ) {
					$past_instances[] = $instance;
				} else {
					$future_instances[] = $instance;
				}
			}
		}
	}

	?>
<?php endif; ?>
	<div class="wrapper">
		<section class="tile-container" role="main">
			<?php // add Events here
			$view = 'past_instances';

			if($future_instances){
				foreach ($future_instances as $future_instance) {
					$post = $future_instance->event->post;
					setup_postdata($GLOBALS['post'] =& $post);
					set_query_var('und_event_instance', $future_instance);
					get_template_part('template-parts/block/und-tile', $future_instance->event->post->post_type);
					wp_reset_postdata();
				}
			}

			?>
		</section>

		// pagination ?

		<?php // do_action( 'foundationpress_after_content' ); ?>

	</div>
<?php
if(is_category()){
	$wp_query->used_categories[] = $wp_query->get_queried_object_id();
}
get_footer();
