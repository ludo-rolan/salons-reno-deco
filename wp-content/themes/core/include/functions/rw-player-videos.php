<?php
if(is_dev('mise_en_place_jw7_151445890')){
	add_action('wp',function(){
		global $post;
		$ids = array(97282,7286,97288,97284,33177);
		if(is_dev() && $post && !in_array($post->ID, $ids)){
			remove_action('wp_enqueue_scripts', 'init_play_video_home', 2);
		}
	});
	add_filter('new_fpvideo', 'fpvideo_shortcode', 10, 2);
}
function fpvideo_shortcode($fpvideo, $atts) {
	global $display_videojs, $is_sidebar;

	if( isset($_GET['force_mp4']) && $_GET['force_mp4'] ){
		$atts['mediaid'] = "http://videos.ladmobile.fr/3/7/1/20371/hd-20371.mp4";
	}

	$fpvideo = $video_id = '';
	if( (isset($atts['player']) && $atts['player'] == 'videojs') || $display_videojs ){
		$display_videojs = true;
		return $fpvideo;
	}

	global $site_config, $exclude_videos, $is_live_content;
	$template_dir_uri =  RW_THEME_DIR_URI ;
	$params = array() ;

	/*-- Liste des paramettres de shotcodes fpvideo_shortcode ---*/

	// Retirer le son d'une video
	$mutevideo = false;
	if(isset($atts['mutevideo']) && $atts['mutevideo'] != 'no'){
		$mutevideo = true;
	}

	// Playlist vidéo automatique dans la sidebar
	$playlist = 'false';
	$other_videos = array();
	if(isset($atts['playlist']) && strtolower($atts['playlist']) == 'yes'){
		$playlist = 'true';
		// Liste des urls (youtube ou dailymotion) à afficher dans la playlist séparer par des virgules dans la sidebar
		if(isset($atts['othersmediaid']) && !empty($atts['othersmediaid']) ){
			$urls=explode(',', $atts['othersmediaid']);
			foreach ($urls as $key => $value) {
				$other_videos[] = get_video_properties($value);
			}
		}else{
			$other_videos = 'false';
		}
	}

	// permets de nous dire que c'est une video qui existe dans la sidebare pour le play-to-scroll playlist article et player bas
	
	$right_video = false;
	if($is_sidebar){
		$right_video = true;
	}

	// Rejouer la vidéo
	$loop = 'false';
	if(isset($atts['loop']) && strtolower($atts['loop']) == 'yes'){
		$loop = 'true';
	}

	//Recharger le player
	$autoloop = 'false';
	if(isset($atts['autoloop']) && strtolower($atts['autoloop']) == 'yes'){
		$autoloop = 'true';
	}

	// Masquer le btn play
	$hide_play_icon = 'false';
	if( isset($atts['hide_play_icon']) ){
		$hide_play_icon = 'true';
	}

	// Choisir une video arbitrairement
	$random = isset($atts['random']) ? $atts['random'] : false;
	if ($random) {
		$args = array(
			'orderby' => 'rand',
			'showposts' => 1 ,
		);

		if(is_dev('optimize_query_video_tr_1088')){
			$args['tag'] = 'has_video';
		}else{
			$args['s'] = '[fpvideo';
		}

		$post_rand = get_posts($args);
		if (!isset($post_rand[0])) {
			return '';
		}
		$media_id = get_source_video_id_by_content($post_rand[0]->post_content);
		$atts['mediaid'] = $media_id['id'];
	}

	// Hauteur player
	$val_height ="420px";
	if(isset($atts['height']) && $atts['height']){
		$val_height =$atts['height']."px";
	}

	// Jouer la dernière vidéo publiée sur le site
	if (isset($atts['last']) && $atts['last']){
		$latestPost = get_posts('numberposts=1&s=[fpvideo');
		$video_item = get_video_item($latestPost[0]);
		$atts['mediaid'] = $video_item['video_id'];
	}

	// Afficher la pub ou non
	$id_pub = get_id_pub_video($atts);

	if(is_dev('correction_conflit_player_videosjs_meme_page_110878748')){
		// Ne pas faire du autoplay dans la seconde video dans le content de l'article
		global $index_video_short;
		$index_video_short = isset($index_video_short) ? $index_video_short : 0;
		$index_video_short ++;
		// Forcer l'activation de l'autoplay
		if(isset($atts['forceplay']) AND $atts['forceplay'] == "yes"){
			$autoPlay = 'true';
		}else if((isset($atts['autoplay']) AND $atts['autoplay'] == "no") OR ($index_video_short > 1 AND is_singular())){
			$autoPlay = 'false';
		}else{
			$autoPlay = 'true';
		}
	}else{
		// Activer l'autoplay
		if(isset($atts['autoplay']) && $atts['autoplay'] == "no"){
			$autoPlay = 'false';
		}else{
			$autoPlay = 'true';
		}
	}

	// ID du l'article de redirection
	$redirect_id = isset($atts['redirect']) ? $atts['redirect'] : '';

	// Afficher le video le plus populaire
	$mostviewed = isset($atts['mostviewed']) ? $atts['mostviewed'] : '';

	//Langeur du player
	$width = '100%';
	if(isset($atts['width'])){
		$width = $atts['width'].'px';
	}

	// src de l'image
	$src_img = '';
	if (isset($atts['src'])) {
		$src_img = $atts['src'];
	} 

	/*-- END Liste des paramettres  ---*/


	if(is_dev('lenteur_chargement_pub_player_150412161')){
		$page_ready = 'jQuery(window).load(function(){' ;
	}else{
		$page_ready ='jQuery(document).ready(function(){' ;
	}


	if ( $mostviewed ) {
		$most_popular_video = get_option( apply_filters('most_popular_video_option',"most_popular_video"), array() );
		if( isset($most_popular_video[0]) ) {
			$post_most_viewed = get_post($most_popular_video[0]);
			$most_viewed_video_params = get_video_params($post_most_viewed->post_content);
			$post_video = $most_viewed_video_params['link'];
		}
	} else {
		$mediaids = explode('|',$atts['mediaid']);
		$post_video = $mediaids[rand(0, count($mediaids)-1)];
	}

	$provider = '';
	if (strpos($post_video, 'youtu') !== false || strpos($post_video, 'dai') !== false) {
		$val_split = '/';
		if (strpos($post_video, 'youtu') !== false) {
			$provider = "youtube";
			$val_split = (strpos($post_video, '/watch?v=') !== false) ? "/watch?v=" : "/";
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $post_video, $matches);
			$video_id = (isset($matches[0])) ? $matches[0] :0 ;
			$video_id = apply_filters('fp_video_id', $video_id, $provider);
			if(!$src_img){
				$atts_width = isset($atts['width']) ? $atts['width'] : 0;
				if($atts_width > 360){
					$src_img = SITE_SCHEME."://img.youtube.com/vi/$video_id/maxresdefault.jpg";
					$data_default_src = SITE_SCHEME."://img.youtube.com/vi/$video_id/0.jpg" ;
				}else{
					$src_img = SITE_SCHEME."://img.youtube.com/vi/$video_id/mqdefault.jpg";
				}
			}
			$params["youtube_id"] = $video_id ;
		}else{
			$provider = "dailymotion";
			preg_match("#(?<=dai.ly/)[^&\n]+|(?<=video/)[^&\n]+#", $post_video, $matches);
			$video_id = (isset($matches[0])) ? $matches[0] :0 ;
			$video_id = apply_filters('fp_video_id', $video_id, $provider);
			if(!$src_img){
				$src_img = "http://www.dailymotion.com/thumbnail/video/$video_id" ;
			}
			$params["dailymotion_id"] = $video_id ;
			$api_video = explode($val_split, $post_video);
			$post_video = "api:" . $api_video[count($api_video) - 1];	
		}
	}


	if(!$src_img && is_single()){
		$thumb_post_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');		
		$src_img = $thumb_post_url[0];
	}


	$fpvideo='';
	$params = array_merge($params, $atts);
	$id_block = md5(serialize($params));

	$opt_player='';

	//initialisation du player JW7
	init_jwplayer7();

	$val_height = apply_filters('dai_player_js_height', $val_height, array('atts'=>$atts));
	$width = apply_filters('dai_player_js_width', $width, array('height'=>$val_height, 'atts'=>$atts));

	if( $provider == 'dailymotion' ){
		$fpvideo .= '<div class="dailymotion_block" data-video="'.$video_id.'" >';
		$syndication_param = $wmode ='' ; 
		if(is_dev() && $syndication = get_param_global('dail_synd_key')){
			$syndication_param = $syndication;
			$wmode = "transparent";
			$syndication = '&syndication=' .  $syndication . '&wmode=transparent';
		}else{
			$syndication = '';
		}

		$autoPlay = $autoPlay == 'true' ? true : $autoPlay;
		$frame_id = "player_".$id_block;
		
		if($autoPlay === 'false' and is_dev('click_to_play_videojs_153745207') ){
				$fpvideo.= '
				<div id="player_img_' . $id_block . '" class="img_player vjs-youtube">
				<img   src="'. $src_img .'"  width="100%" height="auto"  />
				</div>
				';
				$fpvideo .= '<div id="'.$frame_id.'" > </div>';

		}else{

			$fpvideo .= '<div id="'.$frame_id.'" > </div>';
		}




		$vid_params = array(
			"video" => $video_id,
			"width" => $width,
			"height" => $val_height,
			"syndication" => $syndication_param,
			'autoplay' => $autoPlay,
			"wmode" => $wmode,
			"id" => $frame_id,
			'mute' => $mutevideo,
			"playlist" => $playlist,
			"other_videos" => $other_videos,
			"right_video" => $right_video
		);

		$vid_params['hide_play_icon'] = $hide_play_icon;
		$video_dai_params = get_video_dai_params($vid_params);

		$fpvideoDaiTag =  "
		<script>
			var video_dai_params$id_block = $video_dai_params;	
			$page_ready 

				var id_pub = ". json_encode($id_pub) ." ;
				if(window.confing_tag_video_mobile){
					id_pub = confing_tag_video_mobile(id_pub)
				} ";
		if($autoPlay === 'false' and is_dev('click_to_play_videojs_153745207')  ){
	
			$fpvideoDaiTag .= "
					video_dai_params$id_block.params.autoplay = true ;
					jQuery(\"#player_img_$id_block\").click(function(){
						jQuery(\"#player_$id_block\").show();
						jQuery(this).hide();
						
						show_dai_video(\"$frame_id\", video_dai_params$id_block , id_pub);
					});";


		}else{
			$fpvideoDaiTag .=  "	show_dai_video(\"$frame_id\", video_dai_params$id_block , id_pub); ";
		}
			

		$fpvideoDaiTag .="	});
		</script>
		";
		$fpvideo .= $fpvideoDaiTag;
		
		$fpvideo .= '</div>';
	}else{
		
		if($autoPlay === 'false' and is_dev('click_to_play_videojs_153745207') ){
				$fpvideo.= '
				<div id="player_img_' . $id_block . '" class="img_player ">
				<img   src="'. $src_img .'"  width="100%" height="auto"  />
				</div>
				';
				$fpvideo .= '<div id="player_' . $id_block . '"  style="display:block;width:100%; text-align: center; margin-bottom: 4px;height:'.$val_height.';" class="jwplayer" ></div>';


		}else{

			$fpvideo .= '<div id="player_' . $id_block . '"  style="display:block;width:100%; text-align: center; margin-bottom: 4px;height:'.$val_height.';" class="jwplayer" ></div>';
		}


		$muted = ($mutevideo)? "true":"false";
		$params['other_videos'] = $other_videos;
		$params['right_video'] = $right_video;
		
		$id_pub_encoded=json_encode($id_pub);
		$params_encoded=json_encode($params);
		
		$ratio = "stretching: false,";
		if(isset($atts['ratio'])){
			$ratio = "stretching: true,";
		}

		if(isset($atts['noflash']) && $atts['noflash'] == 'yes'){
			$noflash = 'noflash:true' ;
		}else{
			$noflash = 'noflash:false' ;
		}

		$jwplayer_key = get_param_global('jwplayer7_key') ;
		$fpvideotag =  "
		<script>
			var setup_$id_block = {
				$opt_player
				src : \"$post_video\",
				muted: $muted,
				language:\"fr\",
				autoplay: $autoPlay,
				src_img: \"$src_img\",
				repeat:$loop,
				$noflash ,
				autoloop:$autoloop,
				playlist:$playlist,
				$ratio
			};
			
			$page_ready 
				if(\"$jwplayer_key\"){
					jwplayer.key=\"$jwplayer_key\";
				}
				var id_pub = ". json_encode($id_pub) ." ;
				if(window.confing_tag_video_mobile){
					id_pub = confing_tag_video_mobile(id_pub)
				}
			";

		if($autoPlay === 'false' and is_dev('click_to_play_videojs_153745207')  ){
			$fpvideotag .= "
					setup_$id_block.autoplay = true ;
					jQuery(\"#player_img_$id_block\").click(function(){
						jQuery(\"#player_$id_block\").show();
						jQuery(this).hide();
						show_jw7_player(\"player_$id_block\",setup_$id_block,\"".RW_THEME_DIR_URI."\", id_pub ,\"$provider\", $params_encoded);
					});";
		}else{
			$fpvideotag .=  "show_jw7_player(\"player_$id_block\",setup_$id_block,\"".RW_THEME_DIR_URI."\", id_pub ,\"$provider\", $params_encoded);	";
			
		}


			$fpvideotag .=  "
			});

		</script>";

		$fpvideo .= $fpvideotag;
	}

	$fpvideo = apply_filters( 'fpvideo_filter', $fpvideo, $video_id, $atts, $provider );

	if( $redirect_id ){
		$link = get_permalink($redirect_id);
		if($link)
			$fpvideo .= '<a href="'.$link.'" class="read_post_video"></a>';
	}
	return $fpvideo;
}



