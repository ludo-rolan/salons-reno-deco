<?php
$txt_btn_reservation_fr = esc_attr( get_option('txt_btn_reservation_fr' , ''));
$url_btn_reservation_fr = esc_attr( get_option('url_btn_reservation_fr' , ''));
$txt_btn_reservation_en = esc_attr( get_option('txt_btn_reservation_en' , ''));
$url_btn_reservation_en = esc_attr( get_option('url_btn_reservation_en' , ''));
$my_current_lang = apply_filters( 'wpml_current_language', NULL );
?>
<div class="header-visuel">
	<div class="container header-visuel-container">
		<div class="media header-visuel-content">
			<?php if( !empty($visual) ){ ?>
				<div class="media-left media-middle">
					<a href="<?php echo $cta_link; ?>">
						<img class="media-object" src="<?php echo $visual; ?>" alt="<?php echo $post->title; ?>">
					</a>
				</div>
			<?php } ?>
			<div class="media-body media-middle">
				<div class="header-visuel-text"><?php echo $subtitle; ?></div>
				<div class="header-visuel-date"><?php  echo $date;  ?></div>
				<div class="header-visuel-title"><?php echo "PARIS, PORTE DE VERSAILLES";  ?></div>
				<?php 
					if($my_current_lang == 'fr') {
						echo "<a class='btn btn-primary green-hover' href='".$url_btn_reservation_fr."'>".$txt_btn_reservation_fr."<span style='margin-left:5px; font-weight: 900;'>></span></a>";
					}else if ($my_current_lang == 'en') {
						echo "<a class='btn btn-primary green-hover' href='".$url_btn_reservation_en."'>".$txt_btn_reservation_en."<span style='margin-left:5px; font-weight: 900;'>></span></a>";
					}
				?>
			</div>
		</div>

	</div>
</div>