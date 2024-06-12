<?php
global $post;
get_header();
?>
<div class="exposant-body">
	<?php
	while (have_posts()) {
		the_post();
		if( has_excerpt() ){ 
			?>
				<div class="exposant-excerpt"><?php the_excerpt(); ?></div>
			<?php 
		} 
		?>
		<div id="exposant-content">
			<?php the_content(); ?>
		</div>
		<?php
	}
	do_action('show_product');

	$video = get_post_meta($post->ID , 'video_live_shopping', true);
	if( !empty($video) ){
		?>
			<div class="exposant-live-shopping">
				<?php 
					$video_height = rw_is_mobile() ? '200' : '550';
					echo do_shortcode('[fpvideo mediaid="'.$video.'" autoplay="no" height="'.$video_height.'"]'); 
				?>
			</div>
		<?php
	}
	?>

	<div class="exposant-forms">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="default-title">Contact</div>
				<div class="section_subtitle">Formulaire de contact</div>
				<?php 
					$id_form_exposant = get_option('id_formulaire_exposant' , 3);
					echo do_shortcode('[ninja_forms_display_form id='.$id_form_exposant.']');
				?>
			</div>
			<div class="col-xs-12 col-md-6 <?php echo rw_is_mobile() ? 'stick_exposant_phone': ''; ?>">
				<?php 
				$phone_number = get_post_meta($post->ID , 'phone_exposant', true);
				if( empty($phone_number) ) $phone_number = "";
				if( rw_is_mobile() ){ 
				?>
					<a href="tel:<?php echo $phone_number; ?>" class="btn btn-info btn-block">appelez-nous</a>
				<?php } else { ?>
					<div class="form-group">
						<div class="section_subtitle">Par téléphone</div>
						<button class="btn btn-info btn-block" id="show_exposant_phone">Afficher le numéro</button>
						<input type="text" class="form-control hidden" id="exposant_phone" value="<?php echo $phone_number; ?>" readonly>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>