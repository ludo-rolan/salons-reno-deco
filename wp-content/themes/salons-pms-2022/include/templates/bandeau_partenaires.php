
<?php
$bandeau_partenaires = get_option('bandeau_partner');
if(!empty($bandeau_partenaires)){

?>
<div class="bandeau_partners">
    <div class="container">
        <h1 class="bandeau_partners_title" ><?php _e("NOS PARTENAIRES", REWORLDMEDIA_TERMS); ?></h1>
        <div class="bandeau_partners_logos">
            <?php
            foreach($bandeau_partenaires['logo'] as $key=>$val){
                if(!empty($val)){
                    ?>
                    <div class="bandeau_partners_item"><a href="<?php echo $bandeau_partenaires['url'][$key]; ?>" target="_blank"><img src="<?php echo $val; ?>" /></a></div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
}
