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
				<!-- À attaquer -->
				<div class="menu-site hidden-xs hidden-sm">
					<?php
						do_action('before_header_nav');
						echo get_the_menu_header_v2();
						do_action('extra_menu_header_name');
						do_action('end_nav_menu');
					?>	
					<div class="lang-wrapper">
						<?php
							include( locate_template( 'include/templates/lang-selection.php' ) );
						?>
					</div>
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
		<div class="submit_search_btn mobile" data-toggle="modal" data-target="#MainSearch"></div>
	</div>
</header>
<div class="modal fade search" id="MainSearch" tabindex="-1" role="dialog" aria-labelledby="MainSearch">
	<div class="modal-dialog modal-search-container" role="document">
		<div class="modal-content">
			<div class="modal-body text-center popup_body"  >
                <div data-dismiss="modal" class="dismiss_btn" >x</div>
				<img height="90" src="<?php echo STYLESHEET_DIR_URI.'/assets/images-v3/logo-white.svg' ?>" />
                <h3 class="title">Tapez votre recherche</h3>
				<form action="/" class="nl_footer_form modal-search" style="border:1px solid #000" id="form_search">
					<input required="required" type="text" placeholder="entrez votre recherche" id="searchbox" name="s" value="" autocomplete="off">
					<input class="submit-inline" type="submit" value="Recherche">
				</form>
                
			</div>
		</div>
	</div>

</div>
<?php do_action('after_header'); ?>