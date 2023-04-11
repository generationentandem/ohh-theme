<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */
?>
<footer class="footer">
    <div class="footer-container">
        <section id="footer" class="footer-grid">
            <?php do_action( 'foundationpress_before_footer' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-1' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-2' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-3' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-4' ); ?>
        </section>
        <section id="footer-second" class="footer-grid">
            <?php dynamic_sidebar( 'footer-widgets-5' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-6' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-7' ); ?>
            <?php dynamic_sidebar( 'footer-widgets-8' ); ?>
            <?php do_action( 'foundationpress_after_footer' ); ?>
        </section>
        <section class="footer-grid">
            <section class="footer-copyright">
                <a href="/wp-admin">Intern</a>
                | UND Generationentandem © <?php echo date( 'Y' ); ?> |
                <a href="/impressum">Impressum & Datenschutz</a>
            </section>
        </section>
    </div>
</footer>

<?php
if ( ! empty( $wp_query->used_categories ) || ! empty( $wp_query->und_nav_walker->style ) || ! empty( $wp_query->used_tags ) ) {
    echo '<style>';
    if ( ! empty( $wp_query->und_nav_walker->style ) ) {
        foreach ( array_unique( $wp_query->und_nav_walker->style ) as $id => $color ) {
            echo ".menu-secondary .menu-item-$id a{background-color:$color!important}";
        }
    }
    if ( ! empty( $wp_query->used_categories ) ) {
        foreach ( array_unique( $wp_query->used_categories ) as $id ) {
            $color = get_taxonomy_color( $id );
            if ( ! empty( $color ) ) {
                echo ".uc-$id{color:" . get_color_from_hex( $color ) . "!important}.uc-$id:hover{color:" . get_color_from_hex( $color, 1.2 ) . "!important;background-color:" . get_color_from_hex( $color, 1, 0.1 ) . "!important}.bg-cat-$id{background-color:" . get_color_from_hex( $color ) . "!important}";
            }
        }
    }
    foreach ( $wp_query->used_tags as $tag ) {
        $bg = get_field( 'farbe', $tag );
        if ( $bg == null ) {
            $bg = '#000000';
        }
        $fg   = get_contrast_color( $bg );
        $slug = $tag->slug;
        echo ".tag.tag-$slug{color:$fg;background-color:$bg}";
    }
    echo '</style>';
}
?>

<?php wp_footer(); ?>
</div> <!-- Ende div.und-italic (beginnt in header.php) -->
<script>
    $(document).ready(function () {
        $("#s").on("focus", function () {
            $("#searchsubmit").removeClass("search-button-invisible");
        });

        $("#s").on("focusout", function () {
            console.log($("#s").val());
            if ($("#s").val() == "") {
                $("#searchsubmit").addClass("search-button-invisible");
            }
        });
    });

    document.getElementById('s').addEventListener('blur', function (event) {
        document.getElementById('s').placeholder = "Suche";
    });
    document.getElementById('s').addEventListener('focus', function (event) {
        document.getElementById('s').placeholder = "Suchbegriff";
    })
</script>
<?php do_action( 'foundationpress_before_closing_body' ); ?>
</body>
</html>
