<div class="col-md-6 col-xs-12 text-center">
    <div class="nl_footer_wrapper">
        <div class="nl_footer_title">
            <?php _e("INSCRIPTION À LA NEWSLETTER",REWORLDMEDIA_TERMS); ?>
        </div>
        <div class="nl_footer_content">
            <div class="nl_footer_text">
            <?php _e("Hopscotch Congrès traite vos données pour vous permettre de recevoir la newsletter. Pour en savoir plus sur la gestion de vos données personnelles et pour exercer vos droits, reportez-vous à la", REWORLDMEDIA_TERMS); ?>   
            <a href="/politique-de-protection-des-donnees"><?php _e("politique de protection des données", REWORLDMEDIA_TERMS); ?></a>
            </div>
            <form class="nl_footer_form" action="/inscription-newsletter">
                <input name="email_newsletter" required="required" type="email" placeholder="email" />
                <input id="submit_nl" class="submit-inline" type="submit" value="<?php _e("Envoyer",REWORLDMEDIA_TERMS); ?>" />
            </form>
        </div>
    </div>
</div>