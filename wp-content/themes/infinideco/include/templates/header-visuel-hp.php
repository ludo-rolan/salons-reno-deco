<?php

$imge_bg = get_option("visual_hdr_image","");
if (!empty($imge_bg)) {
	$imge_bg = wp_get_attachment_image_src($imge_bg, 'full')[0];
}
$vh_titre = get_option("visual_hdr_title","");
$vh_date = get_option("visual_hdr_date","");
$vh_adresse = get_option("visual_hdr_adresse","");
?>


<div class="header-visuel home" style="background-image:url(<?php echo $imge_bg; ?>)">
	<div class="container header-visuel-container">
		<div class="media header-visuel-content">
			
			<div class="media-body media-middle">
				<?php if( !empty($vh_titre) ) { ?>
					<div class="header-visuel-text"><?php echo $vh_titre; ?></div>
				<?php } ?>
				<?php if( !empty($vh_date) ) { ?>
						<div class="header-visuel-date"><?php echo $vh_date; ?></div>
				<?php } ?>
				<?php if( !empty($vh_adresse) ) { ?>
					<div class="header-visuel-title"><?php echo $vh_adresse; ?></div>
				<?php } ?>
			</div>
		</div>

	</div>
</div>