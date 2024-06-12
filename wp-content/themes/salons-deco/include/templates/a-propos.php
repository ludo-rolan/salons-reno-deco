<?php 
$page_name = get_query_var('page_name');
if( empty($page_name) || $page_name == 'exposition_physique') { 
?>
    <div class="text-bloc has_bg blue">
        <h2 class="default-title">à propos</h2>
        <p class="text-bloc-content">Besoin d'inspiration pour décorer ou aménager votre intérieur ?</p>
        <p class="text-bloc-content"><strong>Le Salon Art&Déco</strong>, le rendez-vous incontournable du<strong> magazine Art&Décoration</strong>, offre une vision éclairée et intime de l’aménagement et de la décoration.
        <br/>
        La 6e édition du salon sera une nouvelle occasion d’incarner l’Art de Vivre à la française, en réunissant en un même lieu les savoir-faire, les marques et les métiers d'art pour vous inspirer et vous accompagner dans vos projets de décoration et d'aménagement.
        <br/>
        <strong>Retrouvez dès maintenant sur notre site des conseils déco, des idées d’équipement et d’aménagement pour répondre à toutes vos envies ! </strong></p>
        <p class="text-bloc-content">Le Salon Art&Déco Paris aura lieu du <strong>14 au 17 octobre 2021,</strong> toujours à  <strong>la Grande Halle de la Villette</strong> : Save the date !</p>
    </div>
	
<?php }else{ ?>
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
    </div>
<?php } ?>