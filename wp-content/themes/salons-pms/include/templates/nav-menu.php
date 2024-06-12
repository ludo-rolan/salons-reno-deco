<?php 
	do_action('before_header'); 
	$home_url = esc_url(apply_filters('logo_home_url', home_url('/')));
	$page_name = get_query_var('page_name');
	$header_variant = 'v1';
?>
<header class="<?php echo $header_variant; ?>">
	<div class="container">
			<?php
				if( $header_variant == 'v1' ){
					$img_logo = apply_filters('default_logo_site', STYLESHEET_DIR_URI . '/assets/images-v3/main-logo-white.svg?v=' . CACHE_VERSION_CDN);
			?>
				<a class="navbar-brand" href="<?php echo $home_url; ?>">
					<?php if ($img_logo): ?>
							<img src="<?php echo $img_logo;?>" class="main-logo img-responsive" alt="<?php echo apply_filters('alt_logo_header', get_bloginfo('name'));?>" />
					<?php endif; ?>
				</a>
				<div class="menu-site hidden-xs hidden-sm">
					<?php
						do_action('before_header_nav');
						echo get_the_menu_header_v2();
						do_action('extra_menu_header_name');
						do_action('end_nav_menu');
					?>	
					<div class="submit_search_btn" data-action="search" data-toggle="modal" data-target="#MainSearch"></div>
				</div>
			<?php }elseif ($header_variant == 'v2'){ ?>
				<div class="navbar_elem_wrapper">
					<a class="navbar-brand" href="<?php echo $home_url; ?>">
						<img src="<?php echo STYLESHEET_DIR_URI . '/assets/images-v3/main-logo-v2.svg?v=' . CACHE_VERSION_CDN; ?>" class="main-logo img-responsive" alt="<?php echo apply_filters('alt_logo_header', get_bloginfo('name'));?>" />
					</a>
					<div class="menu-site hidden-xs hidden-sm">
						<?php echo get_the_menu_header_v2(); ?>	
						<div class="submit_search_btn" data-toggle="modal" data-target="#MainSearch"></div>
					</div>
				</div>
			<?php } ?>
		<button type="button" class="navbar-toggle pull-right">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
</header>
<div class="modal fade search" id="MainSearch" tabindex="-1" role="dialog" aria-labelledby="MainSearch">
	<div class="modal-dialog modal-search-container" role="document">
		<div class="modal-content">
			<div class="modal-body" id="modal-body-search">
				<button type="button" class="close close-search" data-dismiss="modal" aria-label="Close"></button>

				<form id="form_search" class="modal-search">
					<label for="searchbox">Tapez votre recherche</label>
					<span> entrez ici votre recherche... </span>
					<div class="form-group">
						<input type="text" class="form-control header-search-input" placeholder="<?php _e("Entrez ici votre recherche...", REWORLDMEDIA_TERMS ); ?>" id="searchbox" name="s" value="" autocomplete="off" />
						<button type="submit">Recherche</button>
					</div>
				</form>
			</div>
			
		</div>
	</div>
</div>
<?php do_action('after_header'); ?>