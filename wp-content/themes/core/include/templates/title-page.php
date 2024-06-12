<?php
	global $wp_query;
	if(is_home()){
?>
	<h1 class="title_home"><?php
	$title_home = wp_title();
	echo $title_home ; 
	 ?></h1>
<?php 
}elseif( is_single() ){
	?>
		<h1 class="title" itemprop="headline">
	        <?php the_title(); ?>
	    </h1>
	    <?php do_action('exactly_after_title');?>
    <?php
        $cat =  RW_Category::get_menu_cat_post() ;
    
    ?>
    	<meta itemprop="articleSection" content="<?php echo $cat->name ; ?>" />
<?php }?>