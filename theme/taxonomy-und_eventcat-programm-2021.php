<?php
/**
 * The template for displaying events
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

wp_enqueue_style( 'page-festival-2019', get_stylesheet_directory_uri() . '/public/' . foundationpress_asset_path( 'page-festival-2019.css' ), array(), false, 'all' );
wp_enqueue_style( 'generationenfestival_font', 'https://use.typekit.net/hob1yvh.css' );
get_header();
und_get_category_line( '#ffad29' );

			/**
			 * @param $a Und_Event_Instance
			 * @param $b Und_Event_Instance
			 *
			 * @return int
			 */
			function sortUndEvents( $a, $b ) {
				if ( $a->start() == $b->start() ) {
					if ( isset( $a->end ) && isset( $b->end ) ) {
						return $a->end() > $b->end() ? - 1 : 1;
					} else {
						return 0;
					}
				} else {
					return $a->start() < $b->start() ? - 1 : 1;
				}
			}
?>
<main>
	<div class="singular-container">
		<div id="generationenfestival-2019">
			<header class="generationenfestival-2019-header" role="banner">
				<div class="logo" style="margin: auto;text-align: center;">
					<img style="max-width:100%;width:320px"
					     src="<?php echo get_template_directory_uri() ?>/public/images/festival-2019.svg">
				</div>
				<style>
					.button.hollow {
						color: #ff7c1a;
						border-color: #ff7c1a;
					}
				</style>

				<section class="highlights">
					<h2>📅&nbsp;27. – 28. August&nbsp;2021</h2>
					<h3 style="opacity: 0.7;">📍&nbsp;Thun, Seefeld – Gymnasiumareal</h3>
					<?php
					/*$days = floor( ( (new DateTime("2020-09-04T00:00:00"))->getTimestamp() - time() ) / 86400 );
					if ( $days > 0 ) {
						*/?><!--
						<h3 class="generationenfestival-2019-countdown" style="font-feature-settings: 'tnum'">Noch <?php
