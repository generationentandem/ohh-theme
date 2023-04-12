<?php
/*
Template Name: Aktuell
*/

setlocale(LC_TIME, 'de_DE', 'deu_deu');
define( 'TWOHOURS', 7200 );

get_header();

$events = und_get_events( array(
    'post_type'   => 'und_eventpost',
    'numberposts' => -1,
    'tax_query'   => array(
        array(
            'taxonomy'         => 'und_eventcat',
            'field'            => 'id',
            'terms'            => 10977,
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
    <section class="tile-container" id="naechste" name="naechste" role="main">
        <h2 class="title-tile f19_heading" style="border-bottom: 6px solid #0a0a0a;">Aktuell</h2>
        <?php // add Events here
        $view = 'past_instances-whack';

        $view = $_SERVER['REQUEST_URI'];
        $future_instances = bubblesort($future_instances, sizeof($future_instances), '>');
        foreach ($future_instances as $future_instance) {
            $post = $future_instance->event->post;
            setup_postdata($GLOBALS['post'] =& $post);
            set_query_var('und_event_instance', $future_instance);
            get_template_part('template-parts/block/und-tile', $future_instance->event->post->post_type);
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
