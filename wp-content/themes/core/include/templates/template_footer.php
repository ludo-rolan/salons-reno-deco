<footer class="clearfix">
	<?php do_action('start_footer'); ?>
    <div class="container ">
    	
            <?php do_action('side-bar_before-footer'); ?>

        	<div class="row">
            
             <?php	
				$sidebar = apply_filters('filter_all_sidebar', 'footer') ;
                if (is_active_sidebar($sidebar)) { 
                    dynamic_sidebar($sidebar); 
                }
            ?>
			</div>
            
            <?php do_action('side-bar_after-footer'); ?>
            

    </div>
    <?php do_action('end_footer'); ?>
</footer>


<?php do_action('just_after_footer'); ?>