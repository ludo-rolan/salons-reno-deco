<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class='no-js'>
<!--<![endif]-->
<head>
<meta charset="utf-8" />
<?php 
global $site_config_js;
$refresh_desactive = false;
if( is_single() && get_post_meta(get_the_ID(), 'refresh_desactive', true) ) $refresh_desactive = true;
if( defined('_IS_REFRESH_') && !_IS_REFRESH_ ) $refresh_desactive = true;
$refresh_desactive = apply_filters('disable_refresh', $refresh_desactive);
if( !$refresh_desactive ){ 
	$refresh_duration = apply_filters('refresh_duration', 300);
	if( $refresh_duration != 0 ){
		$site_config_js['refresh_meta_duration'] = $refresh_duration;;
		$site_config_js['enable_refresh_meta'] = true;
	}else{
		$site_config_js['enable_refresh_meta'] = false;
	}
} 
?>

<?php
do_action('top_head_rw');

$favicon = STYLESHEET_DIR_URI .'/assets/images-v3/favicon.png';

?>
<link rel="shortcut icon" type="image/png" href="<?php echo  $favicon ; ?>">

<?php 
$favicons_formats =  array( '32','48','64','128' );
$favicons_formats = apply_filters('favicons_formats', $favicons_formats);
foreach ($favicons_formats as $format) {
	if(!$favicon = get_param_global('favicon' . $format)){
		$favicon = STYLESHEET_DIR_URI .'/assets/images/favicon'. $format .'.png';
	}

?>
<link rel="icon" href="<?php echo $favicon; ?>" sizes="<?php echo $format. 'x' .$format ; ?>">
<?php

}
?>

<?php do_action('wp_head'); ?>

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/html5.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/modernizr.custom.86039.js" type="text/javascript"></script>
<![endif]-->
	
<?php do_action('wp_head_end'); ?>

</head>

<body <?php body_class(); ?>  >
	<?php
	do_action('rew_top_head'); 
	
	$sidebar = apply_filters('filter_all_sidebar', 'sidebar-after-body') ;
	if (is_active_sidebar($sidebar)) { 
		dynamic_sidebar($sidebar); 
	}

		
	$sidebar = apply_filters('filter_all_sidebar', 'sidebar-header-pub') ;
	if (is_active_sidebar($sidebar)) { 
		?>
		<div class="sidebar-header-pub row">
		<?php
		dynamic_sidebar($sidebar); 
		?>
		</div>
		<?php
	}				

		
	?>
	<div id="page" class="hfeed site"> <!-- to delete id & hfeed site -->
		<?php if( !rw_is_mobile() ){ ?>
			<div id="barreTopInfo" <?php echo get_param_global('barreTopInfo_class'); ?>>
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-md-9">
							<div class="top-pages-menu">
								<?php 
								$menu_id = apply_filters('get_top_menu_name', 'menu_top_pages');
								wp_nav_menu(array(
									'menu' => $menu_id, 
									'theme_location' => 'primary', 
									'menu_class' => 'nav nav-pills pull-left hidden-xs hidden-sm', 
									'child_of' => '$PARENT', 
									'container' => 'ul',
								)); 
								?>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<?php
							echo do_shortcode('[social_links in_header=true]');
							do_action('after_social_links') ;
							?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php 
			do_action('after_barretopinfo');
			get_nav_header();
			do_action("after_nav");
		?>

<div class="container">

<?php
do_action('rew_head','all');