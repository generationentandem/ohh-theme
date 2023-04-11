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

$nolimit = false;

// get the current taxonomy term
$acf_term = get_queried_object();


// vars
$und_event_landingpage_settings_on = get_field('und_event_landingpage_settings_on', $acf_term);

$und_event_landingpage_header_on = get_field('und_event_landingpage_header_on', $acf_term);
$und_event_landingpage_header_left = get_field('und_event_landingpage_header_left', $acf_term);
$und_event_landingpage_header_middle = get_field('und_event_landingpage_header_middle', $acf_term);
$und_event_landingpage_header_right = get_field('und_event_landingpage_header_right', $acf_term);

$und_event_landingpage_next_events_on = get_field('und_event_landingpage_next_events_on', $acf_term);
$und_event_landingpage_title_next_events = get_field('und_event_landingpage_title_next_events', $acf_term);
$und_event_landingpage_text_next_events = get_field('und_event_landingpage_text_next_events', $acf_term);

$und_event_landingpage_insights_on = get_field('und_event_landingpage_insights_on', $acf_term);
$und_event_landingpage_title_insights = get_field('und_event_landingpage_title_insights', $acf_term);
$und_event_landingpage_text_insights = get_field('und_event_landingpage_text_insights', $acf_term);

$und_event_landingpage_archive_on = get_field('und_event_landingpage_archive_on', $acf_term);
$und_event_landingpage_title_archive = get_field('und_event_landingpage_title_archive', $acf_term);
$und_event_landingpage_text_archive = get_field('und_event_landingpage_text_archive', $acf_term);



?>

<script type="application/javascript">
$("#columnTwo").height($("#columnOne").height());
</script>


<?php


setlocale(LC_TIME, 'de_DE', 'deu_deu');

