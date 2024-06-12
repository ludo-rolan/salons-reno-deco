<?php 
    $style = ""; 
    $apropos_imge_bg = get_option("texte_statique_hp_apropos_image","");
    if (!empty($apropos_imge_bg)) {
        $apropos_imge_bg = wp_get_attachment_image_src($apropos_imge_bg, 'full')[0];
    }
    if (!empty($apropos_imge_bg)) {
        $style = "style=\"background-image:linear-gradient(to right, #000000 0%,rgba(9,24,82,0.2) 95%),url($apropos_imge_bg);\"";
    }
    $apropos_color = get_option("texte_statique_hp_apropos_color","");
    if (!empty($apropos_color)) {
        $style  = "style=\"background-image:linear-gradient(to right, $apropos_color 0%,rgba(9,24,82,0.2) 95%);\"";
        if (!empty($apropos_imge_bg)) {
            $style  = "style=\"background-image:linear-gradient(to right, $apropos_color 0%,rgba(9,24,82,0.2) 95%),url($apropos_imge_bg)\"";
        }
    }
    $apropos_title = get_option("texte_statique_hp_apropos_title","");
    $apropos_texte = get_option("texte_statique_hp_apropos_content","");


?>
<div class="text-bloc has_bg blue" <?php echo $style; ?>>
    <h2 class="default-title"><?php echo $apropos_title; ?></h2>
    <?php echo stripslashes($apropos_texte); ?>
</div>

<!-- backup 
<div class="text-bloc has_bg blue">
    <h2 class="default-title">à propos</h2>
    <p class="text-bloc-content">Besoin d'inspiration pour décorer ou aménager votre intérieur ?</p>
    <p class="text-bloc-content"><strong>Le Salon Art&Déco</strong>, le rendez-vous incontournable du<strong> magazine Art&Décoration</strong>, offre une vision éclairée et intime de l’aménagement et de la décoration.
    <br/>
    La 6e édition du salon sera une nouvelle occasion d’incarner l’Art de Vivre à la française, en réunissant en un même lieu les savoir-faire, les marques et les métiers d'art pour vous inspirer et vous accompagner dans vos projets de décoration et d'aménagement.
    <br/>
    <strong>Retrouvez dès maintenant sur notre site des conseils déco, des idées d’équipement et d’aménagement pour répondre à toutes vos envies ! </strong></p>
    <p class="text-bloc-content">Le Salon Art&Déco Paris aura lieu du <strong>14 au 17 octobre 2021,</strong> toujours à  <strong>la Grande Halle de la Villette</strong> : Save the date !</p>
</div> -->