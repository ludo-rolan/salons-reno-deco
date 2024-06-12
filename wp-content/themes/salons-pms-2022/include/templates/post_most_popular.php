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

$thumb_size = apply_filters('thumb_size_most_popular','rw_medium_second');
$most_popular_cls = ( isset($is_carousel) && $is_carousel ) ? 'slick' : 'normal';
$most_popular_cls .= ( isset($is__video_podcast) && $is_video_podcast ) ? 'video_podcast' : '';
?>
<<?php echo $seo_tag; ?> class="items-small-list most_popular  <?php echo $most_popular_cls; ?>">
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
		<hr>
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
					<?php
						if(isset($is_video_podcast) && $is_video_podcast){
							echo '<i class="fa-solid fa-circle-play"></i>';
						}
					?>
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
								$post_cat= get_the_category($post->ID);
								$array_size = count($post_cat);
								$cat_meta  = get_option('category_'.$post_cat[$array_size-1]->term_id);
								$cat_color = $cat_meta['color'];
								$parent_cat= get_cat_slug($post_cat[$array_size-1]->parent);
								?>
								<span  class="info_link" data-href="<?php echo get_category_link($post_cat[$array_size-1])?>" >		
									<span class="info_cat <?php echo $parent_cat ?> " 
										style="<?php echo (isset($cat_color)) ? "color:$cat_color":"";?>"
									>
										<?php echo $post_cat[$array_size-1]->name; ?>
									</span>
								</span>
								<?php		
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
	 <hr>
</<?php echo $seo_tag; ?>><!-- end .items-small-list.most_popular -->


