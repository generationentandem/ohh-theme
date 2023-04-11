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
?>
<main>
	<div class="singular-container">
		<div id="generationenfestival-2019">
			<header class="generationenfestival-2019-header" role="banner">
				<h2>Rückblick</h2>
				<div class="logo" style="margin: auto;text-align: center;">
					<img style="max-width:100%;width:400px"
					     src="<?php echo get_template_directory_uri() ?>/public/images/festival-2019.svg">
				</div>
				<style>
					.button.hollow {
						color: #ff7c1a;
						border-color: #ff7c1a;
					}
				</style>

				<section class="highlights">
					<h2>6. &amp; 7. September&nbsp;2019</h2>
					<h3 style="opacity: 0.7;">Thun, Seefeld – Gymnasiumareal</h3>
					<?php
					$days = floor( ( 1567728000 - time() ) / 86400 );
					if ( $days > 0 ) {
						?>
						<h3 class="generationenfestival-2019-countdown" style="font-feature-settings: 'tnum'">Noch <?php
						echo $days;
						?> Tage bis zum Festival</h3><?php } ?>
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
			<nav class="festival-2019-insitenav f19_nav button-group expanded">
				<a class="button " href="#news">News</a>
				<a class="button " href="#overview">Übersicht</a>
				<a class="button " href="#timetable">Programm</a>
			</nav>

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
					} ?>


				</div>
			</section>
			<input type="hidden" name="currentSelection" value="everything">
			<section class="tile-container" id="overview">
				<h2 class="title-tile f19_heading">Rückblick</h2>
				<nav class="title-tile f19_map-tile">
					<div class="f19_map-buttons" style="flex-direction: row;flex:auto">
						<button data-target="everything" class="button f19_button-map f19_bg-other active"><span>0</span> Alles anzeigen</button>
						<button data-target="outside_stage" class="button f19_button-map f19_bg-outside_stage"><span>1</span> Aussenbühne</button>
						<button data-target="inside_stage" class="button f19_button-map f19_bg_inside_stage"><span>2</span> Innenbühne</button>
						<button data-target="food" class="button f19_button-map f19_bg-food"><span>3</span> Foodcorner</button>
						<button data-target="market" class="button f19_button-map f19_bg-market"><span>4</span> Markt</button>
						<button data-target="playarea" class="button f19_button-map f19_bg-playarea"><span>5</span> Spielbereich</button>
						<button data-target="pavillon" class="button f19_button-map f19_bg-pavillon"><span>6</span> Pavillon</button>
					</div>

				</nav>

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
				$fridaystart         = 1567728000;
				$fridayend           = 1567828800;
				$saturdaystart       = 1567814400;
				$saturdayend         = 1567915200;
				$saturdaymarketstart = 1567850400;
				$saturdaymarketend   = 1567879200;


				$events = und_get_events( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						array(
							'taxonomy'         => 'und_eventcat',
							'field'            => 'id',
							'terms'            => get_term_by( 'slug', 'programm-2019', 'und_eventcat' )->term_id,
							'include_children' => true
						),
						'orderby' => 'date',
						'order'   => 'DESC',
					)
				), true);

				$catind = array(
					'Aussenbühne'  => 'outside_stage',
					'Innenbühne'   => 'inside_stage',
					'Pavillon'     => 'pavillon',
					'Märit'        => 'market',
					'Foodcorner'   => 'food',
					''             => 'other',
					'Spielbereich' => 'playarea'
				);

				$posts_timed = array(
					'friday'   => array( 'programm' => [], 'food' => [] ),
					'saturday' => array( 'stage' => [], 'workshop' => [], 'market' => [], 'food' => [] ),
					'other'    => array()
				);

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

					$post = $event->post;
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
						'programm' => 'Programm',
						'food'     => 'Food <span class="text-muted">(bis Mitternacht)</span>',
					],
					'saturday' => [
						'stage'    => 'Bühne <span class="text-muted">(bis Mitternacht)</span>',
						'workshop' => 'Workshops <span class="text-muted">(bis 18 Uhr)</span>',
						'market'   => 'Märit <span class="text-muted">(bis 18 Uhr)</span>',
						'food'     => 'Food <span class="text-muted">(bis Mitternacht)</span>',
					]
				);

				?>
			</section>
			<?php
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

			foreach ( $posts_timed as $day => $items ) {
				foreach ( $items as $cat => $item ) {
					usort( $posts_timed[ $day ][ $cat ], 'sortUndEvents' );
				}
			}
			?>

			<section id="timetable" class="grid-x grid-padding-x">
				<h2 class="f19_heading cell">Programm</h2>
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
								} ?>Freitag, 6. Sept <span class="text-muted">(ab 18 Uhr)</span>
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
								} ?>Samstag, 7. Sept <span class="text-muted">(ab 10 Uhr)</span>
							</h3>
							<div class="grid-x">
								<?php
								foreach ( $posts_timed['saturday'] as $name => $cat ) {
									echo '<div class="cell medium-6"><div class="margin-1"><h4 class="f19_heading">' . $listcategories['saturday'][ $name ] . '</h4><ul class="festival-2019-timetable-list">';
									foreach ( $cat as $inst ) {
										echo '<li class="festival-2019-timetable-instance ' . $inst->event->category->slug . '">';
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
				<h2 class="f19_heading cell">Übersicht</h2>
				<div class="cell">
					<nav class="title-tile f19_map-tile">

						<svg class=" f19_map-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
						     viewBox="200 113.89 291.6 262.71" style="font-size:9.5px">
							<defs>
								<filter id="shadow" y="-25%" width="130%" height="150%">
									<feDropShadow dx="2" dy="2" stdDeviation="1" flood-opacity=".3"/>
								</filter>
								<symbol id="Arrow" viewBox="0 0 9.28 13.15" class="symbol">
									<path class="cls-3" d="M9.28 5.79L4.64 0 0 5.79h2.68v7.36h3.91V5.79h2.69z"/>
								</symbol>
								<symbol id="wc" viewBox="0 0 14.8 14.8" class="symbol">
									<rect width="14.8" height="14.8" rx="2.3"/>
									<text class="white wcfont" x="7.5" y="7.5">WC</text>
								</symbol>
								<symbol id="aid" viewBox="0 0 14.4 14.4" class="symbol">
									<path d="M14.4 12.2a2.3 2.3 0 0 1-2.2 2.3h-10A2.3 2.3 0 0 1 0 12.1v-10A2.3 2.3 0 0 1 2.3 0h9.9a2.3 2.3 0 0 1 2.3 2.3z"/>
									<path class="white" d="M3 2.9v8.4h8.5V2.9zm7.7 5H8.2v2.6H6.4V8H3.7V6.2h2.7V3.6h1.7v2.6h2.6z"/>
								</symbol>
								<symbol id="info" viewBox="0 0 14.8 14.8" class="symbol">
									<rect width="14.8" height="14.8" rx="2.3"/>
									<path class="white"
									      d="M5 2.7a3.6 3.6 0 0 1 2-.5 4.3 4.3 0 0 1 2.6.7 2.5 2.5 0 0 1 1 2.3 2.5 2.5 0 0 1-.5 1.5 4.7 4.7 0 0 1-1 1l-.4.3a1.6 1.6 0 0 0-.5.7 3.7 3.7 0 0 0-.1.9H6A5.5 5.5 0 0 1 6.5 8a3 3 0 0 1 1-1l.5-.4a1.7 1.7 0 0 0 .4-.4 1.3 1.3 0 0 0 .2-.8 1.6 1.6 0 0 0-.3-1 1.3 1.3 0 0 0-1.1-.5 1.2 1.2 0 0 0-1.1.5 2 2 0 0 0-.3 1.1h-2A3.2 3.2 0 0 1 5 2.7zm1.1 7.8h2.1v2h-2z"/>
								</symbol>
								<symbol id="rad" viewBox="0 0 14.8 14.8" class="symbol">
									<rect width="14.8" height="14.8" rx="2.3"/>
									<path class="white"
									      d="M10.5 6.5L10 5a1.3 1.3 0 0 0 1.3-1.1h-.8a.4.4 0 0 1-.3.4H9v.5l.3 1H6V5h.7v-.8H4.6V5h.8v1.2L3.6 9.4h4.2l1.8-3 1 2.7.7-.2-.6-1.7a1.8 1.8 0 0 1 2 1.8A1.9 1.9 0 0 1 9 9l-.6.7a2.6 2.6 0 0 0 2.5 1.9A2.6 2.6 0 0 0 13.5 9a2.5 2.5 0 0 0-3-2.5zM5 8.7l1-1.9 1 1.9zm2.7-.4L6.5 6.5h2.3zm-1.9 1.8h.9a2.6 2.6 0 1 1-2.3-3.6l-.3.7a1.9 1.9 0 0 0 .3 3.7 1.8 1.8 0 0 0 1.4-.8z"/>
								</symbol>
								<symbol id="boundry">
									<path style="fill:currentColor;stroke:#607d8b;stroke-dasharray:2;stroke-width:.75px"
									      d="M289 306.6l14.4.4 41.4 25.5 48.8 2.2 22.8 11.5 50.4 3 5.4-166.8-7-.1-14-10.2-16.8-.8.3-7.4-3.6-.3.7-12.4-36.4-.2-1.2 31.5-11-.6-2 48.1-73.2-3-2 8.1 6.2 8.8-8.4 36.2-8.4-1.5-6.5 28z"/>
								</symbol>
							</defs>
							<g class="map">
								<g>
									<g class="streets">
										<path d="M343 34.5l-18 86-60 250-23 126"></path>
										<path d="M519 100L539 123l-212-2.5L198 63m375 292l0 17-306.5-2"></path>
										<path d="M435 374l1.78-45.74"></path>
										<path d="M469 301l94.2 2.07"></path>
										<path d="M411 123l-.65 30.4"></path>
									</g>
									<use width="9.28" height="13.15" transform="translate(430.5 350.87)" xlink:href="#Arrow"/>
									<path style="fill:white;stroke:#607d8b;stroke-dasharray:2;stroke-width:.75px"
									      d="M289 306.6l14.4.4 41.4 25.5 48.8 2.2 22.8 11.5 50.4 3 5.4-166.8-7-.1-14-10.2-16.8-.8.3-7.4-3.6-.3.7-12.4-36.4-.2-1.2 31.5-11-.6-2 48.1-73.2-3-2 8.1 6.2 8.8-8.4 36.2-8.4-1.5-6.5 28z"/>
									<g class="text-muted">
										<text class="text-normal text-muted" transform="rotate(-.1 213939.2 -206261.56)"
										      style="font-size:9.5px">Eingang Äussere Ringstrasse
										</text>
										<text class="cls-33 text-normal text-muted" transform="rotate(-76.31 339.47 -24.83)">Blümlisalpstrasse
										</text>
										<text class="cls-36 text-normal text-muted" transform="translate(364.27 124.91)">Eingang Mittlere Ringstrasse
										</text>
									</g>
									<use width="9.28" height="13.15" transform="rotate(180 208 73.23)" xlink:href="#Arrow"/>
								</g>
								<path class="house" d="M431 169l0 8,-18-1,-.25 4,-13-1,.5-22.5,11 .5,a4 4 0 0 1 7 .3l8 .5-.5 11z"/>
								<g class="path f19_bg-outside_stage" data-target="outside_stage">
									<path class="path" d="M375.19 333.87l-30.38-1.32.55-17.08 30.77 1.44-.94 16.96z"/>
									<text class="white" transform="translate(356.39 328.67)">1</text>
								</g>
								<g class="path f19_bg-market" data-target="market">
									<path d="M437.1 184.5l-9.6-.5-5.35 110-28-1-.4 14 24.5 1-1.5 27 12.5 1 3-55.7.5-9.2 4.5-86z"/>
									<text transform="rotate(2.08 -8237.53 11732.17)"
									      style="font-size:13.24px;font-family:SourceSansPro-Black,Source Sans Pro,sans-serif;font-weight:800;fill:#fff">4
									</text>
								</g>
								<path class="house" d="M459 211l4 0,-1 16,-27 -1,1 -23,1 -18,17 1,-1 18,3 0a4 4 0 1 1 4 4zM429 336l2,-56 25,1-3,56z"/>
								<g class="path f19_bg-playarea" data-target="playarea">
									<path class="cls-23" d="M383 182l-4 87 17 1 4-87-17-1z"/>
									<path class="cls-23" d="M426 184l-2 44-28-1 2-44 6 0c1 2 4 4 7 4 3 0 6-1 7-3z"/>
								</g>
								<text class="text-black" transform="rotate(3.16 -3614.03 7220.3)">5</text>
								<g class="path f19_bg-food" data-target="food">
									<path d="M380.05 268.82l-33.34-1.11 1.22-33.95 33.18 1.07-1.06 33.99z"/>
									<text class="white" transform="translate(361 255.42)">3</text>
								</g>
								<path class="house" d="M394 293l3-61 28 1-3 61z"/>
								<text class="text-normal text-muted" transform="rotate(-87.7 357.34 -69.36)" style="font-size:8.6px;fill:#fff">Neue Turnhalle</text>
								<text class="cls-55 text-normal text-muted" transform="translate(344.54 303.65)">Roter Platz</text>
								<text class="text-normal text-muted" transform="rotate(2.09 -4312.72 11226.68)" style="font-size:7.37px;fill:#fff">Villa
									<tspan class="cls-59" x="0" y="6.81">Lüthi</tspan>
								</text>
								<path d="M383 142h9v26h-9zm-40 53h29v26h-29zm-4-56h21v26h-21z"
								      style="fill:#bcbec0"/>
								<use xlink:href="#boundry" height="400.8" color="transparent" pointer-events="none"/>
								<g class="pavillon">
									<g class="path f19_bg-pavillon" data-target="pavillon">
										<path class="shadow cls-78" d="M391.98 345.03l1.83-38.1 24.42 1.17-1.83 38.1z"/>
										<text class="text-black" transform="translate(397.17 329.58)">6</text>
									</g>
									<text class="text-normal text-muted" transform="rotate(-87.25 387.15 -45.31)" style="font-size:9.5px">
										Pavillon
									</text>
								</g>
								<path class="house" d="M466 151l-1 31 -7-0-5,-.3.2,-5-12,-.1.3,-15 14,-.5 .25,-10.5z"/>
								<g class="aulabuilding">
									<path class="house" d="M471.5 240.7l-1 22.8-8.5-.41h0l-12.34-.6-.44 9-16.65-1 1.32-27.47 4 .1.2-4 .2-5 8 .4.2-4 17 .8-.4 8.7 8.5.5z"/>
									<text transform="rotate(1.19 -11622.48 21445.41)" style="fill:#fff">
										<tspan class="cls-77 text-normal text-muted">Aula</tspan>
									</text>
									<g class="path inside-stage f19_bg_inside_stage" data-target="inside_stage">
										<path class="cls-75 path" d="M432.53 270.76l16.65.8.44-9.06 10.04-.38.36-11.66-26.36-1.08-1.13 21.38z"/>
										<text class="white" transform="translate(438.47 263.01)">2</text>
									</g>
								</g>
								<g class="alteturnhalle">
									<path class="house" d="M340 293l0 15, 6 0, -1 24, -22-1, -19-1, 1-24, 19 1, 2-75, 23 .5,-2 60.5z"/>
									<text class="text-normal text-muted" transform="rotate(-87.85 318.17 -30.94)" style="font-size:8.6px;fill:#fff">Alte Turnhalle</text>
								</g>
								<g>
									<use width="15" x="322" y="170" xlink:href="#wc"/>
									<use width="15" x="387" y="211" xlink:href="#wc"/>
									<use width="15" x="461" y="129" xlink:href="#wc"/>
									<use width="15" x="390" y="160" xlink:href="#aid"/>
									<use width="15" x="435" y="149" xlink:href="#info"/>
									<use width="15" x="443" y="215" xlink:href="#rad"/>
									<use width="15" x="301" y="160" xlink:href="#rad"/>
									<use width="15" x="380" y="34" xlink:href="#rad"/>
								</g>
								<g class="symbol_legend" transform="translate(210 125)">
									<svg y="60">
										<use height="15" width="15" xlink:href="#aid"></use>
										<text x="18" y="12">&nbsp;1. Hilfe</text>
									</svg>
									<svg y="40">
										<use height="15" width="15" xlink:href="#wc"></use>
										<text x="18" y="12">&nbsp;Toilette</text>
									</svg>
									<svg y="20">
										<use height="15" width="15" xlink:href="#info"></use>
										<text x="18" y="12">&nbsp;Infostand</text>
									</svg>
									<svg y="0">
										<use height="15" width="15" xlink:href="#rad"></use>
										<text x="18" y="12">&nbsp;Veloparkplatz</text>
									</svg>
								</g>
						</svg>
					</nav>
				</div>
			</section>
		</div>
	</div>
</main>
<?php get_footer(); ?>
