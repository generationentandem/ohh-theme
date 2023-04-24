<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

<header class="page-header">
    <h1 class="page-title"><?php _e( 'Nichts gefunden', 'foundationpress' ); ?></h1>
</header>

<div class="page-content">
    <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

        <p><?php printf( __( 'Bereit einen Beitrag zu schreiben? <a href="%1$s">Jetzt Beitrag verfassen.</a>.', 'foundationpress' ), admin_url( 'post-new.php' ) ); ?></p>

    <?php elseif ( is_search() ) : ?>

        <p><?php printf( __( 'Entschuldigung, wir haben nichts gefunden das deiner Suche "%s" entspricht. Probiere es bitte mit einem anderen Suchbegriff.', 'foundationpress' ),get_search_query()); ?></p>
        <?php get_search_form(); ?>

    <?php else : ?>

        <p><?php _e( 'Wir konnten ihren gewünschten Inhalt nicht finden. Sie können versuchen, nach dem Inhalt zu suchen.', 'foundationpress' ); ?></p>
        <?php get_search_form(); ?>

    <?php endif; ?>
</div>
