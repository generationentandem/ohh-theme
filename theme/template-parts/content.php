<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <div class="post-wrapper">
            <?php
            if ( in_category( 9218 ) ) {
                echo '<h3 class="entry-title h6 text-center">Veranstaltung</h3>';
            }
            ?>
            <h1 class="entry-title alignwide">
                <?php the_title(); ?>
            </h1>
            <?php the_lead() ?>

            <div class="entry-meta">
                <time itemscope itemtype="http://schema.org/datePublished"
                      title="<?php echo date_i18n( "l, d. F Y | H:i:s", get_the_date( "U" ) ) ?>"
                      datetime="<?php echo get_the_date( 'U' ) ?>"><?php echo date_i18n( "l, d. F Y", get_the_date( "U" ) ) ?></time>
                <?php get_template_part( 'template-parts/author' ); ?>
            </div>
        </div>
    </header>
    <aside class="entry-aside">
        <div class="small-12 social-box button-group expanded post-wrapper">
            <a target="_blank"
               href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ) . '&t=' . rawurlencode( get_the_title() ) ?>"
               class="button social-boxbutton bg-facebook" title="Auf Facebook teilen"><i
                    class="social-icon fa fa-facebook"></i>&nbsp;<span>Facebook</span></a>
            <a target="_blank"
               href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( get_the_title() ) . rawurlencode( "\n" ) . rawurlencode( get_the_permalink() ) ?>"
               class="button social-boxbutton bg-twitter"
               title="Auf Twitter teilen"><i
                    class="social-icon fa fa-twitter"></i>&nbsp;<span>Twitter</span></a>
            <a target="_blank"
               href="https://api.whatsapp.com/send?text=<?php echo rawurlencode( get_the_title() ) . rawurlencode( "\n" ) . rawurlencode( get_the_permalink() ) ?>"
               class="button social-boxbutton bg-whatsapp cell"
               title="Auf WhatsApp teilen"><i
                    class="social-icon fa fa-whatsapp"></i>&nbsp;<span>WhatsApp</span></a>
            <a target="_blank"
               href="mailto:?subject=<?php echo rawurlencode( 'Beitrag: "' . get_the_title() . '" auf UND Generationentandem' ) ?>&body=<?php echo rawurlencode( 'Schaue dir den Beitrag an: ' . get_the_title() . "\n" . get_the_lead( '', '', false ) . "\n" . get_the_permalink() ) ?>"
               class="button social-boxbutton bg-email"
               title="Per E-Mail teilen"><i
                    class="social-icon fa fa-envelope"></i>&nbsp;<span>E-Mail</span></a>
        </div>
    </aside>
    <section class="entry-content">
        <div class="post-wrapper">
            <?php the_content(); ?>
            <?php edit_post_link( __( 'Edit', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>
        </div>
    </section>
    <footer class="entry-footer">
        <div class="post-wrapper">
            <?php
            $authors = [];
            if ( function_exists( 'get_coauthors' ) ) {
                $authors = get_coauthors();
            } else {
                $author               = new stdClass();
                $author->ID           = get_the_author_meta( 'ID' );
                $author->display_name = get_the_author_meta( 'display_name' );
                $author->description  = get_the_author_meta( 'description' );
                $authors[]            = $author;
            }
            ?>
            <div class="content-footer-box">
                <h3>Beitrag von:</h3>
                <div class="author-box">
                    <?php foreach ( $authors as $coauthor ) : ?>
                        <div class="author-boxitem">
                            <div class="author-boxitem-image"
                                 data-author-id="<?php echo $coauthor->ID ?>" data-eventstate="mouseenter" tabindex="0"
                                 style="background-image: url(<?php
                                 if ( get_field( 'und_avatar', 'user_' . $coauthor->ID ) != null ) {
                                     echo wp_get_attachment_image_url( get_field( 'und_avatar', 'user_' . $coauthor->ID ), 'und-small' );
                                 } else {
                                     echo get_avatar_url( $coauthor->ID );
                                 }
                                 ?>);">
                            </div>
                            <div class="author-boxitem-content">
                                <a href="<?php echo get_author_posts_url( $coauthor->ID ) ?>">
                                    <h4
                                        class="name"><?php echo $coauthor->display_name . get_author_age( $coauthor->ID, ' (', ')' ) ?></h4>
                                </a>
                                <p><?php echo $coauthor->description; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="content-footer-box">
                <?php und_donate_small( ); ?>
            </aside>

            <?php und_the_tags( '<div class="content-footer-box entry-tags"><h4>Themen</h4><div class="round-tags-container">', '</div></div>' ); ?>
            <div class="content-footer-box">
                <div class="grid-x grid-padding-x footerbox">
                    <h4 class="small-12">Mehr <em>UND</em> in deiner Mailbox. Erhalte einmal pro Monat die wichtigsten
                        Neuigkeiten per E-Mail.</h4>
                    <form
                        action="https://generationentandem.us10.list-manage.com/subscribe/post?u=586196077b6ca51c50a7720d5&amp;id=062f9cdf0c"
                        method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                        class="small-12 validate" target="_blank" novalidate>
                        <div class="align-bottom grid-x grid-margin-x">
                            <div class="small-12 medium-5 cell">
                                <label>Name
                                    <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
                                </label>
                            </div>
                            <div class="small-12 medium-4 cell">
                                <label>Email <span class="asterisk">*</span>
                                    <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                                </label>
                            </div>
                            <div class="cell auto flex_align_items_end">
                                <button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"
                                        class="und-share button expanded">
                                    <i class="fa fa-paper-plane"></i>&nbsp;Abonnieren
                                </button>
                            </div>
                        </div>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text"
                                   name="b_586196077b6ca51c50a7720d5_062f9cdf0c"
                                   tabindex="-1" value="">
                        </div>
                        <div id="mce-responses">
                            <div class="callout alert" id="mce-error-response" style="display:none"></div>
                            <div class="callout success" id="mce-success-response" style="display:none"></div>
                        </div>
                    </form>
                    <script type='text/javascript'
                            src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                </div>
                <!--End mc_embed_signup-->
            </div>

            <div class="content-footer-box">
                <h4>Das könnte dich auch interessieren:</h4>
                <section class="cards-container related-posts nogrow alignfull" role="main">
                    <div id="relatedplaceholder">
                        <a href="/">Ähnliche Beiträge</a>
                        <?php
                        wp_add_inline_script( 'foundation',
                            "$(document).on('ready', function () {
			$.ajax({
				url: '" . admin_url( 'admin-ajax.php' ) . "',
				type: 'post',
				data: {
					action: 'single_posts',
					id: " . get_the_ID() . "
				},
				success: function (result) {
					$('#relatedplaceholder').replaceWith(result);
				}
			});
		})"
                        );?>
                    </div>
                </section>
            </div>
        </div>
    </footer>
    <section class="comments">
        <div class="post-wrapper">
            <?php comments_template(); ?>
        </div>
    </section>
</article>
