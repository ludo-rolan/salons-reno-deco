<?php 
    $titre = get_option('lp_billetterie_partenaires_titre');
    $images = explode(",",get_option('lp_billetterie_partenaires_image'));
?>
<div class="section partenaires">
    <h2 class="partenaires section-title"><?php _e($titre, REWORLDMEDIA_TERMS); ?></h2>
    <div class="partenaires partners-logos">
        <?php foreach($images as $image): ?>
            <img src="<?= wp_get_attachment_url($image) ?>" alt="image" />
        <?php endforeach?>                  
    </div>
    <a class="button billetterie" href="<?php echo get_option("lp_billetterie_cta_url_fr") ?>" >
        <?php _e(get_option("lp_billetterie_button_text"),REWORLDMEDIA_TERMS) ?>
    </a>
</div>