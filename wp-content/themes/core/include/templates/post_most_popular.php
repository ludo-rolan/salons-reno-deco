<?php 

global $post;

// list tags et and sublist tags
$list_tags = array(
	'div' => 'div',
	'ul'  => 'li',
	'ol'  => 'li',
	'dl'  => 'dt'
);

$seo_tag = get_param_global('most_popular_seo_tag')	? get_param_global('most_popular_seo_tag') : 'div';

$use_seo_js_link_data = get_param_global('use_seo_js_link_most_popular', false) ? 'href="javascript:void(0);" data-' :'';

$thumb_size = apply_filters('thumb_size_most_popular','thumbnail_60_x_60');

$most_popular_cls = ( isset($is_carousel) && $is_carousel ) ? 'slick' : 'normal';
?>

<<?php echo $seo_tag; ?> class="items-small-list most_popular <?php echo $most_popular_cls; ?>">
	<?php 
	$j = 1;

    foreach ($most_popular as $value) {
    	if($n<$j){
    		break;
    	}
    	if(is_object($value)){
	        $post =	$value ; 
	        $post_id = $post->ID; 
    	}else{
	        $post_id =$value ;
			$post = get_post($post_id);    		
    	}
		if(empty($value) || $post->post_status != 'publish'){
			continue;
		}  
		
		$link = get_permalink($post_id);
		$title = '';
		if($post->post_title){
			$j++ ;
			$title = RW_Utils::mini_text_for_lines(strip_tags($post->post_title),30 , 2);
		?>
		<<?php echo $list_tags[$seo_tag];?> class="thumbnail-item">
			
			<div class="media">
				<?php if(get_param_global('seo_bubble_links')) :?>
					<a  target="_self" title="<?php echo $post->post_title;?>" <?php echo $use_seo_js_link_data;?>href="<?php echo $link;?>">
						<?php echo get_the_post_thumbnail($post->ID, $thumb_size, array('alt' => $post->post_title)); ?>
					</a>
				<?php else: ?>
					<span  data-target="_self" title="<?php echo $post->post_title;?>" data-href="<?php echo $link;?>">
						<?php echo get_the_post_thumbnail($post->ID, $thumb_size, array('alt' => $post->post_title)); ?>
						<span class="exposant_title">
							<?php echo $post->post_title; ?>
						</span>
					</span>
				<?php endif; ?>
		    </div>

		</<?php echo $list_tags[$seo_tag];?>><!-- end .thumbnail-item -->
		<?php
		}
	}
	 ?>
</<?php echo $seo_tag; ?>><!-- end .items-small-list.most_popular -->


