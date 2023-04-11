<?php
/**
 * Register widget areas
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_sidebar_widgets' ) ) :
    function foundationpress_sidebar_widgets() {
        register_sidebar(
            array(
                'id'            => 'sidebar-widgets',
                'name'          => __( 'Sidebar widgets', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this sidebar container.', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );

        register_sidebar(
            array(
                'id'            => 'footer-widgets-1',
                'name'          => __( 'Footer widget 1', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-2',
                'name'          => __( 'Footer widget 2', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-3',
                'name'          => __( 'Footer widget 3', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-4',
                'name'          => __( 'Footer widget 4', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-5',
                'name'          => __( 'Footer widget 5', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s" style="padding-top: 40px">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-6',
                'name'          => __( 'Footer widget 6', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-7',
                'name'          => __( 'Footer widget 7', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s" style="padding-top: 40px">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
        register_sidebar(
            array(
                'id'            => 'footer-widgets-8',
                'name'          => __( 'Footer widget 8', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s" style="padding-top: 40px">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );

        // Footer Widgets for Generationenfestival
        register_sidebar(
            array(
                'id'            => 'generationenfestival-footer-widgets-1',
                'name'          => __( 'Generationenfestival Footer 1 (Logo)', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );

        register_sidebar(
            array(
                'id'            => 'generationenfestival-footer-widgets-2',
                'name'          => __( 'Generationenfestival Footer 2 (Kontakt)', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );

        register_sidebar(
            array(
                'id'            => 'generationenfestival-footer-widgets-3',
                'name'          => __( 'Generationenfestival Footer 3 (Info)', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );

        register_sidebar(
            array(
                'id'            => 'generationenfestival-footer-widgets-4',
                'name'          => __( 'Generationenfestival Footer 4 (Folge uns)', 'foundationpress' ),
                'description'   => __( 'Drag widgets to this footer container', 'foundationpress' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h6>',
                'after_title'   => '</h6>',
            )
        );
    }

    add_action( 'widgets_init', 'foundationpress_sidebar_widgets' );
endif;
