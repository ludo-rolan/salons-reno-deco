<?php
    $exposant_id = $exposant->ID;
    $exposant_title = $exposant->post_title;
    $exposant_logo = get_post_meta($exposant_id, 'logo_exposant', true);
    $exposant_logo = wp_get_attachment_url($exposant_logo);
    $exposant_num_stand = get_post_meta($exposant_id, 'num_stand', true);
    $exposant_email = get_post_meta($exposant_id, 'email_exposant', true);
    $exposant_phone = get_post_meta($exposant_id, 'phone_exposant', true);
    $exposant_url = get_post_meta($exposant_id, 'url_exposant', true);
    $exposant_ou_nous_trouver = get_post_meta($exposant_id, 'ou_nous_trouver_exposant', true);
    $exposant_ou_nous_trouver_url = get_post_meta($exposant_id, 'ou_nous_trouver_url_exposant', true);
    $exposant_descriptif = get_post_meta($exposant_id, 'descriptif_exposant', true);
    // var_dump($exposant);
    $hidden_class_check = (isset($hidden_expo) && $hidden_expo) ? 'hidden' : '';
?>


<div class="exposant-card <?php echo $hidden_class_check; ?>">
    <div class="row exposant-card-top">
        <a class="exposant-card-left col-xs-4 col-sm-3" href="<?php echo $exposant_url;?>">
            <img src="<?php echo $exposant_logo; ?>" alt="<?php echo $exposant_title; ?>" />
        </a>
        <div class="exposant-card-right col-xs-8 col-sm-9">
            <h3><?php echo $exposant_title; ?></h3>
            <p class="nous-trouver"> <b>Où nous trouver :</b> <?php echo $exposant_ou_nous_trouver; ?></p>
            <p><b>N° Stand:</b> <?php echo $exposant_num_stand; ?></p>
        </div>
    </div>
    <div class="exposant-card-bottom">
        <p><?php echo $exposant_descriptif; ?></p>
    </div>
</div>