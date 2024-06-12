<?php
$block1_text1 = get_option('lp_billetterie_carrousel_titre',"");
$images = explode(",",get_option('lp_billetterie_carrousel_images',"")); 
?>

<div class="section carousel">
  <div class="carousel-container">

    <h2 class="section-title"><?php _e($block1_text1, REWORLDMEDIA_TERMS); ?></h2>
          <div class="carousel-item">
            <?php foreach ($images as $image) : ?>
                <div class="item">
                  <img src="<?= wp_get_attachment_url($image) ?>">
                </div>
            <?php endforeach; ?>
          </div>
          <a class="button billetterie" href="<?php echo get_option("lp_billetterie_cta_url_fr") ?>" >
            <?php _e(get_option("lp_billetterie_button_text"),REWORLDMEDIA_TERMS) ?>
        </a>
  </div>
</div>