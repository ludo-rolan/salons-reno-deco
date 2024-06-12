<?php 

if (!defined('JSON_UNESCAPED_UNICODE')) {
	define('JSON_UNESCAPED_UNICODE', 0);
}

function filter_whitespacee($str){
	//$str = str_replace("’", "'", $str);
	$nbsp = utf8_encode("\xA0");
	$str = str_replace($nbsp, " ", $str);

	$space = html_entity_decode('&#x202F;', ENT_NOQUOTES, 'UTF-8');
	$str = str_replace($space, " ", $str);
	$str = str_replace('&rsquo;', "'", $str);

	$str = preg_replace("/\s{2,}/", " ", $str);
	return  $str; 
}

function remove_shortcodes_from_content($content){
	$to_cancel = array('likebox' , 'fpvideo' , 'jwplayer') ;

	foreach (  $to_cancel as $key=>$shortcode  ){
		$reg = "/\[$shortcode\s*(.*?)\](.*?\[\/$shortcode\])?/im";
		$content = preg_replace($reg , '' , $content );
	}
	return $content;
}

function feed_json() {
	//error_reporting(E_ALL);
	//print_r(JSON_UNESCAPED_UNICODE);
	global $post ,$query_string, $blog_id;
		
	if (isset($_GET["count"])) {	
		$count = $_GET["count"];								
	} else {		
		$count = 25;
	}
	if (isset($_GET["force"])) {	
		$force = $_GET["force"];								
	} else {		
		$force = false;
	}
	header('Content-type: application/json; charset=utf-8');

	$feed = apply_filters("feed_name","feed_json_".$count ) ;
	$json = get_transient($feed);
	//$json = false;

	if ($json && !$force) {
		
		echo $json;	

	} else {

		$meta = get_option('meta_boxes_category');
		$exclure = array();
		foreach($meta as $cat_id => $value){
			if( isset($value["exclude_feed_beemobee"]) && $value["exclude_feed_beemobee"] == 1){
				$exclure[] = $cat_id ;
			}
		}		
		for($i=0; $i<count($exclure); $i++){
			$id = $exclure[$i];
			$exclure_new = get_category_children($id, ",") ;
			$exclure_new = substr($exclure_new, 1) ;
			if($exclure_new ){
				$exclure = array_merge($exclure, explode(",", $exclure_new));	
			}						
		}				
		$posts = get_posts(array('showposts' => $count, 'category__not_in' => $exclure));
	
		if (count($posts)) {
			$json = array();
			foreach ($posts as $post) {

				setup_postdata( $post );

				$id = (int) get_the_ID();
				
				$post_categories = get_post_categories();

				$single = array(
					'id'			=> $id,
					'title'			=> filter_whitespacee(get_the_title()) ,
					'permalink'		=> get_permalink(),
					'excerpt'		=> filter_whitespacee(get_the_excerpt()),
					'date'			=> get_the_date('Y-m-d H:i:s','','',false) ,
					'author'		=> filter_whitespacee(get_the_author()),
					'nonce'			=> wp_create_nonce("nonce_ratings_" .$id),
					'tinyurl'		=> file_get_contents("http://tinyurl.com/api-create.php?url=".get_permalink()),
					'categories' 	=> $post_categories['items'],
				);



				if(function_exists('has_post_thumbnail') && has_post_thumbnail($id)) {
					$single["thumbnail"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id)));
					$single["thumbnail_popularpost"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "thumbnail_popularpost"));
					//$single["thumbnail_carroussel"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "thumbnail_carroussel"));
					$single["thumbnail_carroussel"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "rw_large"));
					
					$single["thumbnail_medium"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "medium"));
					$single["thumbnail_large"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "large"));
					$single["thumbnail_thumb"] = current(wp_get_attachment_image_src(get_post_thumbnail_id($id), "thumbnail"));
				}

				if ($blog_id==2) {

					$post_meta_data = get_post_custom($id);
					$post_ratings_average = get_average_vote($id);
					$post_ratings_average = ($post_ratings_average)?$post_ratings_average:0;
					$single['ratings'] = $post_ratings_average;
					if ($single['ratings']==null || $single['ratings']=="") {
						$single['ratings'] = 0;
					}


					$post_ingredients = (isset($post_meta_data['post_ingredients'])) ? $post_meta_data['post_ingredients'][0] : "";
					$post_list_ingredients = (isset($post_meta_data['post_list_ingredients'])) ? maybe_unserialize($post_meta_data['post_list_ingredients'][0]) : array();
					
					$post_nb_persons = (isset($post_meta_data['post_nb_persons'])) ? $post_meta_data['post_nb_persons'][0] : "";
				
					$ingredients = array("ingredients" => array() , "personnes" => "");

					if(count($post_list_ingredients) > 0 && isset($post_list_ingredients[0]) && $post_list_ingredients[0]!=""){
						$ingredients = array("ingredients" => $post_list_ingredients , "personnes" => "Pour ".$post_nb_persons." personnes");
																	
					} else if( $post_ingredients) {					
						$pos = stripos($post_ingredients, '<ul>');
						$debut = substr($post_ingredients, 0, $pos);
						$fin = substr($post_ingredients, $pos);
				
						$np_personnes	= trim(strip_tags($debut));
						$liste_ing		= explode('<li>', $fin);
	
						array_shift($liste_ing);
				
						foreach($liste_ing as $key => $value) {
							$i = trim(strip_tags($value));
							if ($i!=null && $i!="") {
								$liste_ing[$key] = $i;
							}
						}		
						$ingredients = array('personnes' => "Pour ".$np_personnes." personnes", 'ingredients' => $liste_ing);					
					}
					
					$single['ingredients'] = $ingredients;													
				}
	 
				$content	= get_the_content();

				if(class_exists('nggdb')){
					// TODO : remove this conditionnal
					$nggdb = new nggdb();
					$types		= array('nggallery', 'slideshow', 'imagebrowser ');
					
					foreach($types as $type){
						if(stristr($content , "[{$type}" )) {
							$search = "@\[{$type}\s*id\s*=\s*(\w+|^\+)\s*\]\s*@i";
							if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
								foreach ($matches as $match) {
									$gallery			= nggdb::find_gallery($match[1] );
									$image				= $nggdb->find_image($gallery->previewpic);
		
									$replace = '['.$type.' id="'.$match[1].'" cover="'.$image->imageURL.'"]';
									$content = str_replace ($match[0], $replace, $content);
								}
							}
						}
					}
				}

				
				
				// TODO : Should be the main code after removing nextgen
				if(stristr($content , "[gallery" )) {
					if(preg_match_all(HAS_GALLERY_REGEXP, $content, $matches, PREG_SET_ORDER)) {
						foreach ($matches as $match) {
							if(NEW_HAS_GALLERY_REGEXP){
								$galleries			= explode( ',' , get_gallery_ids($match) );
							}else{
								$galleries			= explode( ',' , $match[2] );
							}
							// just get the cover for the first one
							$image_url				= wp_get_attachment_url($galleries[0]);
							// Use the post ID
							$replace = '[slideshow id="'.$id.'" cover="'.$image_url.'"]';
							$content = str_replace ($match[0], $replace, $content);
						}
					}
				}
				
				//$content = apply_filters( 'the_content', $content );
				$content = wpautop($content);
				/* plus besoin..
				$pattern = '/<iframe(.*?)(\/>|>(.*?)<\/iframe>)/im';						
				$content = preg_replace($pattern, '', $content  ) ;*/
				

		
				// video plugin jwplayer 
				$videos = array();
				if(stristr($content , "[jwplayer" )) {
					//$search = "@\[jwplayer.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
					$search = "@\[jwplayer\s+mediaid\s*=\s*\"(.*?)\"\s*\]@i";
					if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
						//on recup le guid du media 
						foreach ($matches as $match) {
							if( is_numeric ($match[1])){
								$media = get_object_vars(get_post($match[1]));							
								if($media) {
									$videos[]= $media['guid'];
								}
							}else{
								$videos[] = $match[1];
							}

						}
					}
				}
				if(stristr($content , "[fpvideo" )) {
					//$search = "@\[fpvideo.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
					$search = "@\[fpvideo\s+mediaid\s*=\s*\"(.*?)\"\s*\]@i";
					if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
						//on recup le guid du media 
						foreach ($matches as $match) {
							if( is_numeric ($match[1])){
								$media = get_object_vars(get_post($match[1]));							
								if($media) {
									$videos[]= $media['guid'];
								}
							}else{
								$videos[] = $match[1];
							}

						}
					}
				}
				if(stristr($content , "[fpvideo" )) {
					$search = "@\[fpvideo.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
					if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
						//on recup le guid du media 
						foreach ($matches as $match) {
							$media = get_object_vars(get_post($match[1]));
							
							if($media) {
								$videos[]= $media['guid'];
							}
						}
					}
				}
				// remmove shortcodes
				$single["content"] = remove_shortcodes_from_content(filter_whitespacee($content));
				$single["videos"] = $videos;
				

				
				// tag
				$tags = array();
				foreach((array)get_the_tags() as $tag) { 
					if(isset($tag->name)){
						$tags[] = $tag->name; 
					}
				}
				$single["tags"] = array_filter($tags);
				if ($single["tags"]==null) $single["tags"]=array();
			
				//print_r($single); 
				foreach ($single as $key => $value) {
					if ($value==null && !is_array($value) && $key!="ratings") {
						$single[$key] = "";
					}
				}

				$json[] = $single;
			}
			//print_r($json); 
			//die();
			$json = json_encode($json, JSON_UNESCAPED_UNICODE);
			
			nocache_headers();
			$feed = apply_filters("feed_name", "feed_json_".$count ) ;
			set_transient($feed, $json, 60*60*6);
			echo $json;
		
		} else {
			header("HTTP/1.0 404 Not Found");
			wp_die("404 Not Found");
		}
	
	}
		
}
add_feed('json', 'feed_json');

