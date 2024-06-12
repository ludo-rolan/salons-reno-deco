<?php 
// list tags et and sublist tags
$list_tags = array(
	'div' => 'div',
	'ul'  => 'li',
	'ol'  => 'li',
	'dl'  => 'dt'
);

$seo_tag = get_param_global('most_popular_seo_tag')	? get_param_global('most_popular_seo_tag') : 'div';

$use_seo_js_link_data = get_param_global('use_seo_js_link_most_popular', false) ? 'href="javascript:void(0);" data-' :'';

$thumb_size = apply_filters('thumb_size_most_popular','rw_small');
$most_popular_cls = ( isset($is_carousel) && $is_carousel ) ? 'slick' : 'normal';
?>
<<?php echo $seo_tag; ?> class="items-small-list most_popular <?php echo $most_popular_cls; ?>">
	<?php 
	$j = 1 ;

    foreach ($most_popular as $value) {
    	global $post;
    	if($n<$j){
    		break;
    	}
    	if(is_object($value)){
	        $post =	$value ; 
	        $post_id = $post->ID; 
    	}else{
	        $post_id =	$value ;
			$post = get_post($post_id);    		
    	}
		if(empty($value) || $post->post_status != 'publish'){
			continue ;
		}  
		$j++ ;
		$link = get_permalink($post_id);
		$category=get_the_category($post_id);
		$cat_id=$category[0]->term_id;
		$title = '' ;
		$size_text_title = apply_filters('size_mini_text_for_lines',30);
		
		if($post->post_title!=""){
			$title =mini_text_for_lines(strip_tags($post->post_title),$size_text_title , 2);
		?>
		<<?php echo $list_tags[$seo_tag];?> class="thumbnail-item">
			<?php
			$category = get_menu_cat_post($post);
			$class_name = "";
			if(strlen(get_cat_name($category->term_id))>28){
				$class_name = "link2lines";
			}
		
			?>
			

			<div class="media">
				<div class="media-left">

					<?php if(get_param_global('seo_bubble_links')) :?>
					<a  target="_self" title="<?php echo $post->post_title;?>" <?php echo $use_seo_js_link_data;?>href="<?php echo $link;?>">
						<?php echo get_the_post_thumbnail($post->ID, $thumb_size, array('alt' => $post->post_title)); ?>
					</a>
					<?php else: ?>

					<span  data-target="_self" title="<?php echo $post->post_title;?>" data-href="<?php echo $link;?>">
						<?php echo get_the_post_thumbnail($post->ID, $thumb_size, array('alt' => $post->post_title)); ?>
					</span>
					<?php endif; ?>
					<?php do_action('after_thumb_most_popular');?>
				</div>
				<div class="media-body">
					<?php 
						if(!get_param_global('hide_top_populare_infos')){ 
							if(get_param_global('top_populare_cat_to_date')){ 
					?>
								<a class="info_link" href="#">
									<span class="info_cat"> 
									<?php echo get_the_date(); ?>
									</span>
								</a>
								<?php
							}else{
							   	$cat_link = get_most_popular_cat_link($post, 'info_cat', array('lines'=>array(30 , 2)));			
								echo apply_filters('show_categories_date_most_popular', $cat_link);				
							}
						}
					 ?>	

					
					<a target="_self" title="<?php echo $post->post_title;?>"  href="<?php echo $link;?>">
						<span class="title-item-small <?php echo $class_name;?>"><?php echo $title;?></span>
					</a>
					<?php 
					if(get_param_global('show_popular_date_below_title') && !get_param_global('hide_top_populare_infos')){
						echo '<span class="date">'. get_the_date() .'</span>';
					}
					?>
					<?php do_action('after_body_most_popular' , $post); ?>
				</div>
		    </div>
		</<?php echo $list_tags[$seo_tag];?>><!-- end .thumbnail-item -->
		<?php
		}
	}
	 ?>
</<?php echo $seo_tag; ?>><!-- end .items-small-list.most_popular -->


