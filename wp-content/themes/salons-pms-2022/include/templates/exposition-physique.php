<div class="text-bloc has_bg red">
	<h2 class="default-title">L'exposition physique</h2>
    <p class="text-bloc-content bold"><strong>Le Salon Art&Déco, en partenariat avec le magazine Art&Décoration, se tiendra du jeudi 11 au dimanche 14 mars 2021, toujours à la Grande Halle de la Villette</strong></p>
    <ul class="list-unstyled text-bloc-list">
	Quatre jours pour vous inspirer et trouver des produits et solutions pour tous vos projets de décoration et d’aménagement :
        <li class="text-bloc-list-item">400 marques proposant leurs produits et savoir-faire</li>
        <li class="text-bloc-list-item">Un espace pour découvrir des artisans et petites marques prometteuses</li>
        <li class="text-bloc-list-item">4 tables rondes par jour sur des thèmes tendances animées par la rédaction</li>
        <li class="text-bloc-list-item">Ateliers & coaching déco gratuits</li>
    </ul>
	<?php 
		$exposition_physique_link = get_site_url() . "/exposition_physique";
		$link_target = "_blank";
		$link_txt = "DÉCOUVRIR LE PLAN DU SALON";
		if( (!empty($parent_cat->slug) && $parent_cat->slug == 'exposition-virtuelle') || (!empty($current_cat->slug) && $current_cat->slug == 'conseils-deco') ){
			$link_target = "_self";
			$link_txt = "DÉCOUVRIR";
		}
	?>
	<a class="btn btn-ghost" href="<?php echo $exposition_physique_link; ?>" target="<?php echo $link_target; ?>">
		<?php echo $link_txt; ?>
	</a>
</div>