function buildTree(array $elements, $parentId = 0) {
    $branch = array();
    foreach ($elements as $element) {
        if ($element["parent"] == $parentId) {
            $sousCategorie = buildTree($elements, $element["menu_id"]);
            if ($sousCategorie) {
                $element["categories"] = $sousCategorie;
            }				
			unset($element["parent"]);
			unset($element["menu_id"]);								
            $branch[] = $element;
        }
    }
    return $branch;
}

function do_feed_plan() {
	header('Content-type: application/json; charset=utf-8');
	$feed = apply_filters("feed_name", "feed_plan" ) ;
	$json = get_transient( $feed );
	if (isset($_GET["force"])) {	
		$force = $_GET["force"];								
	} else {		
		$force = false;
	}
	if($json && !$force){
		echo $json ;		
	}else{	
		$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');
		$items = wp_get_nav_menu_items($menu_id);
		$finalItems =  array();
		$meta = get_option('meta_boxes_category');
		$exclure = array();
		foreach($meta as $cat_id => $value){
			if( isset($value["exclude_feed_beemobee"]) && $value["exclude_feed_beemobee"] == 1){
				$exclure[] = $cat_id ;
			}
		}
		foreach($items as $item){
			if($item->object == "category" && !in_array($item->object_id, $exclure) ){
				$items2 = array(		
					"name" => $item->title ,
					"id"   =>  $item->object_id ,
					"parent" => $item->menu_item_parent ,
					"menu_id" =>  $item->ID
				);	
			
				$finalItems [] = $items2 ;
			}
		}			
		$json = json_encode(array("menu" => buildTree($finalItems)), JSON_UNESCAPED_UNICODE);
		$feed = apply_filters("feed_name", "feed_plan" ) ;

		set_transient( $feed, $json, 60*60*6 );
		echo $json ;
	}
	exit();	
}

