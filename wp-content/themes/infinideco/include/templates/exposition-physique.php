<?php 
    $style = ""; 
    $exposition_physique_imge_bg = get_option("texte_statique_hp_exposition_physique_image","");
    if (!empty($exposition_physique_imge_bg)) {
        $exposition_physique_imge_bg = wp_get_attachment_image_src($exposition_physique_imge_bg, 'full')[0];
    }
    if (!empty($exposition_physique_imge_bg)) {
        $style = "style=\"background-image:linear-gradient(to right, #000000 0%,rgba(9,24,82,0.2) 95%),url($exposition_physique_imge_bg);\"";
    }
    $exposition_physique_color = get_option("texte_statique_hp_exposition_physique_color","");
    if (!empty($exposition_physique_color)) {
        $style  = "style=\"background-image:linear-gradient(to right, $exposition_physique_color 0%,rgba(9,24,82,0.2) 95%);\"";
        if (!empty($exposition_physique_imge_bg)) {
            $style  = "style=\"background-image:linear-gradient(to right, $exposition_physique_color 0%,rgba(9,24,82,0.2) 95%),url($exposition_physique_imge_bg)\"";
        }
    }
    $exposition_physique_title = get_option("texte_statique_hp_exposition_physique_title","");
    $exposition_physique_texte = get_option("texte_statique_hp_exposition_physique_content","");
?>

<div class="text-bloc has_bg_v2 blue" <?php echo $style; ?>>
    <h2 class="default-title"><?php echo $exposition_physique_title; ?></h2>
    <?php echo stripslashes($exposition_physique_texte); ?>
</div>
