<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
<!doctype html>
<html class="no-js" lang="de-CH">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php wp_head(); ?>
</head>
<body id="italic" <?php body_class(); ?>>
<header class="main nav-">
    <nav id="menu">
        <div class="menu-main">
            <div class="wrapper">
                <div class="menu-main-wrapper">
                    <div class="deleted-menu-main-branding" role="heading">
                        <div class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                               title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home">
                                <img style="height: 50px" src="https://cdn-und.s3.eu-central-1.amazonaws.com/images/2023/01/17145409/Logo-weiss-UND-Generationentandem.png" alt="UND Generationentandem">
                                <?php /*<svg id="logo-und" xmlns="http://www.w3.org/2000/svg" viewBox="20 5 100 60">
									<title>«und»</title>
									<path d="M25.5 19v23.4S25.5 54 40 54s14.4-11.6 14.4-11.6V31s.5-12 14.3-12 13.7 12 13.7 12v23h17c9 0 15-6.6 15-17.4 0-11-6-17.5-15-17.5h-14m-31 0v35"/>
								</svg> */?>
                                <h1><span class="show-for-sr">UND</span> Generationentandem</h1>
                            </a>
                        </div>
                    </div>
                    <div class="menu-main-container" role="navigation">
                        <?php
                        global $wp_query;
                        $wp_query->used_categories = array();
                        $wp_query->used_tags       = array();
                        $wp_query->und_nav_walker  = new Und_Walker_Nav_Menu();
                        $menugen                   = wp_nav_menu( [
                            "theme_location" => "main-menu",
                            "depth"          => 5,
                            "walker"         => $wp_query->und_nav_walker,
                            "container"      => false
                        ] );
                        ?>
                    </div>
                    <div class="navigation-control" onclick="document.body.classList.toggle('nav-opened')"
                         role="button">
                        <!-- new version triggers modal version
                         <div class="navigation-control" data-toggle="navModal" role="button"> -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 22">
                            <ellipse cx="8" cy="11" class="s" rx="6.4" ry="6.4"/>
                            <path d="M14 15l4 3" class="s"/>
                            <path d="M18 5h16" class="l t"/>
                            <path d="M20 11h14" class="l m"/>
                            <path d="M22 17h12" class="l b"/>
                        </svg>
                    </div>
                    <div class="menu-main-search" role="search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-secondary">
            <div class="wrapper">
                <div class="menu-kategoriemenu-container" role="navigation">
                    <?php echo $wp_query->und_nav_walker->submenu ?>
                </div>
            </div>
        </div>
        <div class="menu-meta">
            <div class="wrapper">
                <div class="menu-meta-container" role="navigation">
                    <?php wp_nav_menu( array( 'theme_location' => 'meta-menu-left', "walker" => $wp_query->und_nav_walker ) ); ?>
                    <?php wp_nav_menu( array( 'theme_location' => 'meta-menu-right', "walker" => $wp_query->und_nav_walker ) ); ?>
                    <ul class="nav-social-icon">
                        <li><a href="https://www.facebook.com/generationentandem/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/undTandem" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://www.instagram.com/generationentandem/" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="https://soundcloud.com/generationentandem" target="_blank"><i class="fa fa-soundcloud"></i></a></li>
                        <li><a href="https://www.youtube.com/channel/UCyJ52xscqRdb7z3GsVub_Ew" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
                        <li><a href="https://www.generationentandem.ch/about/netzwerk/newsletter/"><i class="fa fa-envelope"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?php
        if ( is_category() ) {
            ?>
            <?php
            global $wp_query;

            function get_cat_dept( $cat ) {
                $cats_str   = get_category_parents( $cat, false, '%#%' );
                $cats_array = explode( '%#%', $cats_str );
                $cat_depth  = sizeof( $cats_array ) - 2;

                return $cat_depth;
            }


            function render_submenucategories( $submenucategory ) {
                global $wp_query;
                $args = array(
                    'child_of'     => $submenucategory->cat_ID,
                    'taxonomy'     => 'category',
                    'hide_empty'   => 0,
                    'hierarchical' => true,
                    'depth'        => 1,
                );
                $cats = get_categories( $args );
                if ( count( $cats ) > 0 ) {
                    ?>

                    <div class="subcategory_navigation">
                        <div class="wrapper">

                            <ul class="category_list reverse">
                                <?php
                                foreach ( $cats as $category ) {
                                    $catlink          = get_term_link( $category );
                                    $catname          = get_category_field( 'kurzname', $category->cat_ID );
                                    $current_category = ( $category->cat_ID == $wp_query->get_queried_object_id() ) ? "current_menu_item" : "";
                                    if ( $catname == "" ) {
                                        $catname = $category->name;
                                    }
                                    $wp_query->used_categories[] = $category->cat_ID;
                                    echo "<li class='category-listelement ${current_category}'><a href='${catlink}'>${catname}</a></li>";
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                    <?php
                }
                //else {
                //echo "<hr class='category_line' style='order: 10;border-bottom: 10px solid " . get_taxonomy_color( $wp_query->get_queried_object_id() ) . "'>";
                //}
            }

            $submenucategory = false;
            if ( is_category() && ! is_category( get_category_by_slug( 'online' ) ) && ! is_category( get_category_by_slug( 'live' ) ) ) {
                $cat_depth       = get_cat_dept( $wp_query->get_queried_object_id() );
                $submenucategory = get_category( $wp_query->get_queried_object_id() );
                if ( $cat_depth != 1 ) {
                    if ( 1 < $cat_depth ) {
                        while ( get_cat_dept( $submenucategory ) !== 1 ) {
                            if ( get_cat_dept( $submenucategory ) < 1 ) {
                                break;
                            }
                            $submenucategory = get_category( $submenucategory->category_parent );
                        }
                        render_submenucategories( $submenucategory );
                    }
                } else {
                    render_submenucategories( $submenucategory );
                }
                ?>
                <style>
                    .subcategory_navigation {
                        background-color: <?php echo get_taxonomy_color($submenucategory->cat_ID)?>;
                    }

                    .subcategory_navigation li.current_menu_item a {
                        color: <?php echo get_taxonomy_color($submenucategory->cat_ID)?>;
                    }
                </style>

                <?php
            }
        }
        ?>
    </nav>

    <?php
    class Mobile_Menu_Walker extends Walker_Nav_Menu {
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );

            // Default class.
            $classes = array( 'sub-menu' );

            /**
             * Filters the CSS class(es) applied to a menu list element.
             *
             * @since 4.8.0
             *
             * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
             * @param stdClass $args    An object of `wp_nav_menu()` arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $output .= "{$n}{$indent}<ul data-tab-content $class_names>{$n}";
        }

        function start_el(&$output, $item, $depth=0, $args=[], $id=0) {

            array_push($item->classes, "item-" . $item->object_id);
            array_push($item->classes, "category-" . $item->title);

            if (in_array("current-menu-item", $item->classes) || in_array("current-menu-parent", $item->classes)) {
                array_push($item->classes, "is-active");
            }

            $output .= "<li data-accordion-item class='" .  implode(" ", $item->classes) . "'>";

            if ($args->walker->has_children) {
                $output .= '<a class="collapse-control" href=""><i class="fa"></i></a>';
            }

            $output .= '<div class="link-container ' . 'depth-' . $depth  . '">';

            if ($item->url && $item->url != '#') {
                $output .= '<a href="' . $item->url . '">';
            } else {
                $output .= '<span>';
            }

            $output .= $item->title;

            if ($item->url && $item->url != '#') {
                $output .= '</a>';
            } else {
                $output .= '</span>';
            }

            $output .= '</div>';
        }
    }
    ?>

    <div class="full reveal navModal" id="navModal" data-reveal>
        <div class="header grid-x">
            <div class="cell small-10">
                <svg alt="UND Generationentandem" id="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2063.39 347.14"><defs><style>.cls-1{letter-spacing:-.02em;}.cls-2{letter-spacing:-.02em;}.cls-3{letter-spacing:-.03em;}.cls-4,.cls-5{fill:#fff;}.cls-6{letter-spacing:-.02em;}.cls-5{font-family:SourceSansPro-Regular, 'Source Sans Pro';font-size:140px;}.cls-7{letter-spacing:-.02em;}.cls-8{letter-spacing:-.02em;}.cls-9{letter-spacing:-.02em;}.cls-10{letter-spacing:-.03em;}.cls-11{letter-spacing:-.03em;}.cls-12{letter-spacing:-.03em;}.cls-13{letter-spacing:-.02em;}.cls-14{letter-spacing:-.02em;}.cls-15{letter-spacing:-.02em;}.cls-16{letter-spacing:-.03em;}.cls-17{letter-spacing:-.03em;}.cls-18{letter-spacing:-.01em;}</style></defs><g id="allgemein"><text class="cls-5" transform="translate(812.36 199.87)"><tspan class="cls-3" x="0" y="0">d</tspan><tspan class="cls-7" x="72.9" y="0">a</tspan><tspan x="141.2" y="0">s </tspan><tspan class="cls-2" x="0" y="130">G</tspan><tspan class="cls-8" x="83.91" y="130">e</tspan><tspan class="cls-14" x="150.34" y="130">n</tspan><tspan class="cls-8" x="223.76" y="130">e</tspan><tspan class="cls-18" x="290.19" y="130">r</tspan><tspan class="cls-12" x="336.69" y="130">a</tspan><tspan class="cls-1" x="404.07" y="130">t</tspan><tspan class="cls-10" x="448.57" y="130">i</tspan><tspan class="cls-17" x="479.27" y="130">o</tspan><tspan class="cls-13" x="551.52" y="130">n</tspan><tspan class="cls-8" x="624.93" y="130">e</tspan><tspan class="cls-16" x="691.36" y="130">n</tspan><tspan class="cls-15" x="763.8" y="130">t</tspan><tspan class="cls-11" x="808.67" y="130">a</tspan><tspan class="cls-9" x="876.41" y="130">n</tspan><tspan class="cls-6" x="949.98" y="130">d</tspan><tspan class="cls-9" x="1024.29" y="130">e</tspan><tspan x="1090.72" y="130">m</tspan></text><g id="allgemein-2"><g id="basislogo"><path class="cls-4" d="M731.5,59.77c-24.28-28.08-58.51-42.91-98.98-42.91-.1,0-.21,0-.31,0h-117.51c-7.88,0-14.28,6.39-14.28,14.28s6.39,14.28,14.28,14.28h117.79c31.96,0,58.73,11.42,77.42,33.04,19.52,22.58,29.87,55.59,29.93,95.48,.12,78.44-41.13,127.25-107.63,127.37h-125.2V128.88c0-1.15-.18-28.55-15.89-55.97-14.65-25.57-45.7-56.05-110.4-56.05-52.8,0-83.97,20.31-102.13,41.62V31.14c0-7.88-6.39-14.28-14.28-14.28s-14.28,6.39-14.28,14.28V221.05c0,.21-.36,20.84-13.16,40.74-16.85,26.21-47.16,39.5-90.07,39.5s-73.22-13.29-90.07-39.5c-12.8-19.9-13.16-40.54-13.16-40.67V31.14c0-7.88-6.39-14.28-14.28-14.28s-14.28,6.39-14.28,14.28V221.13c0,1.13,.19,28,16.84,54.76,27.74,44.6,77.82,53.96,114.94,53.96,32.36,0,74.56-7.12,103.23-38.49v24.21c0,7.88,6.39,14.28,14.28,14.28s14.28-6.39,14.28-14.28V129.3c.17-2.28,1.97-22.21,14.07-41.79,17.26-27.93,46.89-42.1,88.07-42.1s69.13,13.72,85.1,40.78c12.32,20.88,12.64,42.53,12.64,42.68v186.7c0,7.88,6.39,14.28,14.28,14.28h139.5c82.85-.15,136.3-61.38,136.15-155.97-.07-46.82-12.83-86.28-36.89-114.11Z"/></g></g></g></svg>
            </div>
            <div class="cell small-2">
                <div class="height-100 grid-x grid-padding-x align-middle align-center-middle">
                    <button class="cell btn-close" data-close aria-label="Navigation schliessen" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="body grid-x">
            <div class="cell small-12">
                <div class="menu-main-search" role="search">
                    <?php get_search_form(); ?>
                </div>
            </div>
            <div class="cell small-12" style="margin-bottom: 20px;">
                <div class="menu-heading">Menu</div>
                <?php
                global $wp_query;
                $menugen = wp_nav_menu( [
                    "theme_location" => "main-menu",
                    "depth"          => 2,
                    "walker"         => new Mobile_Menu_Walker(),
                    "container"      => false,
                    "items_wrap"	 => '<ul id="%1$s" class="%2$s" data-accordion data-allow-all-closed="true" data-multi-expand="true">%3$s</ul>'
                ] );
                ?>
            </div>

            <div class="cell small-12">
                <?php wp_nav_menu( array( 'theme_location' => 'meta-menu-left', "walker" => $wp_query->und_nav_walker ) ); ?>
            </div>

            <div class="cell small-12" style="align-self: end;">
                <ul class="nav-social-icon">
                    <li><a href="https://www.facebook.com/generationentandem/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://twitter.com/undTandem" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/generationentandem/" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://soundcloud.com/generationentandem" target="_blank"><i class="fa fa-soundcloud"></i></a></li>
                    <li><a href="https://www.youtube.com/channel/UCyJ52xscqRdb7z3GsVub_Ew" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
                    <li><a href="https://www.generationentandem.ch/about/netzwerk/newsletter/"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="und-italic">
