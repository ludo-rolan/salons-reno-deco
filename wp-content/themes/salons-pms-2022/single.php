<?php
global $has_gallery, $has_video, $is_folder ;
$current_cat = RW_Category::get_post_category_from_url();
if(isset($is_parent_cat) && $is_parent_cat){
	$parent_cat = $current_cat ;
}else{
	if(isset($current_cat->parent) && $current_cat->parent) {
		$parent_cat = get_term($current_cat->parent, 'category');
	}
}

$cat_slug = '';
if(isset($parent_cat) && isset($parent_cat->slug) && $parent_cat->slug!="")
	$cat_slug=$parent_cat->slug;
else if(isset($current_cat) && $current_cat) {
	$cat_slug=$current_cat->slug;
}
$id_post = get_the_ID() ; 
$title_related_post =  __("Lire plus d'articles",REWORLDMEDIA_TERMS )  ;
	
$number_posts = apply_filters( 'similar_posts_count' , 3 );


if ( ($show_related =  get_param_global('show_similar_posts')) && $show_related!='no' ){
	$posts_related = get_similar_posts( $current_cat , $number_posts , $id_post ) ; 
} else {
	$posts_related = array();
}

get_header(); 



//add_script_zoombox();
$is_quizz=false;
$cat_lists=get_the_category( $id_post );


$has_gallery = Rw_Post::get_gallery_type();

if($has_gallery){
	$has_gallery = ($has_gallery == 'classical' || $has_gallery == 'diapo_popup') ? true : false;
}
?>

<?php do_action('before_single_primary_content');?>

<?php $primary_cls = apply_filters( 'single_primary_cls', 'site-content' );?>
<?php do_action('before_content_page_single');?>
<?php 
do_action('just_after_post_v2'); 
$bootstrap_class = apply_filters( 'bootstrap_class_content_single', 'col-xs-12 col-md-8 col-lg-8' );
include(RW_THEME_DIR."/include/templates/title-page.php");
?>
<div id="content" role="main" class="site-content <?php echo $bootstrap_class;?> pull-left">
	<div id="results" class="post">
		<?php
		while (have_posts()) {
			the_post();

			$has_video = RW_Post::page_has_video();

			
			include(locate_template('include/templates/single-article.php'));
		
			if ( !$has_gallery && !empty($posts_related) && get_param_global('show_posts_related')!='no') {
				if(isset($site_config['show_similar_posts']) && $site_config['show_similar_posts']){
					include(locate_template('include/templates/single-more-articles-v2.php'));
				}
			} 

			include(locate_template('include/templates/read-more-block.php'));
			if(!$has_gallery){
				do_action('before-block-comments');
				if(!get_param_global('hide_comment_template')) {
					comments_template('', true);
				}
				do_action('after-block-comments');
			}
		}
		?>
		
            <?php if( !$is_quizz && !get_param_global('hide_schema_organization')) { ?>
            <div class="schema_organization" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
                <span itemprop="name"><?php bloginfo('name'); ?></span>
            </div>   
            <?php } ?>
	</div>
	<?php do_action('after_content_page_single');?>
</div>
<?php do_action('after_single_primary_content');?>
<?php if(!$has_gallery && !get_param_global('hide_single_sidebar') ) get_sidebar(); ?>
<?php get_footer(); 