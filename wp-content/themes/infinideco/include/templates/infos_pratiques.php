<?php 
	$ip_color_bg = get_option("texte_statique_ep_ip_color","");
	$ip_title = get_option("texte_statique_ep_ip_title","");
    $ip_intro = get_option("texte_statique_ep_ip_intro","");
    $ip_map = get_option("texte_statique_ep_ip_map","");
    $ip_texte = get_option("texte_statique_ep_ip_texte","");
    $ip_cta_texte = get_option("texte_statique_ep_ip_cta_texte","");
    $ip_cta_link = get_option("texte_statique_ep_ip_cta_link","");
?>
<div class="text-bloc red" data-hash-target="#infos_pratiques" style="background-color:<?php echo $ip_color_bg; ?>">
	<h2 class="default-title"><?php echo $ip_title;?></h2>
	<p class="text-bloc-content bold">
		<?php echo $ip_intro;?>
	</p>

	<div id="exposition_map">
		<?php echo $ip_map;?>
	</div>

	<div class="text-bloc-content">
		<?php echo $ip_texte;?>
	</div>
	<?php if( !empty($ip_cta_texte) ) { ?>
		<a class="btn btn-ghost" href="<?php echo $ip_cta_link;?>" target="_blank"><?php echo $ip_cta_texte;?></a>
	<?php } ?>
</div>



<!-- backup
<div class="text-bloc red" data-hash-target="#infos_pratiques">
	<h2 class="default-title">INFOS PRATIQUES</h2>
	<p class="text-bloc-content bold">
	Le Salon Art&Déco se tient du jeudi 14 au dimanche 17 octobre 2021 de 10h à 19h.<br>
	Grande Halle de la Villette, Paris 19ème arrondissement.
	</p>

	<div id="exposition_map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2623.3295259908177!2d2.3896127160237364!3d48.890056806429975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66d159e40e111%3A0x37fa2bacfcd5dd17!2sGrande%20Halle%20de%20la%20Villette!5e0!3m2!1sfr!2sfr!4v1632323814319!5m2!1sfr!2sfr" width="600" height="230" frameborder="0" style="border:0; width: 100%;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
	</div>

	<ul class="list-unstyled text-bloc-list">
		<li class="bold">Comment y accéder ?</li>
		<li><strong>Métro :</strong> ligne 5 station Porte de Pantin</li>
		<li><strong>Bus :</strong> 75 et 151, arrêt Porte de Pantin (Grande Halle)</li>
		<li><strong>Tram :</strong> 3b, arrêt Porte de Pantin (Grande Halle)</li>
		<li><strong>Voiture :</strong> Parking payant autour du Parc de la Villette à la cité de la Musique (Vinci)</li>
	</ul>
	<a class="btn btn-ghost" href="#" target="_blank">DÉCOUVRIR LE PLAN DU SALON</a>
</div> -->