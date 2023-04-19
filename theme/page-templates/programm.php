<?php
/*
Template Name: Programm
*/

setlocale(LC_TIME, 'de_DE', 'deu_deu');
define( 'TWOHOURS', 7200 );

get_header();

// Alle Events von generationentandem.ch abfragen
$args = [
    'headers' => [
        'method' => 'GET',
    ]
];
$response = wp_remote_request('https://www.generationentandem.ch/wp-json/wp/v2/und_eventpost?und_eventcat=18858&_embed', $args);
$events = json_decode($response['body'], true);

$future_instances = [];
$past_instances = [];

foreach($events as $event) {
    if(strtotime($event['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) > time()) {
        $future_instances[] = $event;
    } else {
        $past_instances[] = $event;
    }
}

// Alle News von generationentandem.ch abfragen
$response = wp_remote_request('https://www.generationentandem.ch/wp-json/wp/v2/posts?categories=18859&per_page=3&_embed', $args);
$news = json_decode($response['body'], true);


/** Events sortieren **/
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
?>

<div class="header-img">
    <div class="header-img-overlay">
        <p>
            Nächster Event
        </p>
    </div>
    <img class="img-object-center-60" src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2023/04/12104647/Bildschirm%C2%ADfoto-2023-04-12-um-10.41.01.png" />
</div>

<div class="wrapper">
    <section class="tile-container" id="naechste" name="naechste" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Programm</h2>
        <?php // add Events here
            $future_instances = bubblesort($future_instances, sizeof($future_instances), '>');

            foreach ($future_instances as $future_instance) {
                set_query_var('und_event_instance', $future_instance);
                get_template_part('template-parts/block/und-tile-und_eventpost');
                wp_reset_postdata();
            }

            if (empty($future_instances)) {
                echo "<div class='text-center cell'>";
                    echo "<h4>Aktuell sind keine Aktivitäten im Höchhus geplant.</h4>";
                echo "</div>";
            }
        ?>
    </section>
</div>

<div class="wrapper">
    <section class="cards-container nogrow" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Einblicke</h2>
        <?php
        foreach($news as $post) {
            setup_postdata( $GLOBALS['post'] =& $post );
            get_template_part( 'template-parts/block/und-card', 'post' );
            wp_reset_postdata();
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