if(isset($_GET["feed"])){
	/*résoudre le conflit entre /?feed=sitemap et /sitemap.xml */
	add_feed('sitemap', 'do_feed_plan' );
}
function get_quiz_result_text($result_text) {
	$result_text = maybe_unserialize($result_text);

	if (is_array($result_text)) {
		$text = '';
		$count = count($result_text['prozent']);

		for ($q=0; $q<$count; $q++) {

			$start = $result_text['prozent'][$q];
			$end = 100;
			if ($q<$count-1) {
				$end = $result_text['prozent'][$q+1];
			}

			$text .= 'VOUS AVEZ OBTENU ENTRE '.$start.'% et '.$end.'% DE BONNES REPONSES?'."\r\n\r\n";
			$text .= $result_text['text'][$q]."\r\n\r\n";
			
		}
		return $text;
	} 

	return $result_text;
}


function json_quiz() {
	header('Content-type: application/json; charset=utf-8');
	$feed = apply_filters("feed_name", "json_quiz" ) ;
	$json = get_transient($feed);
	//$json = false;
	if (isset($_GET["force"])) {	
		$force = $_GET["force"];								
	} else {		
		$force = false;
	}

	if($json and !$force){
		echo $json ;
	}else{
		global $wpdb;
	
		$rows = $wpdb->get_results("
			SELECT 
				`".$wpdb->prefix."wp_pro_quiz_master`.id,
				`".$wpdb->prefix."wp_pro_quiz_master`.name, 
				`".$wpdb->prefix."wp_pro_quiz_master`.text,  
				`".$wpdb->prefix."wp_pro_quiz_master`.result_text
			FROM  `".$wpdb->prefix."wp_pro_quiz_master` 
			ORDER BY `".$wpdb->prefix."wp_pro_quiz_master`.id DESC
			LIMIT 10
		");
	
		$quizz = array();
		$quizz_ids = array();
		foreach ($rows as $row) {			
			$quiz = array();
			$quiz["id"] = (int)$row->id;
			$quiz["name"] = $row->name;
			$quiz["text"] = $row->text;
			$quiz["result_text"] =  get_quiz_result_text($row->result_text) ;
			$quiz["questions"] = array();
			$quizz[$row->id] = $quiz;
			array_push($quizz_ids, $row->id);
		}
	
		$sql = "SELECT 
				`".$wpdb->prefix."wp_pro_quiz_question`.quiz_id,  
				`".$wpdb->prefix."wp_pro_quiz_question`.id, 
				`".$wpdb->prefix."wp_pro_quiz_question`.question,  
				`".$wpdb->prefix."wp_pro_quiz_question`.answer_data,
				`".$wpdb->prefix."wp_pro_quiz_question`.category_id,
				`".$wpdb->prefix."wp_pro_quiz_category`.category_name
			FROM  `".$wpdb->prefix."wp_pro_quiz_question` 
			LEFT JOIN `".$wpdb->prefix."wp_pro_quiz_category` ON `".$wpdb->prefix."wp_pro_quiz_category`.category_id = `".$wpdb->prefix."wp_pro_quiz_question`.category_id
			WHERE quiz_id IN (".implode(",", $quizz_ids).")
			ORDER BY `".$wpdb->prefix."wp_pro_quiz_question`.quiz_id DESC, `".$wpdb->prefix."wp_pro_quiz_question`.id ASC";
	
		$rows = $wpdb->get_results($sql);

		foreach ($rows as $row) {
			$quizz[$row->quiz_id]["category"] = $row->category_name;
			$question = array();
			$question["id"] = (int)$row->id;
			$question["question"] = $row->question;
			$question["answers"] = array();
			$answers = unserialize($row->answer_data);
			if(is_array($answers)){
				foreach ($answers as $answer) {
					//echo $answer->getAnswer();
					//var_dump($answer);
					$resp = array(
						"id" => 0,
						"answer" => $answer->getAnswer(),
						"points" => $answer->getPoints(),
						"correct" => $answer->isCorrect()
					);
					array_push($question["answers"], $resp);
				}
			}

			array_push($quizz[$row->quiz_id]["questions"], $question);
		}
		$quizz = array_values($quizz);
		$json = json_encode($quizz, JSON_UNESCAPED_UNICODE);	
		$feed = apply_filters("feed_name", "json_quiz" ) ;
		
		set_transient( $feed . $count, $json, 60*60*6);	
		echo $json ;
	}
}
add_feed('json_quiz', 'json_quiz');

function get_post_categories(){
	
	global $blog_id;

	$cats = get_the_category() ;
	$list = array('items'=> [] ,  'slugs'=> [] , 'names' =>[]); 
	foreach($cats as $cat){

		if($cat->category_parent == 0){	

			add_feed_category_parent($list, $cat);

		} else {
			if ($blog_id == 2) {
				add_feed_category_parent($list, $cat);
			}
			$cat_parent = get_category($cat->category_parent);
			add_feed_category_parent($list, $cat_parent) ;
		}	
		$list['slugs'][] = $cat->slug;
		$list['names'][] = filter_whitespacee($cat->name); 

	}
	return $list ;
}

function add_feed_category_parent(&$list, $cat){
	global $blog_id;
	if ($blog_id == 2) {
		$item = $cat->term_id;	
	} else {
		$item = filter_whitespacee($cat->name);
	}
	if (!in_array($item, $list['items'])) {
		$list['items'][] = $item;
	}
	/*if (!in_array($cat->slug, $list['slugs'])) {
		$list['slugs'][] = $cat->slug;
	}*/

}



function feed_json2() {
	header('Content-type: application/json; charset=utf-8');
	
	global $post ,$wp_query;		
	
	if (isset($_GET["force"])) {	
		$force = $_GET["force"];								
	} else {		
		$force = false;
	}
	$json['page'] = !empty($wp_query->query_vars['paged'])?$wp_query->query_vars['paged'] : 1  ;
	if ($wp_query->found_posts) {

		$found_posts = $wp_query->found_posts;
		$posts_per_page = $wp_query->query_vars['posts_per_page'] ;
		$total_page =  ceil($found_posts / $posts_per_page) ;
		$json['total_pages'] = $total_page ;
		$json['found_posts'] = $found_posts ;
		
		$json['posts'] =  rw_generat_json($wp_query->posts);	
	} else {
		$json['posts'] = array();
		$json['total_pages'] = 0 ;
		$json['found_posts'] = 0 ;
	}
	echo json_encode($json, JSON_UNESCAPED_UNICODE);
	exit();
}
function rw_generat_json($posts){
	global $post ;
	foreach ($posts as $post) {
		setup_postdata($post) ;
		$meta_data = get_post_custom($post->ID);

		$id = (int) get_the_ID();

		$post_categories = get_post_categories();

		$single = array(
			'id'			=> $id,
			'title'			=> filter_whitespacee(get_the_title()) ,
			'permalink'		=> get_permalink(),
			'excerpt'		=> filter_whitespacee(get_the_excerpt()),
			'date'			=> get_the_date('Y-m-d H:i:s','','',false) ,
			'author'		=> filter_whitespacee(get_the_author()),
			'nonce'			=> wp_create_nonce("nonce_ratings_" .$id),
			//'tinyurl'		=> file_get_contents("http://tinyurl.com/api-create.php?url=".get_permalink()),
			'categories' 	=> $post_categories['items'],
			'categories_slugs' 	=> $post_categories['slugs'],
			'categories_names' 	=> $post_categories['names'],
			'meta_data'		=> $meta_data,
			'status'		=> get_post_status($post),
			'title_highlight'	=> get_post_meta($id, 'title_highlight', true),
		);

		$single = apply_filters('rw_generat_json_post', $single ,$post);

		if(function_exists('has_post_thumbnail') && has_post_thumbnail($id)) {
			$image_id = get_post_thumbnail_id($id);
			$single["thumbnail"] = current(wp_get_attachment_image_src($image_id));
			$single["thumbnail_popularpost"] = current(wp_get_attachment_image_src($image_id, "thumbnail_popularpost"));
			$single["thumbnail_full"] = current(wp_get_attachment_image_src($image_id, "thumbnail_full"));
			//$single["thumbnail_carroussel"] = current(wp_get_attachment_image_src($image_id, "thumbnail_carroussel"));
			$single["thumbnail_medium"] = current(wp_get_attachment_image_src($image_id, "rw_medium"));
			$single["thumbnail_large"] = current(wp_get_attachment_image_src($image_id, "rw_large"));
			$single["thumbnail_thumb"] = current(wp_get_attachment_image_src($image_id, "thumbnail"));
		}

		$content	= get_the_content();



		
		$galleries_export = array() ;
		// TODO : Should be the main code after removing nextgen
		if(stristr($content , "[gallery" )) {
			if(preg_match_all(HAS_GALLERY_REGEXP, $content, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if(NEW_HAS_GALLERY_REGEXP){
						$galleries			= explode( ',' , get_gallery_ids($match) );
					}else{
						$galleries			= explode( ',' , $match[2] );
					}

					foreach ($galleries as $img_id) {
						$image_url= wp_get_attachment_url($img_id);
						$img = get_post($img_id) ;
						$galleries_export[] = array(
							'title' => $img->post_title, 
							'excerpt' => $img->post_excerpt,
							'url' => $image_url,
						);
					}
					$content = str_replace ($match[0], '', $content);
				}
			}
		}
		
		//$content = apply_filters( 'the_content', $content );
		$content = wpautop($content);
		/* plus besoin..
		$pattern = '/<iframe(.*?)(\/>|>(.*?)<\/iframe>)/im';						
		$content = preg_replace($pattern, '', $content  ) ;*/
		


		// video plugin jwplayer 
		$videos = array();
		$videos_short_code = array();
		if(stristr($content , "[jwplayer" )) {
			//$search = "@\[jwplayer.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
			$search = "@\[jwplayer\s+mediaid\s*=\s*\"(.*?)\"\s*\]@i";
			if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
				//on recup le guid du media 
				foreach ($matches as $match) {
					if( is_numeric ($match[1])){
						$media = get_object_vars(get_post($match[1]));							
						if($media) {
							$videos[]= $media['guid'];
						}
					}else{
						$videos[] = $match[1];
					}
					$videos_short_code[] = $match[0] ;

				}
			}
		}
		if(stristr($content , "[fpvideo" )) {
			//$search = "@\[fpvideo.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
			$search = "@\[fpvideo\s+mediaid\s*=\s*\"(.*?)\"\s*\]@i";
			if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
				//on recup le guid du media 
				foreach ($matches as $match) {
					if( is_numeric ($match[1])){
						$media = get_object_vars(get_post($match[1]));							
						if($media) {
							$videos[]= $media['guid'];
						}
					}else{
						$videos[] = $match[1];
					}
					$videos_short_code[] = $match[0] ;
				}
			}
		}
		if(stristr($content , "[fpvideo" )) {
			$search = "@\[fpvideo.*mediaid\s*=\s*\"(\w+|^\+)\"\s*\]\s*@i";
			if(preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {
				//on recup le guid du media 
				foreach ($matches as $match) {
					$media = get_object_vars(get_post($match[1]));
					
					if($media) {
						$videos[]= $media['guid'];
					}
					$videos_short_code[] = $match[0] ;
				}
			}
		}
		// remmove shortcodes
		$single["content"] = remove_shortcodes_from_content(filter_whitespacee($content));
		$single["videos"] = $videos;
		$single["videos_short_code"] = $videos_short_code;
		
		
		if(count($galleries_export)){
			$single["galleries"] = $galleries_export;
		}

		
		// tag
		$tags = array();
		foreach((array)get_the_tags() as $tag) { 
			if(isset($tag->name)){
				$tags[] = $tag->name; 
			}
		}
		$single["tags"] = array_filter($tags);
		if ($single["tags"]==null) $single["tags"]=array();
	
		//print_r($single); 
		foreach ($single as $key => $value) {
			if ($value==null && !is_array($value) && $key!="ratings") {
				$single[$key] = "";
			}
		}

		$extra = apply_filters('extra_meta_json2' , array(), $post, $meta_data);
		if($extra){
			$single["extra"] = $extra ;
		}

		$json[] = $single;
		wp_reset_postdata();
	}
	return $json ;
}

