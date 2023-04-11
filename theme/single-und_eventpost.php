<?php
/**
 * The template for displaying events
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

error_reporting(0);
setlocale(LC_TIME, 'de_DE.UTF-8', 'deu_deu');


function print_time_correct($time, $suffix = '') {
    if (str_ends_with($time->format( 'H:i' ), '00')) {
        return $time->format( 'H' ) . $suffix;
    } else {
        return $time->format( 'H.i' ) . $suffix;
    }
}

function eventpostTimespan( $start, $end ) {
    $endNotGiven = false;
    if ( ! is_numeric( $end ) ) {
        $end         = $start;
        $endNotGiven = true;
    }
    $start     = new DateTime( "@{$start}" );
    $end       = new DateTime( "@{$end}" );

    // is without end
    if ( $endNotGiven ) {
        return strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . print_time_correct($start, ' Uhr');
    } else {
        // start and end on same day
        if ( $start->format( 'Ymd' ) == $end->format( 'Ymd' ) ) {
            return '<span class="date">' . strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . '</span><span class="time">' . print_time_correct($start) . '–' . print_time_correct($end, ' Uhr') . '</span>';
        } else {
            return '<span class="date">' . strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . print_time_correct($start, ' Uhr') . ' – ' . strftime('%A, %e. %B %Y, ', $end->getTimestamp()) . print_time_correct($end, ' Uhr');
        }
    }
}

get_header();
$currentTerm = yoast_get_primary_term_id( 'und_eventcat' );
if(term_is_ancestor_of( get_term_by( 'slug', 'generationenfestival', 'und_eventcat' ), yoast_get_primary_term_id( 'und_eventcat' ), 'und_eventcat' )):
    $topterm = 'festival';
else:
    $topterm = 'live';
endif;

und_get_category_line(get_taxonomy_color($wp_query->get_queried_object_id()));

?>
    <main>
        <div class="singular-container">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <style>
                    .button.hollow {
                        color: #ff7c1a;
                        border-color: #ff7c1a;
                    }
                </style>
                <?php if ( has_post_thumbnail( $post->ID ) ) : ?>
                    <div class="featured-hero eventpost-header" role="banner"
                         data-interchange="[<?php the_post_thumbnail_url( 'und-small' ); ?>, small], [<?php the_post_thumbnail_url( 'und-medium' ); ?>, medium], [<?php the_post_thumbnail_url( 'und-large' ); ?>, large], [<?php the_post_thumbnail_url( 'und-xlarge' ); ?>, xlarge]">
                    </div>
                    <?php
                    wp_add_inline_script( 'foundation', 'new Foundation.Interchange($(".featured-hero"))', 'after' );
                endif;
                und_get_category_line(get_taxonomy_color($wp_query->get_queried_object_id()));
                ?>


                <section class="entry-header">
                    <div class="post-wrapper">
                        <?php if('live'== $topterm ): ?>
                            <a href="<?php echo get_term_link( yoast_get_primary_term_id('und_eventcat'), 'und_eventcat' ) ?>#overview"
                               class="button info hollow">« Zur Übersicht
                            </a>
                        <?php else: ?>
                            <a href="/generationenfestival/programm/events/"
                               class="button info hollow">« Zur Übersicht
                            </a>
                        <?php endif; ?>
                        <header>
                            <?php
                            $term_id = yoast_get_primary_term_id('und_eventcat');
                            $term = get_term($term_id);
                            ?>
                            <h1 class="entry-title und_eventpost-title alignwide">
                                <?php the_title(); ?>
                            </h1>
                        </header>
                        <?php the_lead() ?>

                        <aside class="entry-aside">
                            <div class="small-12 social-box button-group expanded post-wrapper" style="padding: 0">
                                <a href="/about/netzwerk/spenden/"
                                   class="button social-boxbutton bg-donate" target="_blank"
                                   title="UND Generationentandem unterstützen">
                                    <i class="social-icon fa fa-gift"></i>
                                    &nbsp;
                                    <span>Kollekte</span>
                                </a>
                                <a target="_blank"
                                   href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ) . '&t=' . rawurlencode( get_the_title() ) ?>"
                                   class="button social-boxbutton bg-facebook" title="Auf Facebook teilen"><i
                                        class="social-icon fa fa-facebook"></i>&nbsp;<span>Facebook</span></a>
                                <a target="_blank"
                                   href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( get_the_title() ) . rawurlencode( "\n" ) . rawurlencode( get_the_permalink() ) ?>"
                                   class="button social-boxbutton bg-twitter"
                                   title="Auf Twitter teilen"><i
                                        class="social-icon fa fa-twitter"></i>&nbsp;<span>Twitter</span></a>
                                <a target="_blank"
                                   href="https://api.whatsapp.com/send?text=<?php echo rawurlencode( get_the_title() ) . rawurlencode( "\n" ) . rawurlencode( get_the_permalink() ) ?>"
                                   class="button social-boxbutton bg-whatsapp cell"
                                   title="Auf WhatsApp teilen"><i
                                        class="social-icon fa fa-whatsapp"></i>&nbsp;<span>WhatsApp</span></a>
                                <a target="_blank"
                                   href="mailto:?subject=<?php echo rawurlencode( 'Beitrag: "' . get_the_title() . '" auf UND Generationentandem' ) ?>&body=<?php echo rawurlencode( 'Schaue dir den Beitrag an: ' . get_the_title() . "\n" . get_the_lead( '', '', false ) . "\n" . get_the_permalink() ) ?>"
                                   class="button social-boxbutton bg-email"
                                   title="Per E-Mail teilen"><i
                                        class="social-icon fa fa-envelope"></i>&nbsp;<span>E-Mail</span></a>
                            </div>
                        </aside>

                        <div class="und_eventpost-meta">
                            <div class="und_eventpost-metablock und_eventpost-meta_timetable" style="margin: 0">
                                <p class="lead event-info-box position-relative text-align-left">
                                    <?php
                                    if (get_field('und_event_timetable')['0']['und_event_timetable_instanceend'] != null) {
                                        $startDate = date('Y-m-d', get_field('und_event_timetable')['0']['und_event_timetable_instancestart']);
                                        $startTime = date('H:i', get_field('und_event_timetable')['0']['und_event_timetable_instancestart']);
                                        $endDate = date('Y-m-d', get_field('und_event_timetable')['0']['und_event_timetable_instanceend']);
                                        $endTime = date('H:i', get_field('und_event_timetable')['0']['und_event_timetable_instanceend']);
                                    } else {
                                        $startDate = date('Y-m-d', get_field('und_event_timetable')['0']['und_event_timetable_instancestart']);
                                        $startTime = date('H:i', get_field('und_event_timetable')['0']['und_event_timetable_instancestart']);
                                        $endDate = $startDate;
                                        $endTime = date('H:i', get_field('und_event_timetable')['0']['und_event_timetable_instancestart'] + 5400);
                                    }
                                    ?>

                                    <button id="calendarButton" class="button-click position-absolute"></button>

                                    <i class="fa fa-calendar und-event-text padding-right-1 padding-left-1" aria-hidden="true"></i>

                                    <?php
                                    while ( have_rows( 'und_event_timetable' ) ) : the_row();
                                        // Your loop code
                                        echo '<time class="und_event_instance event-info-text-underline">' . eventpostTimespan( get_sub_field( 'und_event_timetable_instancestart' ), get_sub_field( 'und_event_timetable_instanceend' ) ) . '</time><br>';

                                    endwhile;
                                    ?>
                                </p>
                                <script type="application/javascript">
                                    const config = {
                                        name: "<?php the_title() ?>",
                                        startDate: "<?php echo $startDate ?>",
                                        startTime: "<?php echo $startTime ?>",
                                        endDate: "<?php echo $endDate ?>",
                                        endTime: "<?php echo $endTime ?>",
                                        timeZone: "Europe/Zurich",
                                        location: "<?php the_field( 'und_event_place' ) ?>",
                                        trigger: "click",
                                        hideCheckmark: true,
                                        language: "de",
                                        options: ["Apple","Google","Outlook.com","Yahoo","Microsoft365","iCal"],
                                    };
                                    const button = document.getElementById('calendarButton');
                                    if (button) {
                                        button.addEventListener('click', () => document.atcb_action(config, button))
                                    }
                                </script>
                            </div>
                            <div style="flex-basis: 100%; height: 3px;"></div>
                            <div class="und_eventpost-metablock und_eventpost-meta_place" style="margin: 0">
                                <p class="lead event-info-box position-relative text-align-left">
                                    <i class="fa fa-map und-event-text padding-right-1 padding-left-1" aria-hidden="true"></i>
                                    <?php
                                    $mapsLocation = get_field('und_event_maps_location');
                                    $mapsLocation = str_replace(' ', '+', $mapsLocation);

                                    if ($mapsLocation != null) {
                                        $link = "https://www.google.com/maps/place/$mapsLocation";
                                        echo "<a class='button-click position-absolute' target='blank' href='$link'></a>";
                                        echo "<span class='event-info-text-underline'>".get_field('und_event_place')."</span>";
                                    } else {
                                        the_field('und_event_place');
                                    }
                                    ?>
                                </p>
                            </div>
                            <div style="flex-basis: 100%; height: 1rem;"></div>
                            <br />
                            <div class="und_eventpost-metablock und_eventpost-meta_desc" style="margin: 0">
                                <!--<h2 class="h3">Was?</h2>-->
                                <div class="entry-content">
                                    <div class="post-wrapper" style="padding: 0px">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </main>

    <div class="post-wrapper" style="margin: auto">
        <footer class="entry-footer">
            <aside class="content-footer-box">
                <?php und_donate_small( ); ?>
            </aside>

            <?php und_the_tags( '<div class="content-footer-box entry-tags"><h4>Themen</h4><div class="round-tags-container">', '</div></div>' ); ?>
            <div class="content-footer-box">
                <div class="grid-x grid-padding-x footerbox">
                    <h4 class="small-12">Mehr UND in deiner Mailbox. Erhalte einmal pro Monat die wichtigsten
                        Neuigkeiten per E-Mail.</h4>
                    <form
                        action="https://generationentandem.us10.list-manage.com/subscribe/post?u=586196077b6ca51c50a7720d5&amp;id=062f9cdf0c"
                        method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                        class="small-12 validate" target="_blank" novalidate>
                        <div class="align-bottom grid-x grid-margin-x">
                            <div class="small-12 medium-5 cell">
                                <label>Name
                                    <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
                                </label>
                            </div>
                            <div class="small-12 medium-4 cell">
                                <label>Email <span class="asterisk">*</span>
                                    <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                                </label>
                            </div>
                            <div class="cell auto flex_align_items_end">
                                <button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"
                                        class="und-share button expanded">
                                    <i class="fa fa-paper-plane"></i>&nbsp;Abonnieren
                                </button>
                            </div>
                        </div>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text"
                                   name="b_586196077b6ca51c50a7720d5_062f9cdf0c"
                                   tabindex="-1" value="">
                        </div>
                        <div id="mce-responses">
                            <div class="callout alert" id="mce-error-response" style="display:none"></div>
                            <div class="callout success" id="mce-success-response" style="display:none"></div>
                        </div>
                    </form>
                    <script type='text/javascript'
                            src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                </div>
                <!--End mc_embed_signup-->
            </div>

            <div class="content-footer-box">
                <?php if('live'== $topterm ):?>
                    <h4>Das könnte dich auch interessieren:</h4>
                <?php endif; ?>

                <div class="wrapper">
                    <main class="cards-container nogrow">
                        <?php
                        $post_type = get_post_type(get_the_ID());
                        $taxonomies = get_object_taxonomies($post_type);
                        $post_terms = wp_get_post_terms($post->ID, $taxonomies[0]);
                        //var_dump($post_terms[0]->term_id);

                        $events = und_get_events( array(
                            'post_type'   => 'und_eventpost',
                            'numberposts' => -1,
                            'tax_query'   => array(
                                array(
                                    'taxonomy'         => 'und_eventcat',
                                    'field'            => 'id',
                                    //'terms'            => [get_queried_object_id()],
                                    //'terms'            => '17115',
                                    'terms'            => $post_terms[0]->term_id,
                                    //'include_children' => true,
                                ),
                            ),
                        ),false );


                        $myposts = get_posts(array(
                                'post_type'   => 'und_eventpost',
                                'numberposts' => 2,
                                'tax_query'   => array(
                                    array(
                                        'taxonomy'         => 'und_eventcat',
                                        'field'            => 'id',
                                        'terms'            => 17115,
                                        'include_children' => true
                                    ),
                                    'orderby' => 'date',
                                    'order'   => 'DESC',
                                )
                            )
                        );
                        //print_r($myposts);

                        // $post_type = get_post_type(get_the_ID());
                        // $taxonomies = get_object_taxonomies($post_type);
                        //var_dump($taxonomies);
                        // var_dump( get_the_term_list( $post->ID, $taxonomies[0], '', ', ' ) );


                        // $category_id = get_the_category( get_the_ID());
                        // $id          = get_category($category_id[0]->term_id);
                        // var_dump( wp_get_post_terms($post->ID, $taxonomies[0]) );


                        //print_r($events);
                        //print_r(get_post_meta(59134, 'und_event_timetable_0_und_event_timetable_instancestart', true) );
                        //print_r($post->und_event_timetable_0_und_event_timetable_instancestart);
                        // foreach ( $events as $event ) {

                        // 	foreach ( $event->instances[0] as $instance ) {
                        // 		//if ( $instance->start + 7200 < time() ) {
                        // 			//print_r($instance);
                        // 		//}
                        // 	}
                        // 	//var_dump($event->post->post_title);
                        // 	//var_dump($event->instances[0]->start);
                        // }


                        $rueckblickePosts = new WP_Query(array(
                            "post_type" => "post",
                            "category_name" => $term->slug,
                            "posts_per_page" => 3,
                            "order" => "DESC",
                            "order_by"=>"date"
                        ));
                        while($rueckblickePosts->have_posts()){
                            $rueckblickePosts->the_post();
                            $post = get_post();
                            render_frontpage_block($post);
                        }

                        ?>
                    </main>
                </div>
            </div>
        </footer>
    </div>


<?php get_footer();
