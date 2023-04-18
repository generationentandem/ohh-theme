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
$response = wp_remote_request('https://www.generationentandem.ch/wp-json/wp/v2/und_eventpost?und_eventcat=18858&_embed', $args);
$events = json_decode($response['body'], true);

$future_instances = [];

foreach($events as $event) {
    if(strtotime($event['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']) > time()) {
        $future_instances[] = $event;
    }
}

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
            Der neue offene Ort der Begegnung in der Region Thun
        </p>
        <a href="#" class="button button-donation">Offenes Höchhus</a>
    </div>
    <img src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2023/04/12093751/DSF0362-scaled.jpg" />
</div>

<main>
    <div class="singular-container">
        <article <?php post_class(); ?>>
            <header class="entry-header">
                <h1><?php the_title() ?></h1>
            </header>
            <div class="entry-content">
                <div class="post-wrapper">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>
    </div>
</main>

<div class="wrapper">
    <section class="tile-container" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Aktuell</h2>
        <?php
            /*if (have_posts()) :
                $used_posts = [];

                function convert_post_list( $posts ) {
                    $array = [];
                    foreach ( $posts as $post ) {
                        $array[ '' . $post->ID ] = $post;
                    }

                    return $array;
                }
*/
                /**
                 * @param $used_posts array
                 * @param $requested WP_Post|null
                 *
                 * @return WP_Post
                 */
                /*function get_frontpage_post( $used_posts, $requested = null ) {
                    if ( $requested == null ) {
                        return get_posts( array( 'exclude' => $used_posts, 'posts_per_page' => 1, 'category__not_in' => array( 42 ) ) )[0];
                    } else {
                        return get_post( $requested );
                    }
                }

                function get_slideshow_posts($used_posts, $frontpage_featured) {
                    if ($frontpage_featured['typ'] == "custom" && $frontpage_featured['specific_post']) {
                        return $frontpage_featured['specific_post'];
                    } else {
                        return get_posts( array( 'exclude' => $used_posts, 'posts_per_page' => 3, 'category__not_in' => array( 42 ) ) );
                    }
                }

                $frontpage_featured = get_field( 'frontpage_featured', (int) get_option( 'page_on_front' ) );
                $sliderPostArray = get_slideshow_posts($used_posts, $frontpage_featured);*/

            //endif;
        ?>

    </section>
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
        ?>
    </section>
</div>

<?php
if(is_category()){
    $wp_query->used_categories[] = $wp_query->get_queried_object_id();
}
get_footer(); ?>