add_feed('json2', 'feed_json2');

if(isset($_GET['feed_posts_json'])){
	add_action('wp', function(){
		
		header('Content-type: application/json; charset=utf-8');
		$post_type = ['post','page'];
		$post__in = $_GET['feed_posts_json'] ;
		$post__in = explode(',', $post__in) ;
		$posts = get_posts( array('post__in' => $post__in, 'post_type' => $post_type , 'post_status' => array('future', 'publish'))) ;
		
		foreach ($posts as  &$post) {
			$post->post_status = 'publish' ;
		}
		$json =  rw_generat_json($posts) ;
		echo  json_encode( $json ) ;
		exit();
	} );
}

function extra_meta_json2_recipes($extra, $post, $meta_data){
	if(isset($meta_data['post_is_recette'][0]) && $meta_data['post_is_recette'][0]){
		$data_recette  =array('post_prep_duration', 'post_cook_duration', 'post_cook_refrigeration', 'post_list_ingredients', 'post_nb_persons') ;

		foreach ($data_recette  as $key) {
			if(isset($meta_data[$key][0])){
				$value = $meta_data[$key][0] ;
				if(is_serialized($value)){
					$value = unserialize($value);
				}
				$extra[$key] = $value;
			}
		}
	}
	return $extra ;
}


if(get_param_global('has_recipes')){
	add_filter('extra_meta_json2','extra_meta_json2_recipes', 10, 3);
}


add_filter( 'option_posts_per_rss', 'feed_posts_json2' );


function feed_posts_json2($n){
	global $wp_query ;
	if( $wp_query->is_feed('json2') && !empty($_GET['count']) ) {
    	$n = $_GET['count'];
    }
    return $n ;
}

function set_query_feed( $query ) {
	if ( isset($_GET['recette_json']) ) {
		$query->set( 'meta_query', array(
			array(
				'key' => 'post_is_recette',
				'value' => 1,
			)
		));
	}
}
add_action( 'pre_get_posts', 'set_query_feed' );


function set_query_feed_coronavirus( $query ) {
	if ( $query->is_main_query()){
		$query->set( 'meta_query', array(
			array(
				'key' => 'is_corona_post',
				'value' => 1,
			)
		));
	}
	
}
if ( !empty($_GET['feed_coronavirus']) ) {
	add_action( 'parse_query', 'set_query_feed_coronavirus' );
}



