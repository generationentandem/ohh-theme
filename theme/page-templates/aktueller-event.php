<?php
/*
Template Name: Aktueller Event
*/

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

$args = [
    'headers' => [
        'method' => 'GET',
    ]
];

// Alle Events von generationentandem.ch abfragen
$event = $_GET['event'];
$response = wp_remote_request("https://www.generationentandem.ch/wp-json/wp/v2/und_eventpost/$event", $args);
$eventData = json_decode($response['body'], true);

get_header();
?>

<main>
    <div class="singular-container">
        <article id="post-<?php $eventData['id'] ?>" <?php post_class(); ?>>
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
            ?>


            <section class="entry-header">
                <div class="post-wrapper">
                    <a href="/programm" class="button info hollow">
                        « Zur Übersicht
                    </a>
                    <header>
                        <h1 class="entry-title und_eventpost-title alignwide">
                            <?php echo $eventData['title']['rendered'] ?>
                        </h1>
                    </header>
                    <?php echo str_replace('<p>', '<p class="lead">', $eventData['excerpt']['rendered']) ?>

                    <div class="und_eventpost-meta">
                        <div class="und_eventpost-metablock und_eventpost-meta_timetable" style="margin: 0">
                            <p class="lead event-info-box position-relative text-align-left">
                                <?php
                                if ($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instanceend'] != null) {
                                    $startDate = date('Y-m-d', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']));
                                    $startTime = date('H:i', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']));
                                    $endDate = date('Y-m-d', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instanceend']));
                                    $endTime = date('H:i', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instanceend']));
                                } else {
                                    $startDate = date('Y-m-d', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']));
                                    $startTime = date('H:i', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']));
                                    $endDate = $startDate;
                                    $endTime = date('H:i', strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) + 5400);
                                }
                                ?>

                                <button id="calendarButton" class="button-click position-absolute"></button>

                                <i class="fa fa-calendar und-event-text padding-right-1 padding-left-1" aria-hidden="true"></i>

                                <?php
                                echo '<time class="und_event_instance event-info-text-underline">' . eventpostTimespan(strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']), strtotime($eventData['acf']['und_event_timetable'][0]['und_event_timetable_instanceend'])) . '</time><br>';
                                ?>
                            </p>
                            <script type="application/javascript">
                                const config = {
                                    name: "<?php echo $eventData['title']['rendered'] ?>",
                                    startDate: "<?php echo $startDate ?>",
                                    startTime: "<?php echo $startTime ?>",
                                    endDate: "<?php echo $endDate ?>",
                                    endTime: "<?php echo $endTime ?>",
                                    timeZone: "Europe/Zurich",
                                    location: "<?php echo $eventData['acf']['und_event_place'] ?>",
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
                                $mapsLocation = $eventData['acf']['und_event_maps_location'];
                                $mapsLocation = str_replace(' ', '+', $mapsLocation);

                                if ($mapsLocation != null) {
                                    $link = "https://www.google.com/maps/place/$mapsLocation";
                                    echo "<a class='button-click position-absolute' target='blank' href='$link'></a>";
                                    echo "<span class='event-info-text-underline'>".$eventData['acf']['und_event_place']."</span>";
                                } else {
                                    echo $eventData['acf']['und_event_place'];
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
                                    <?php echo $eventData['content']['rendered'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
    </div>
</main>

<?php
get_footer();
?>
