<?php
/*
Template Name: Aktuell
*/

setlocale(LC_TIME, 'de_DE', 'deu_deu');
define( 'TWOHOURS', 7200 );

get_header();

$args = [
    'headers' => [
        'method' => 'GET',
    ]
];

// Alle Events von generationentandem.ch abfragen
$response = wp_remote_request('https://www.generationentandem.ch/wp-json/wp/v2/und_eventpost?und_eventcat=18874&_embed&per_page=100', $args);
$events = json_decode($response['body'], true);

$future_instances = [];

foreach($events as $event) {
    if(strtotime($event['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) > time()) {
        $future_instances[] = $event;
    }
}

// Alle News von generationentandem.ch abfragen
$response = wp_remote_request('https://www.generationentandem.ch/wp-json/wp/v2/posts?categories=18859&_embed', $args);
$news = json_decode($response['body'], true);

/** Events sortieren **/
function bubblesort($array, $length, $order){
    for ($i = ($length - 1); $i >= 0; $i--) {
        for ($j = 1; $j <= $i; $j++) {
            if($order == '>') {
                if ($array[$j-1]['acf']['und_event_timetable'][0]['und_event_timetable_instancestart'] > $array[$j]['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) {
                    $temp = $array[$j-1];
                    $array[$j-1] = $array[$j];
                    $array[$j] = $temp;
                }
            } else if($order == '<') {
                if ($array[$j-1]['acf']['und_event_timetable'][0]['und_event_timetable_instancestart'] < $array[$j]['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) {
                    $temp = $array[$j-1];
                    $array[$j-1] = $array[$j];
                    $array[$j] = $temp;
                }
            }
        }
    }
    return $array;
}
?>

<div class="header-img">
    <div class="header-img-overlay">
        <p>
            Neues Leben in historischen Mauern: <br />
            Ein Ort der Begegnung
        </p>
        <a href="/organisation" class="button button-donation">Offenes Höchhus</a>
    </div>
    <img src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2023/07/03074233/R5__9546.jpg" />
</div>

<main>
    <div class="singular-container">
        <article <?php post_class(); ?>>
            <div class="entry-content">
                <div class="post-wrapper">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>
    </div>
</main>

<div class="wrapper">
    <section class="cards-container nogrow" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Aktuell</h2>
        <?php
            foreach($news as $post) {
                setup_postdata( $GLOBALS['post'] =& $post );
                get_template_part( 'template-parts/block/und-card', 'post' );
                wp_reset_postdata();
            }
        ?>
    </section>
</div>

<div class="wrapper">
    <section class="tile-container" id="naechste" name="naechste" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Programm</h2>
        <?php // add Events here
            $future_instances = bubblesort($future_instances, sizeof($future_instances), '>');
            $i = 0;
            foreach ($future_instances as $future_instance) {
                if($i < 12) {
                    set_query_var('und_event_instance', $future_instance);
                    get_template_part('template-parts/block/und-tile-und_eventpost');
                    wp_reset_postdata();
                }
                $i++;
            }

            if (empty($future_instances)) {
                echo "<div class='text-center cell'>";
                    echo "<h4>Aktuell sind keine Aktivitäten im Höchhus geplant.</h4>";
                echo "</div>";
            }
        ?>
    </section>
</div>

<?php
if(is_category()){
    $wp_query->used_categories[] = $wp_query->get_queried_object_id();
}
get_footer();
?>