/*						echo $days;
						*/?> Tage bis zum Festival</h3>--><?php /*} */?>
					<br>
				</section>
			</header>
			<style>.f19_nav {
					background: #ff7c19;
					justify-content: center;
				}

				.f19_nav .button {
					color: #ffffff;
					border: none;
					background: transparent;
					flex: auto 0 0 !important;
				}</style>
			<!-- <nav class="festival-2019-insitenav f19_nav button-group expanded">
				<a class="button " href="#news">News</a>
				<a class="button " href="#overview">Übersicht</a>
				<a class="button " href="#timetable">Programm</a>
			</nav> -->

			<div class="post-wrapper generationenfestival-2019-description">
				<p>
					<?php
					echo term_description();
					?>
				</p>
			</div>
		</div>
		<div class="wrapper festival-2019-main">
			<section id="news">
				<div class="cards-container nogrow">
					<h2 class="title-tile f19_heading">News</h2>

					<?php
					$news = get_posts( array( 'category' => 2508, 'numberposts' => 3 ) );
					foreach ( $news as $post ) {
						$post->overview_category = get_category( 2508 );
						setup_postdata( $GLOBALS['post'] =& $post );
						get_template_part( 'template-parts/block/und-card', 'post' );
						wp_reset_postdata();
					}
					?>
				<a id="collapse-future" class="button info hollow" style="margin: auto; text-decoration: none; border-color: #CD1719; color: #CD1719;" href="/generationenfestival/news/">Alle Beiträge anzeigen</a>


				</div>
			</section>
			<input type="hidden" name="currentSelection" value="everything">
			<section class="tile-container" id="overview">
				<h2 class="title-tile f19_heading">Programm Generationenfestival 2021</h2>
				<div class="columns medium-9">
	  <br />
					<ul>
						<li>
							<strong>Wann:</strong> Freitag, 27. August 2021 17-01 Uhr und Samstag, 28. August 10-02 Uhr
						</li>
						<li>
							<strong>Wo:</strong> Gymnasium Thun, Standort Seefeld
						</li>
						<li>
							<strong>Eingänge:</strong> Mittlere Ringstrasse 8 oder Äussere Ringstrasse 7.
						</li>
					</ul>
				</div>
  <div class="columns medium-2">
  <div style="margin: auto;text-align: center;">

				<a href="https://www.generationentandem.ch/generationenfestival/corona/"><img src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2021/08/13093440/Covid_Button_Zertifikat.png" width="160px" alt="Covid-Zertikat Label" /></a>
				</div>
			</div>
				<nav class="title-tile f19_map-tile">
					<div class="f19_map-buttons" style="flex-direction: row;flex:auto">
						<button data-target="everything" class="button f19_button-map f19_bg-other active">Alles anzeigen</button>
						<button data-target="stage" class="button f19_button-map f19_bg-outside_stage">Bühne</button>
						<button data-target="market" class="button f19_button-map f19_bg-playarea">Märit</button>
						<button data-target="workshop" class="button f19_button-map f19_bg-pavillon">Workshop</button>
						<button data-target="food" class="button f19_button-map f19_bg_inside_stage">Foodcorner</button>
					</div>
				</nav>
				<!-- <nav class="title-tile f19_map-tile">
					<div class="f19_map-buttons" style="flex-direction: row;flex:auto">
						<button data-target="everything" class="button f19_button-map f19_bg-other active"><span>0</span> Alles anzeigen</button>
						<button data-target="outside_stage" class="button f19_button-map f19_bg-outside_stage"><span>1</span> Aussenbühne</button>
						<button data-target="inside_stage" class="button f19_button-map f19_bg_inside_stage"><span>2</span> Innenbühne</button>
						<button data-target="food" class="button f19_button-map f19_bg-food"><span>3</span> Foodcorner</button>
						<button data-target="market" class="button f19_button-map f19_bg-market"><span>4</span> Markt</button>
						<button data-target="playarea" class="button f19_button-map f19_bg-playarea"><span>5</span> Spielbereich</button>
						<button data-target="pavillon" class="button f19_button-map f19_bg-pavillon"><span>6</span> Pavillon</button>
						<button data-target="gym" class="button f19_button-map f19_bg-turnhalle"><span>7</span> Alte Turnhalle</button>
						<button data-target="stage" class="button f19_button-map f19_bg-stage"><span>8</span> Bühne</button>
					</div>
				</nav> -->

				<script>
					document.addEventListener('DOMContentLoaded', function () {

						document.querySelectorAll('[class*="f19_bg"]').forEach(function (elem) {
							elem.addEventListener('click', function (event) {
								setSel(event.currentTarget, elem.getAttribute('data-target'))
							})
						});

						function setSel(elem, string) {
							document.querySelectorAll('[class*="f19_bg"]').forEach(function (e) {
								if (e.getAttribute('data-target') === elem.getAttribute('data-target')) {
									e.classList.add('active');
								} else {
									e.classList.remove('active');
								}
							});
							document.querySelector('input[name="currentSelection"]').value = string;
						}
					})
				</script>
				<?php
				$fridaystart         = 1630022400;
				$fridayend           = 1630123200;
				$saturdaystart       = 1630108800;
				$saturdayend         = 1630209600;
				$saturdaymarketstart = 1630058400;
				$saturdaymarketend   = 1630087200;


				$events = und_get_events( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						array(
							'taxonomy'         => 'und_eventcat',
							'field'            => 'id',
							'terms'            => get_term_by( 'slug', 'programm-2021', 'und_eventcat' )->term_id,
							'include_children' => true
						),
						'orderby' => 'date',
						'order'   => 'ASC',
					)
				), true);

				$catind = array(
					'Aussenbühne'  => 'outside_stage',
					'Innenbühne'   => 'inside_stage',
					'Pavillon'     => 'pavillon',
					'Märit'        => 'market',
					'Foodcorner'   => 'food',
					'Alte Turnhalle' => 'gym',
					''             => 'other',
					'Spielbereich' => 'playarea'
				);

				$posts_timed = array(
					'friday'   => array( 'programm' => [], 'food' => [] , 'maerit' => [] ),
					'saturday' => array( 'stage' => [], 'market' => [], 'workshop' => [], 'food' => [], 'gym' => [] ),
					'other'    => array()
				);


			// foreach ( $posts_timed as $day => $items ) {
			// 	foreach ( $items as $cat => $item ) {
			// 		usort( $posts_timed[ $day ][ $cat ], 'sortUndEvents' );
			// 	}
			// }

				foreach ( $events as &$event ) {
					foreach ( $event->instances as &$instance ) {
						if ( $fridaystart <= $instance->start->getTimestamp() && $instance->start->getTimestamp() <= $fridayend ) {
							$instance->allday                                                                  = $event->category->slug == 'food';
							$posts_timed['friday'][ $event->category->slug == 'food' ? 'food' : 'programm' ][] = $instance;
						} elseif ( $saturdaystart <= $instance->start->getTimestamp() && $instance->start->getTimestamp() <= $saturdayend ) {
							$instance->allday                         = $event->category->slug == 'food' || ( $event->category->slug == 'market' || $event->category->slug == 'workshop' ) && $saturdaymarketstart == $instance->start->getTimestamp() && $instance->end->getTimestamp() == $saturdaymarketend;
							$posts_timed['saturday'][ $event->category->slug ][] = $instance;
						} else {
							$posts_timed['other'][] = $instance;
						}
					}
					//$d = new DateTime($event->post->und_event->instances[0]->start->date);
					//print_r($event->post->und_event->instances[0]->start->getTimestamp());

					$post = $event->post;
					// foreach ( $post as $day => $items ) {
					// 	foreach ( $items as $cat => $item ) {
					// 		print_r($item);

					// 		//usort( $post[ $day ][ $cat ], 'sortUndEvents' );
					// 	}
					// }
					setup_postdata( $GLOBALS['post'] =& $post );
					get_template_part( 'template-parts/block/und-tile', $event->post->post_type . '-festival' );
					wp_reset_postdata();
				}
				echo '';
				function formatTimetableInstance( $time ) {
					return $time->start->format( 'H:i' ) . '</span> - <span>' . $time->end->format( 'H:i' );
				}

				$listcategories = array(
					'friday'   => [
						'programm' => 'Bühne&nbsp;&nbsp;<span class="text-muted">(17-01 Uhr)</span>',
						'food'     => 'Food&nbsp;&nbsp;<span class="text-muted">(17 Uhr)</span>',
						'maerit'     => 'Märit&nbsp;&nbsp;<span class="text-muted">(17-22 Uhr)</span>',
					],
					'saturday' => [
						'stage'    => 'Bühne&nbsp;&nbsp;<span class="text-muted">(10-02 Uhr)</span><p><small>Aussenbühne (rot)<br/>Innenbühne (orange)<br/>Pavillon (gelb)<br/>Alte Turnhalle (grün)</small></p>',
						'market'   => 'Märit&nbsp;&nbsp;<span class="text-muted">(17-22 Uhr)</span>',
						'workshop' => 'Workshops&nbsp;&nbsp;<span class="text-muted">(10-18 Uhr)</span>',
						'food'     => 'Food&nbsp;&nbsp;<span class="text-muted">(10-02 Uhr)</span>',

					]
				);

				?>
			</section>
			<?php

			foreach ( $posts_timed as $day => $items ) {
				foreach ( $items as $cat => $item ) {
					usort( $posts_timed[ $day ][ $cat ], 'sortUndEvents' );
				}
			}
			?>

			<section id="timetable" class="grid-x grid-padding-x">
				<h2 class="f19_heading cell">Programmrückblick</h2>
				<div class="cell">
					<div class="f19_timetable  grid-x">
						<section class="cell f19_timetable-box">
							<h3 class="f19_heading f19_timetable-heading">
								<?php
								if ( time() > $fridaystart ) {
									if ( time() > $fridayend ) {
										if ( time() <= $saturdayend ) {
											echo 'Gestern: ';
										}
									} else {
										echo 'Heute: ';
									}
								} ?>Freitag, 27. August&nbsp;&nbsp;<span class="text-muted">(ab 17-01 Uhr)</span>
							</h3>
							<div class="grid-x">
								<?php
								foreach ( $posts_timed['friday'] as $name => $cat ) {
									echo '<div class="cell medium-6"><div class="margin-1"><h4 class="f19_heading">' . $listcategories['friday'][ $name ] . '</h4><ul class="festival-2019-timetable-list">';
									foreach ( $cat as $inst ) {
										echo '<li class="festival-2019-timetable-instance ' . $catind[$inst->event->location] . '">';
										echo '<a href="' . get_post_permalink( $inst->event->post->ID ) . '"><h4 class="h5">';
										echo '<span title="' . $inst->event->location . '" class="festival-2019__category-indicator">●</span>';
										echo $inst->event->post->post_title;
										echo '</h4>';
										echo $inst->allday ? '' : '<time class="festival-2019-timetable-time"><span>' . formatTimetableInstance( $inst ) . '</span></time>';
										echo '</a></li>';
									}
									echo '</ul></div></div>';
								}
								?>
							</div>
						</section>
						<section class="cell ">
							<h3 class="f19_heading f19_timetable-heading">
								<?php
								if ( time() > $fridaystart ) {
									if ( time() > $fridayend ) {
										if ( time() <= $saturdayend ) {
											echo 'Heute: ';
										}
									} else {
										echo 'Morgen: ';
									}
								} ?>Samstag, 28. August&nbsp;&nbsp;<span class="text-muted">(ab 10-02 Uhr)</span>
							</h3>
							<div class="grid-x">
								<?php
								foreach ( $posts_timed['saturday'] as $name => $cat ) {
									echo '<div class="cell medium-5"><div class="margin-1"><h4 class="f19_heading">' . $listcategories['saturday'][ $name ] . '</h4><ul class="festival-2019-timetable-list">';
									foreach ( $cat as $inst ) {
										echo '<li class="festival-2019-timetable-instance ' . $catind[$inst->event->location] . '">';
										echo '<a href="' . get_post_permalink( $inst->event->post->ID ) . '"><h4 class="h5">';
										echo '<span title="' . $inst->event->location . '" class="festival-2019__category-indicator">●</span>';
										echo $inst->event->post->post_title;
										echo '</h4>';
										echo $inst->allday ? '' : '<time class="festival-2019-timetable-time"><span>' . ( $name == 'stage' ?  $inst->start->format( 'H:i' ) : formatTimetableInstance( $inst ) ) . '</span></time>';
										echo '</a></li>';
									}
									echo '</ul></div></div>';
								}
								?>
							</div>
						</section>
					</div>
				</div>
			</section>

			<section id="timetable" class="grid-x grid-padding-x">
				<h2 class="f19_heading cell">Arealplan</h2>
				<div class="cell">
				<nav class="title-tile f19_map-tile">
				<img src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2021/07/29133903/arealplan2021.jpg"/>
				</nav>

				</div>
			</section>
		</div>
	</div>
</main>
<?php get_footer(); ?>
