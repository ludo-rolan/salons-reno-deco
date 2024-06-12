<div class="text-bloc has_bg red">
	<h2 class="default-title">L'exposition physique</h2>
	<p class="text-bloc-content bold">Le Salon de la Rénovation by Karine & Gaelle, se tiendra du jeudi 25 au dimanche 28 mars 2021, Hall 5.1, Paris, Porte de Versailles.</p>
	<p class="text-bloc-content">Quatre jours pour découvrir des solutions et produits pour tous vos projets de travaux et d’aménagement :</p>
	<ul class="list-unstyled text-bloc-list bold">
		<li class="text-bloc-list-item">150 exposants dans les univers de l’aménagement, le bâtiment, l’énergie et l’extérieur</li>
		<li class="text-bloc-list-item">Conseils d’aménagement et de décoration avec Karine & Gaelle</li>
		<li class="text-bloc-list-item">Conférences organisées par la rédaction de Maison&Travaux</li>
		<li class="text-bloc-list-item">Coachings gratuits avec des architectes</li>
		<li class="text-bloc-list-item">Exposition des produits et services lauréats des Prix Maison&Travaux, Mon Jardin Ma Maison et Le Journal de la Maison</li>
	</ul>
	<?php 
		$exposition_physique_link = get_site_url() . "/exposition_physique";
		$link_target = "_blank";
		$link_txt = "DÉCOUVRIR LE PLAN DU SALON";
		if( (!empty($parent_cat->slug) && $parent_cat->slug == 'exposition-virtuelle') || (!empty($current_cat->slug) && $current_cat->slug == 'conseils-experts') ){
			$link_target = "_self";
			$link_txt = "DÉCOUVRIR";
		}
	?>
	<a class="btn btn-ghost" href="<?php echo $exposition_physique_link; ?>" target="<?php echo $link_target; ?>">
		<?php echo $link_txt; ?>
	</a>
</div>