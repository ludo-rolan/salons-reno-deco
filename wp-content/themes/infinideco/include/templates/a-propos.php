<?php 
    $style = ""; 
    $apropos_imge_bg = get_option("texte_statique_ep_apropos_image","");
    if (!empty($apropos_imge_bg)) {
        $apropos_imge_bg = wp_get_attachment_image_src($apropos_imge_bg, 'full')[0];
    }
    if (!empty($apropos_imge_bg)) {
        $style = "style=\"background-image:linear-gradient(to right, #000000 0%,rgba(9,24,82,0.2) 95%),url($apropos_imge_bg);\"";
    }
    $apropos_color = get_option("texte_statique_ep_apropos_color","");
    if (!empty($apropos_color)) {
        $style  = "style=\"background-image:linear-gradient(to right, $apropos_color 0%,rgba(9,24,82,0.2) 95%);\"";
        if (!empty($apropos_imge_bg)) {
            $style  = "style=\"background-image:linear-gradient(to right, $apropos_color 0%,rgba(9,24,82,0.2) 95%),url($apropos_imge_bg)\"";
        }
    }
    $apropos_title = get_option("texte_statique_ep_apropos_title","");
    $apropos_texte = get_option("texte_statique_ep_apropos_content","");


?>
<div class="text-bloc has_bg blue" <?php echo $style; ?>>
    <h2 class="default-title"><?php echo $apropos_title; ?></h2>
    <?php echo stripslashes($apropos_texte); ?>
</div>
