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



	<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) : ?>
		<?php get_template_part( 'template-parts/mobile-off-canvas' ); ?>
	<?php endif; ?>

	<header class="site-header" role="banner">
		<div class="site-title-bar title-bar" <?php foundationpress_title_bar_responsive_toggle(); ?>>
			<div class="title-bar-left">
				<button aria-label="<?php _e( 'Main Menu', 'foundationpress' ); ?>" class="menu-icon" type="button" data-toggle="<?php foundationpress_mobile_menu_id(); ?>"></button>
				<span class="site-mobile-title title-bar-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
			</div>
		</div>

		<nav class="site-navigation top-bar" role="navigation" id="<?php foundationpress_mobile_menu_id(); ?>">
			<div class="top-bar-left">
				<div class="site-desktop-title top-bar-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</div>
			</div>
			<div class="top-bar-right">
				<?php foundationpress_top_bar_r(); ?>

				<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) === 'topbar' ) : ?>
					<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
				<?php endif; ?>
			</div>
		</nav>

	</header>
