        </div>
		<?php
		do_action('before_megabanner_bottom');
		?>
                <div id="megabanner_bottom" class="container" >
                <?php	
				$sidebar = apply_filters('filter_all_sidebar', 'footer-pub') ;

                if (is_active_sidebar($sidebar)) { 
                    dynamic_sidebar($sidebar); 
                }
                ?>
                </div>
            <?php do_action('just_before_footer') ?>
            <?php do_action('display_footer'); ?>
	
    </div><!-- #page -->
<?php 
	do_action('before_wp_footer');
	wp_footer();
	do_action('after_wp_footer');
	 ?>
</body>
</html>