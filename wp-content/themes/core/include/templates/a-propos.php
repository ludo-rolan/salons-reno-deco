<?php if (get_query_var('page_name') == 'exposition_physique') { ?>
	<div class="text-bloc has_bg_v2 blue">
		<h2 class="default-title">à propos</h2>
		<p>
		<?php
		$special_content = get_option('special_content');
		echo $special_content['data_apropos_evenement'];
		?>
		</p>
	</div>
<?php } else { ?>
	<div class="text-bloc has_bg blue">
		<h2 class="default-title">à propos</h2>
		<p>
		<?php
		$special_content = get_option('special_content');
		echo $special_content['data_apropos'];
		?>
		</p>
	</div>
<?php } ?>