// SEO VIDEOS SINGLE
add_filter( 'fpvideo_filter', 'seo_fpvideo_single', 10, 4 );
function seo_fpvideo_single( $html_fpvideo, $video_id, $atts, $provider ){
	global $post, $is_live_content;
	$fpvideo_seo = '';
	
	// START Recommandations SEO sur les articles video Ouverture div <div class="video-pub-post"
	$mediaid = $atts['mediaid'];
	if($is_live_content){
		if(!empty($video_id)){
			$info_video_id = $video_id;
		}else{
			$info_video_id = $mediaid;
		}
		$info = get_info_viedo($info_video_id, $provider);
		if($info){
			$fpvideo_seo = '<div class="video_pub">';
			$fpvideo_seo .= apply_filters('btn_masque_video_premiun','');
			$fpvideo_seo .= '<div class="video-pub-post" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">';
			
			$fpvideo_seo .= '<meta itemprop="name" content="'.  str_replace('"', '\"', $info['name'])  .'" />';

			if(!empty($info['duration'])){
				$fpvideo_seo .= '<meta itemprop="duration" content="'. $info['duration']  .'" />';
			}
			$fpvideo_seo .= '<meta itemprop="contentUrl" content="'. $info['contentUrl'] .'" />';
			$fpvideo_seo .= '<meta itemprop="playerType" content="Flash" />';
			$video_img = get_video_img($info_video_id, $provider, true) ;
			$video_img = $video_img ? $video_img : get_the_post_thumbnail_url($post);
			if($video_img){
				$fpvideo_seo .= '<meta itemprop="thumbnail" content="'. $video_img .'" />';
			}

			if(is_dev('seo_propriete_itemprop_manquant_101198852')){
				if($video_img){
					$fpvideo_seo .= '<meta itemprop="thumbnailUrl" content="'. $video_img .'" />';
					$fpvideo_seo .= '<meta itemprop="image" content="'. $video_img .'" />';
				}
				if(isset($info['uploadDate'])) {
					$fpvideo_seo .= '<meta itemprop="uploadDate" content="'. $info['uploadDate'].'" />';
				}
			}

		}
	}

	// HTML FP_VIDEO
	$fpvideo_seo .= $html_fpvideo;


	// START Recommandations SEO sur les articles video Fermeture div <div class="video-pub-post"
	if($is_live_content){
		if($info){
			global $post;
			$desc = $info['description'] ? $info['description'] : $post->post_excerpt;
			$capt = $info['caption'] ? $info['caption'] : $post->post_excerpt;
			if($desc){				
				$fpvideo_seo .= '<meta itemprop="description" content="'. htmlentities($desc, ENT_QUOTES).'" />';
			}
			if($capt){			
				$fpvideo_seo .= '<meta itemprop="caption" content="'. htmlentities($capt, ENT_QUOTES).'" />';
			}
			$fpvideo_seo .= '</div>';
			$fpvideo_seo .= '</div>';
		}
	}

	return $fpvideo_seo;
}