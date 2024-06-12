<?php

add_filter( 'wpseo_robots', 'rw_remove_robots_meta',999,1 );
add_action( 'wp_head', 'rw_add_meta_robots_google_news',12);

function rw_add_meta_robots_google_news(){
	if (is_single(  ) || is_category() || is_home() ) {
		$seo_max_option = get_option('seo_meta_max');
		if (empty($seo_max_option['max_snippet'])) {
			$seo_max_option['max_snippet'] = SEO_META_MAX_SNIPPET;
		}
		if (empty($seo_max_option['img_preview'])) {
			$seo_max_option['img_preview'] = SEO_META_IMG_PREVIEW;
		}
		if (empty($seo_max_option['video_preview'])) {
			$seo_max_option['video_preview'] = SEO_META_VIDEO_PREVIEW;
		}
		echo '<meta name="robots" content="max-snippet:'.$seo_max_option['max_snippet'].'">';
		echo '<meta name="robots" content="max-image-preview:'.$seo_max_option['img_preview'].'">';
		echo '<meta name="robots" content="max-video-preview:'.$seo_max_option['video_preview'].'">';
		 
	}
}

function rw_remove_robots_meta($meta){
	if (!empty($meta)) {
		$v = 'noodp';
		$meta_data = explode(',',strtolower($meta));
		$key = array_search($v, $meta_data);
		if ($key !== false) {
			unset($meta_data[$key]);
			$meta = implode(',',$meta_data);
		}
	}
	return $meta;
}