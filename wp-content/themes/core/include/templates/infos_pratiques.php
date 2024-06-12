<?php 
	$plan_salon = get_option('pdf_plan_salon' , '#');
?>
<div class="text-bloc red" data-hash-target="#infos_pratiques">
	<h2 class="default-title">INFOS PRATIQUES</h2>
	<p class="text-bloc-content bold">
		Le Salon de la Rénovation se tient du jeudi 25 au dimanche 28 mars 2021 de 10h à 19h. <br>
		Hall 5.1 du Parc des Expositions de la Porte de Versailles, Paris 15ème.
	</p>

	<div id="exposition_map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2626.4761903851195!2d2.284848215673391!3d48.83005497928478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6707227dc3507%3A0xb95e16217b96a221!2sParis%20Expo%20Porte%20de%20Versailles!5e0!3m2!1sfr!2sfr!4v1598367801727!5m2!1sfr!2sfr" width="600" height="230" frameborder="0" style="border:0; width: 100%;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
	</div>

	<ul class="list-unstyled text-bloc-list">
		<li class="bold">Comment y accéder ?</li>
		<li><strong>Métro :</strong> ligne 12 station Porte de Versailles</li>
		<li><strong>Bus :</strong> 80, arrêt Porte de Versailles, 39 arrêt Desnouettes</li>
		<li><strong>Tram :</strong> 2 et 3a, arrêt Porte de Versailles</li>
		<li><strong>Voiture :</strong> 3 parkings payants autour du Parc d'Exposition</li>
	</ul>
	<a class="btn btn-ghost" href="<?php echo $plan_salon; ?>" target="_blank">DÉCOUVRIR LE PLAN DU SALON</a>
</div>