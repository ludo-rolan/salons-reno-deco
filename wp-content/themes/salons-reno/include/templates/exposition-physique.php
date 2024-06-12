<div class="text-bloc has_bg red">
	<h2 class="default-title">L'ÉVÈNEMENT</h2>
	<p>
	<?php
		$special_content = get_option('special_content');
		echo $special_content['data_evenement_bloc'];
	?>
	</p>

	<a class="btn btn-ghost" href="<?php echo get_site_url();?>/billetterie" target="_blank">
		BILLETTERIE	</a>
</div>