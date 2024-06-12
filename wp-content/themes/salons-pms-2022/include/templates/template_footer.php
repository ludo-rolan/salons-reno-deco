<footer class="clearfix">
	<?php do_action('start_footer'); ?>
    <div class="container ">
    	
            <?php do_action('side-bar_before-footer'); ?>
            <?php do_action('footer-menu-list'); ?>
            <div class="nl_footer_container row">
                <div class="col-md-3 col-xs-12"></div>
                <?php do_action('footer-nl-form'); ?>
                <div class="menu-footer-img col-xs-12 col-md-3">
                    <img src="<?php echo STYLESHEET_DIR_URI .'/assets/images-v3/Paris_Automotive_Week.svg'?>"/>
                </div>
            </div>

    </div>
    <?php do_action('end_footer'); ?>
</footer>


<?php do_action('just_after_footer'); ?>