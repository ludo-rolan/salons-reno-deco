<?php
    $partenaire_id = $exposant->ID;
    $partenaire_title = $exposant->post_title;
    $partenaire_logo = get_post_meta($partenaire_id, 'logo_partenaire', true);
    $partenaire_logo = wp_get_attachment_url($partenaire_logo);
    $partenaire_phone = get_post_meta($partenaire_id, 'phone_partenaire', true);
    $partenaire_url = get_post_meta($partenaire_id, 'url_partenaire', true)??"#";
    $partenaire_descriptif = get_post_meta($partenaire_id, 'descriptif_partenaire', true);
    $hidden_class_check = (isset($hidden_expo) && $hidden_expo) ? 'hidden' : '';
?>


<div class="exposant-card <?php echo $hidden_class_check; ?>">
    <div class="row exposant-card-top">
        <a class="exposant-card-left col-xs-4 col-sm-3" href="<?php echo $partenaire_url ?>">
            <img src="<?php echo $partenaire_logo; ?>" alt="<?php echo $partenaire_title; ?>" />
        </a>
        <div class="exposant-card-right col-xs-8 col-sm-9">
            <h3><?php echo $partenaire_title; ?></h3>
        </div>
    </div>
    <div class="exposant-card-bottom">
        <p><?php echo $partenaire_descriptif; ?></p>
    </div>
</div>