define( 'TWOHOURS', 7200 );
get_header();
if (get_queried_object()->term_id != 10977){
	und_get_category_line(get_taxonomy_color($wp_query->get_queried_object_id()));


	if ( ! is_category( 'online' ) ): ?>
		<header class="archive-header" style=" border-top: 10px solid <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>; border-bottom: 10px solid <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;
		<?php echo get_field( 'bild', $wp_query->get_queried_object() ) ? 'background: url(' . wp_get_attachment_image_src( get_field( 'bild', $wp_query->get_queried_object() ), 'und-large' )[0] . ') center;' : '' ?>">
			<?php if( ! $und_event_landingpage_settings_on || ! $und_event_landingpage_header_on ): ?>
				<div class="archive-headercontent <?php echo 'bg-cat-' . $wp_query->get_queried_object_id() ?>" style="background-color: <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;">
					<h2><?php echo single_term_title(); ?></h2>
					<?php echo term_description() ?>
				</div>
			<?php else: ?>
				<div class="row small-up-1 medium-up-1 large-up-3">
					<style>.lp-child {padding: 20px;margin: .2em .2em;}@media only screen and (min-width: 768px) {.lp-container {width: 100%;display: flex;align-items: stretch;justify-content: space-around;}.lp-child {width: 100%;margin: 0 .2em;min-height: 100%;padding: 20px;}}</style>
					<div class="lp-container">
						<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id() ?> lp-child" style="background-color: <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;"> 
							<?php echo $und_event_landingpage_header_left; ?>
						</div>
						<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id() ?> lp-child" style=" background-color: <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;"> 
							<h2><?php echo single_term_title(); ?></h2>
							<?php echo $und_event_landingpage_header_middle; ?>
						</div>
						<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id() ?> lp-child" style="background-color: <?php echo get_taxonomy_color($wp_query->get_queried_object_id()); ?>;"> 
							<?php echo $und_event_landingpage_header_right; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</header>


	<?php
	und_get_category_line(get_taxonomy_color($wp_query->get_queried_object_id()));

	endif; 
}?>
	<div class="wrapper" style="margin-bottom: 2rem!important">
			<?php
			$events = und_get_events( array(
				'post_type'   => 'und_eventpost',
				'numberposts' => -1,
				'tax_query'   => array(
					array(
						'taxonomy'         => 'und_eventcat',
						'field'            => 'id',
						'terms'            => [get_queried_object_id()],
						'include_children' => true,
					),
				),
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

			/** Events sortieren **/

			$future_instances = bubblesort($future_instances, sizeof($future_instances), '>');
			$past_instances = bubblesort($past_instances, sizeof($past_instances), '<');


			function bubblesort($array, $length, $order){
				for ($i = ($length - 1); $i >= 0; $i--)
				{
					for ($j = 1; $j <= $i; $j++)
					{
						if($order == '>'){
							if ($array[$j-1]->start > $array[$j]->start)
							{
								$temp = $array[$j-1];
								$array[$j-1] = $array[$j];
								$array[$j] = $temp;
							}
						}
						else if($order == '<'){
							if ($array[$j-1]->start < $array[$j]->start)
							{
								$temp = $array[$j-1];
								$array[$j-1] = $array[$j];
								$array[$j] = $temp;
							}
						}

					}
				}
				return $array;
			}



			if ( ! $und_event_landingpage_settings_on || $und_event_landingpage_next_events_on ){
			if ($future_instances){
			?>
		<section class="tile-container" role="main">
			<h2 id="zu-kurse" class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;"><?php echo ( $und_event_landingpage_next_events_on && !empty($und_event_landingpage_title_next_events) ) ? $und_event_landingpage_title_next_events : "Nächste Events"; ?></h2>
			<div class="small-12" style="margin-left: .78947rem;margin-right: .78947rem;"><?php echo $und_event_landingpage_text_next_events; ?></div>
			<?php
			$i = 0;
			$limit = 12;
			foreach ($future_instances as $future_instance) {
				$post = $future_instance->event->post;
				setup_postdata($GLOBALS['post'] =& $post);
				set_query_var('und_event_instance', $future_instance);
				get_template_part('template-parts/block/und-tile', $future_instance->event->post->post_type);
				wp_reset_postdata();
				$i += 1;
				if ($i == $limit) {
					break;
				}
			}
			if ($i >= $limit) {
				?> <a id="collapse-future" class="button info hollow" style="margin: auto; text-decoration: none; border-color: #CD1719; color: #CD1719;" href="/events-archiv/">ganze Agenda anzeigen</a> <?php

			}
			}
			}

			?>

		</section>

		<?php
		$rueckblickePosts = get_posts( array(
			'numberposts' => 3,
			'category_name' => $term,
			'order' => 'DESC',
			'orderby' => 'date'
		));

		if ( ! $und_event_landingpage_settings_on || $und_event_landingpage_insights_on ) {
		if ($rueckblickePosts) {
			?>
			<section class="tile-container" role="main">
				<h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;"><a style="text-decoration:none; color:#0a0a0a;" href="/live/<?php if ($term !== 'live') echo $term; ?>"><?php echo ( $und_event_landingpage_insights_on && !empty($und_event_landingpage_title_insights) ) ? $und_event_landingpage_title_insights : "Einblicke" ?></a>

				<a id="collapse-past" class="button info hollow" href="/live/<?php if ($term !== 'live') echo $term; ?>" style="margin: auto; text-decoration: none; border-color: #CD1719; color: #CD1719;margin-left: 30px;">Alle Beiträge anzeigen</a></h2>
				<div class="small-12" style="margin-left: .78947rem;margin-right: .78947rem;"><?php echo $und_event_landingpage_text_insights; ?></div>

				<div class="wrapper">
					<main class="cards-container nogrow">
						<?php

						foreach ($rueckblickePosts as $post) {
							render_frontpage_block($post);
						}



						?>
					</main>
				</div>


			</section>

			<?php
		}
		}

		if (! $und_event_landingpage_settings_on || $und_event_landingpage_archive_on ) {
		if ($past_instances){
		?>
		<section class="tile-container" role="main">
			<h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;"><a style="text-decoration:none; color:#0a0a0a;" href="/events-archiv/#vergangene"><?php echo ( $und_event_landingpage_archive_on && !empty($und_event_landingpage_title_archive) ) ? $und_event_landingpage_title_archive : "Archiv" ?></a>
              
			<a id="collapse-past" class="button info hollow" href="/events-archiv/#vergangene" style="margin: auto; text-decoration: none; border-color: #CD1719; color: #CD1719;margin-left: 30px;">ganze Agenda anzeigen</a>
                        </h2>
						<div class="small-12" style="margin-left: .78947rem;margin-right: .78947rem;"><?php echo $und_event_landingpage_text_archive; ?></div>

			<?php
			$j = 0;
			$limit = 4;
			foreach ($past_instances as $future_instance) {
				$post = $future_instance->event->post;
				setup_postdata($GLOBALS['post'] =& $post);
				set_query_var('und_event_instance', $future_instance);
				get_template_part('template-parts/block/und-tile', $future_instance->event->post->post_type);
				wp_reset_postdata();
				$j += 1;
				if ($j == $limit) {
					break;
				}
			}
			/*if ($j >= $limit) {
				?> <a id="collapse-past" class="button info hollow" href="/events-archiv/#vergangene" style="margin: auto; text-decoration: none; border-color: #CD1719; color: #CD1719;">ganze Agenda anzeigen</a> <?php
			}*/
			}
		}
		?>


	</section>

	<?php

?>

		<?php do_action( 'foundationpress_after_content' ); ?>

	</div>
<?php
//if ( is_category() ) {
	$wp_query->used_categories[] = $wp_query->get_queried_object_id();
//}
get_footer();
?>



