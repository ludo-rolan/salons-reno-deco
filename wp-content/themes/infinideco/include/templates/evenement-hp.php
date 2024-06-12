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

<!-- backup
<div class="text-bloc has_bg_v2 blue">
    <h2 class="default-title">L'ÉVÉNEMENT</h2>
    <p class="text-block-content"><strong>Le Salon Art&Déco</strong>, le rendez-vous du magazine Art&Décoration se tiendra du 14 au 17 octobre 2021, toujours à la Grande Halle de la Villette</p>
    <ul class="list-unstyled text-bloc-list">
        <li class="text-bloc-list-item">Quatre jours pour vous inspirer et trouver des produits et solutions pour tous vos projets de décoration et d’aménagement</li>
        <li class="text-bloc-list-item">400 marques proposant leurs produits et savoir-faire</li>
        <li class="text-bloc-list-item">Un espace pour découvrir des artisans et petites marques prometteuses</li>
        <li class="text-bloc-list-item">4 tables rondes par jour sur des thèmes tendances animées par la rédaction</li>
        <li class="text-bloc-list-item">Ateliers & coaching déco gratuits</li>
    </ul>       
</div> -->