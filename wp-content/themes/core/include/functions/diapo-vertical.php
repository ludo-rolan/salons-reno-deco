<?php
add_action('wp', 'apply_diapo_vertical', 1);

function apply_diapo_vertical(){
	global $post, $site_config, $vertical_diapo_dfp_count;
	$vertical_diapo_dfp_count = 0;

	add_filter('the_content', 'diapo_before_content');

	// add_action('before_diapo_vertical_img', 'diapo_vertical_banniere');
	// add_action('after_diapo_vertical_img', 'diapo_vertical_paves');
	// add_filter('dfp_formats_lazyloading', 'diapo_vertical_dfp_formats');
	// add_filter('dfp_v2_disableInitialLoad', 'diapo_vertical_dfp_formats');
	// add_filter('dfp_tag_keys', 'diapo_vertical_dfp_formats');
}

function diapo_before_content($content){
	$post_gallery = get_the_gallery();
	return $post_gallery.str_replace($post_gallery, '', $content);
}

function diapo_vertical_banniere(){
	global $vertical_diapo_dfp_count;
	?>
	<div class="diapo_vertical_banner">
		<?php 
			if( $vertical_diapo_dfp_count==0 ){
				echo do_shortcode("[dfp_v2 id='native']");
			}else{ 
				$vertical_diapo_dfp_count ++;
				echo do_shortcode("[dfp_v2 id='vertical_diapo_pave_".$vertical_diapo_dfp_count."']");
			}
		?>
	</div>
	<?php
}
function diapo_vertical_paves(){
	global $vertical_diapo_dfp_count;
	?>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="diapo_vertical_pave">
					<?php 
						$vertical_diapo_dfp_count++;
						echo do_shortcode("[dfp_v2 id='vertical_diapo_pave_".$vertical_diapo_dfp_count."']"); 
					?>
				</div>
			</div><!-- .col -->

			<div class="col-xs-12 col-md-6">
				<div class="diapo_vertical_pave">
					<?php 
						$vertical_diapo_dfp_count++;
						echo do_shortcode("[dfp_v2 id='vertical_diapo_pave_".$vertical_diapo_dfp_count."']"); 
					?>
				</div><!-- .col -->
			</div><!-- .col -->
		</div><!-- .row -->
	<?php
}

function diapo_vertical_dfp_formats($formats){
	if( $diapo_count = RW_Post::count_gallery_items() ){
		$formats[] = 'native';
		// Each diapo element contain x3 ads
		for( $i=1; $i < $diapo_count*3; $i++ ){
			$formats[] = 'vertical_diapo_pave_'.$i;
		}
	}
	return $formats;
}

add_filter('before_single_primary_content', function(){
	add_filter('post_gallery', 'customize_post_gallery', 10, 3);
});

function customize_post_gallery($null, $attr = [], $instance) {
	global $printed_top_gallery, $current_gallery_images ,$post;
	$current_gallery_images = get_attachements_post_array($attr);
	$content_diapo = "";

	if (!$printed_top_gallery) {
		$printed_top_gallery = true;
		$attachments = get_customize_post_gallery($post, $attr ) ;
		include(locate_template('include/templates/template_diapo_vertical.php'));
	}
	return $content_diapo;
}

function get_attachements_post_array($attr){
	global $post,$current_gallery_images;
	$current_gallery_images = array();

	$ids=explode(',' , $attr['ids']);
	// we need to keep the order like in post__in!
	$args = array('post_type' => 'attachment', 'post__in' => $ids, 'orderby' => 'post__in' , 'posts_per_page' => -1);
    // the widget needs it
	
	$current_gallery_images = get_posts($args);

	return $current_gallery_images;
}

function get_custom_url_by_ids($ids){
	global $wpdb;
	$ids = implode(',', $ids);
	$items = $wpdb->get_results( 'select SQL_CACHE post_id, meta_value from '.$wpdb->prefix.'postmeta WHERE meta_key="custom_url" AND post_id IN('.$ids.')', ARRAY_A );
	$result = [];
	foreach ($items as $key => $value) {
		# code...
		if( isset($value['post_id']) && isset($value['meta_value']) ){
			$result[$value['post_id']] = $value['meta_value'];
		}
	}
	return $result;
}

function get_customize_post_gallery($post, $attr = array() , $forse = false) {
	global $site_config_js, $current_gallery_images ,$post;

	$meta_key= 'post_gallery_cache_' . md5($attr['ids']) ;
	if(!$forse){
		$data = get_post_meta($post->ID, $meta_key,true);
		if($data){
			return $data ;
		}	
	}

	$current_gallery_images = get_attachements_post_array($attr);

	$content_diapo = "";

	$format_thumb_diapo= 'gallery_thumb';

	$id_thumbs = explode(",", $attr['ids']);

	$attachments = array();
	
	$custom_urls = get_custom_url_by_ids($id_thumbs);
	
	foreach ($current_gallery_images as $image_gallery) {
		$id_thumb = $image_gallery->ID;			

			$size = array(
			'thumbnail' =>  'rw_thumb', 
	    	'medium' =>  'rw_medium', 
	    	'large' =>  'rw_large', 
	    	'full' =>  'rw_full', 
	    	'image_diaporama' =>  'image_diaporama', 
	    	'thumbnail_diaporama' =>  'thumbnail_diaporama', 
	    	'gallery_full' =>  apply_filters( 'size_diaporama_full', 'rw_gallery_full') , 
	    	'gallery_thumb' => 'rw_gallery_thumb', 
	    );


		$get_the_content = $image_gallery->post_content;
		
	    $format_gallery_thumb = '';

		$format_gallery_thumb = wp_get_attachment_image_src($id_thumb, $size[ $format_thumb_diapo] );
	    		   
		$attachment_size = get_param_global('gallery_big_image_zise', 'gallery_full');
		$size_diaporama_full = wp_get_attachment_image_src($id_thumb, $size[ $attachment_size] ) ;

	    $attachment = array(
	    	'id' => $id_thumb, 
	    	'thumbnail' => $size_diaporama_full, 
	    	'medium' => $format_gallery_thumb, 
	    	'large' => $size_diaporama_full, 
	    	'full' => $size_diaporama_full, 
	    	'image_diaporama' => $size_diaporama_full, 
	    	'thumbnail_diaporama' => $size_diaporama_full, 
	    	'gallery_full' => $size_diaporama_full , 
	    	'gallery_thumb' => $format_gallery_thumb, 
	    	'post_content' => $image_gallery->post_content
	    );

		$attachment['data'] = array();
		if(isset($shortcode) && !empty($shortcode['attr']) ){
			foreach ($shortcode['attr'] as $key => $value) {
				$attachment['data'][$key] = $value ;
			}

		}
        // make the image on the diapo clickable.
        $custom_url = isset($custom_urls[$id_thumb]) ? $custom_urls[$id_thumb] : '';

        if ( ! empty( $custom_url ) ) {
            $attachment[ 'data' ][ 'custom_url' ] = $custom_url;
        }
        array_push( $attachments, $attachment );
	}

	update_post_meta($post->ID, $meta_key, $attachments);

	return $attachments ;
}


function get_the_gallery() {
	global $post;
	if (preg_match(HAS_GALLERY_REGEXP, $post->post_content, $matches)) {
		return $matches[0];
	}
	return false;
}