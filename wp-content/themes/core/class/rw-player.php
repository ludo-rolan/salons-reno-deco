<?php
/**
* 
*/
class RW_Player {
	

	private static $_instance;

	function __construct(){
		# code...
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Player();
		}
		return self::$_instance;
	}
	
	static function init_video_js(){

		global $video_js_included;
		if(!$video_js_included){
			$video_js_included = true ;
			wp_enqueue_script('reworldmedia-videojs', RW_THEME_DIR_URI . '//assets/video-js/video'.JS_EXT , array('jquery'), '', true);
			wp_enqueue_script('reworldmedia-videojs-ga', RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.ga.min.js', array('reworldmedia-videojs'), '', true);
			wp_enqueue_script('reworldmedia-videojs-ads', RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.ads'.JS_EXT, array('reworldmedia-videojs'), '', true);
			wp_enqueue_style('reworldmedia-videojs-css' , RW_THEME_DIR_URI . '//assets/video-js/video-js.css');
			wp_enqueue_style('reworldmedia-videojs-css-ads' , RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.ads.css');
			wp_enqueue_script('reworldmedia-videojs-youtube', RW_THEME_DIR_URI . '/assets/videojs-plugins/youtube'.JS_EXT, array('reworldmedia-videojs'), CACHE_VERSION_CDN, true);
		}
	}

	static function init_jwplayer(){
		global $jwplayer_included;
		if(!$jwplayer_included){
			$jwplayer_included = true ;
			$template_dir_uri =  get_template_directory_uri() ;
			wp_enqueue_script('reworldmedia-jwplayer', $template_dir_uri.'/assets/jwplayer/jwplayer.js', array('jquery'), CACHE_VERSION_CDN, true );
			wp_enqueue_script('reworldmedia-jwplayer_html5', $template_dir_uri.'/assets/jwplayer/jwplayer.html5.js', array('reworldmedia-jwplayer'), CACHE_VERSION_CDN, true );
		}

	}

	static function init_jwplayer7(){
		global $jwplayer7_included;
		//wp_enqueue_script('reworldmedia-jwplayer7',  SITE_SCHEME . '://content.jwplatform.com/players/o54lqcmW-NYeljKzK.js', array('jquery') );
		if(!$jwplayer7_included){
			wp_enqueue_script('reworldmedia-jwplayer7', RW_THEME_DIR_URI . '/assets/jwplayer7/jwplayer.js', array('jquery'), '', true);
			$jwplayer7_included = true;
		}
	}

	static function init_videojs_vast(){
		$id_pub = get_param_global('id_pub');
		if(isset($id_pub['type']) && $id_pub['type'] == 'vpaid' or is_dev('pouvoir_diffuser_vast_vpaid_135592715') ){
			if(is_dev()){
				wp_enqueue_script('videojs_vast_vpaid', RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs-vast-vpaid/videojs_4.vast.vpaid'.JS_EXT, array('reworldmedia-videojs-ads'), CACHE_VERSION_CDN, true);

			}else{
				wp_enqueue_script('videojs_vast_vpaid', RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs-vast-vpaid/videojs_4.vast.vpaid.min.js', array('reworldmedia-videojs-ads'), CACHE_VERSION_CDN, true);
				
			}
			wp_enqueue_style('reworldmedia-videojs-css-vast' , RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs-vast-vpaid/videojs.vast.vpaid.min.css', array() , CACHE_VERSION_CDN);
		}else{
			wp_enqueue_style('reworldmedia-videojs-css-vast' , RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.vast.css', array() , CACHE_VERSION_CDN);
			wp_enqueue_script('reworldmedia-videojs-vast-client', RW_THEME_DIR_URI . '/assets/videojs-plugins/vast-client'.JS_EXT, array('reworldmedia-videojs-ads'), CACHE_VERSION_CDN, true);
			wp_enqueue_script('reworldmedia-videojs-vast', RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.vast'.JS_EXT, array('reworldmedia-videojs-vast-client'), CACHE_VERSION_CDN, true);	
		}
	}

	static function init_scripts_playlist(){
		if(is_dev('mise_en_place_jw7_151445890')){
			init_jwplayer7();
		}else if( is_dev( 'passage_du_player_sur_jw6_111776366' ) ){
			init_jwplayer();
		}else{
			init_video_js();
			init_videojs_vast();
		}
		wp_enqueue_script('playlist_post', get_template_directory_uri().'/assets/javascripts/playlist_post'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN, true );
	}

}