<?php
/*
Template Name: Aktuelle News
*/

setlocale(LC_TIME, 'de_DE.UTF-8', 'deu_deu');
define('TWOHOURS', 7200);

$args = [
    'headers' => [
        'method' => 'GET',
    ]
];

// Alle News von generationentandem.ch abfragen
$post = $_GET['post'];
$response = wp_remote_request("https://www.generationentandem.ch/wp-json/wp/v2/posts/$post?_embed", $args);
$news = json_decode($response['body'], true);

function print_time_correct($time, $suffix = '') {
    if(str_ends_with($time->format('H:i'), '00')) {
        return $time->format('H') . $suffix;
    } else {
        return $time->format('H.i') . $suffix;
    }
}

function eventpostTimespan($start, $end) {
    $endNotGiven = false;
    if (!is_numeric($end)) {
        $end = $start;
        $endNotGiven = true;
    }
    $start = new DateTime("@{$start}");
    $end = new DateTime("@{$end}");

    // is without end
    if ($endNotGiven) {
        return strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . print_time_correct($start, ' Uhr');
    } else {
        // start and end on same day
        if($start->format('Ymd') == $end->format('Ymd')) {
            return '<span class="date">' . strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . '</span><span class="time">' . print_time_correct($start) . '–' . print_time_correct($end, ' Uhr') . '</span>';
        } else {
            return '<span class="date">' . strftime('%A, %e. %B %Y, ', $start->getTimestamp()) . print_time_correct($start, ' Uhr') . ' – ' . strftime('%A, %e. %B %Y, ', $end->getTimestamp()) . print_time_correct($end, ' Uhr');
        }
    }
}

get_header();
?>

<main>
    <div class="singular-container">
        <article id="post-<?php echo $news['id'] ?>" <?php post_class(); ?>>
            <section class="entry-header">
                <div class="post-wrapper">
                    <a href="/programm" class="button info hollow">
                        « Zur Übersicht
                    </a>
                    <header>
                        <h1 class="entry-title und_eventpost-title alignwide">
                            <?php echo $news['title']['rendered'] ?>
                        </h1>
                    </header>
                    <?php echo str_replace('<p>', '<p class="lead">', $news['excerpt']['rendered']) ?>

                    <div class="und_eventpost-meta">
                        <div class="und_eventpost-metablock und_eventpost-meta_desc" style="margin: 0">
                            <!--<h2 class="h3">Was?</h2>-->
                            <div class="entry-content">
                                <div class="post-wrapper" style="padding: 0px">
                                    <?php echo $news['content']['rendered'] ?>
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
    wp_enqueue_style( 'prettyPhoto', get_stylesheet_directory_uri() . '/public/assets/prettyPhoto.css', array(), false, 'all' );
    wp_enqueue_style( 'section-scroll', get_stylesheet_directory_uri() . '/public/assets/section-scroll.min.css', array(), false, 'all' );
    wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/public/assets/slick.css', array(), false, 'all' );
    wp_enqueue_style( 'ctl_styles', get_stylesheet_directory_uri() . '/public/assets/ctl_styles.min.css', array(), false, 'all' );

    wp_enqueue_script('jquery.prettyPhoto', get_stylesheet_directory_uri().'/public/assets/jquery.prettyPhoto.js', array(), false, true);
    wp_enqueue_script('jquery.section-scroll', get_stylesheet_directory_uri().'/public/assets/jquery.section-scroll.min.js', array(), false, true);
    wp_enqueue_script('load-more', get_stylesheet_directory_uri().'/public/assets/load-more.min.js', array(), false, true);
    wp_enqueue_script('slick', get_stylesheet_directory_uri().'/public/assets/slick.min.js', array(), false, true);
    wp_enqueue_script('ctl_scripts', get_stylesheet_directory_uri().'/public/assets/ctl_scripts.min.js', array(), false, true);

    get_footer();
?>
