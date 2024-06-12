<?php
global $is_sidebar;
$is_sidebar = true;
$sidebar = apply_filters('main_sidebar', 'sidebar-1');
if ($sidebar && is_active_sidebar($sidebar)) { ?>
	<div id="blockRight" class="<?php echo apply_filters('sidebar_classes','widget-area col-xs-12 col-sm-12 col-md-4 col-lg-4 pull-right') ?>" role="complementary">
		<div class="list-ms-item clearfix">
			<?php 
			do_action('before_right_sidebar');
			dynamic_sidebar( $sidebar ); ?>
		</div>
	</div>
<?php } 
$is_sidebar = false;

do_action('rew_block_right');