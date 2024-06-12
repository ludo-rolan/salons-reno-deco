<?php
// Define LOCK_POSTS_LIMIT 
defined('LOCK_POSTS_LIMIT') or define('LOCK_POSTS_LIMIT', 10);

// add admin options
    class Locking {
    	static $nb_pos = 5;
        function __construct() {
            // activate admin page
			if (is_admin()){
				add_action('admin_init', array(&$this, 'locking_rw_meta_box'));
	            add_action('admin_menu', array(&$this, 'add_locking_admin'));
		    	add_action( 'wp_ajax_locking_ajax',  array(&$this, 'locking_ajax'));
			}
        }
        /*public static function nb_pos(){
        	return 5;
        }*/
        function add_locking_admin() {
            //add_options_page('Locking', 'Locking', 'manage_options', 'locking_admin', array(&$this, 'locking_admin'));
            $role = apply_filters('locking_permission', 'manage_options');
            add_menu_page('Locking', 'Verrouillage RW', $role, 'locking_admin', array(&$this, 'locking_admin'),"dashicons-lock");
        }
        function locking_rw_meta_box(){
			add_meta_box(
				'locking_rw_properties', // $id
				__('Vérrouillage RW' ) , // $title
				 array(&$this, 'display_locking_to_post'), // $callback
				get_param_global('meta_box_post_type_locking','post'), // $page /* stick with $post_type for now */
				'normal', // $context /* 'normal' = main column. 'side' = sidebar */
				'high' // $priority /* placement on admin page */
			);
		}
        function display_locking_to_post(){
		    global $post;
			$locks = get_post_meta( $post->ID, '_post_rw_locked',true );
			if(!empty($locks)){
				$locking = get_param_global('locking');
				echo "<ul> \n";
				foreach($locks as $k=>$v){
					$element=$element_check=$v['element'];
					$page=$v['page'];
					$pos=$v['position'];
					$urlto = admin_url("options-general.php?page=locking_admin&locking_page=".$page);
					if($page=='category' || $page=='sous-category'){
						$element_check=preg_replace("/(_[0-9]+)$/","",$element);
						$cat_id=(int) str_replace($element_check."_","",$element);
					}
					if(isset($locking[$page][$element_check])){
						$locking_page_title=(!empty($locking[$page]['title']))?$locking[$page]['title']:$page;
						$locking_element_title=(!empty($locking[$page][$element_check]['title']))?$locking[$page][$element_check]['title']:$element_check;
						if(($page=='category' || $page=='sous-category') && $cat_id > 0){
							$locking_element_title = str_replace("%cat%", get_the_category_by_ID($cat_id), $locking_element_title);
							$urlto .= "&cat=$cat_id";
						}

						echo "<li> <strong>$locking_page_title </strong> $locking_element_title : ".$locking[$page][$element_check]['desc'];
						echo " $pos <br /> du <strong>".$v['start'].'</strong> au  <strong>'.$v['end'].'</strong>';
						echo '<br/> <a href="'.$urlto.'">modifier</a>';
						echo "</li> \n";
					}
				}
				echo "</ul> \n";
			}else{
				echo '<ul><li>'.__('Aucun vérrouillage pour ce post' ).',<br /> <a href="'.admin_url("options-general.php?page=locking_admin").'">'.__('Ajouter' ).'</a></li></ul>';
			}
        }
    	/* Enregistrement 
    	{
			Position : 1
			id : 23
			start: date0
			end : date1

		}
		{
			Position : 1
			id : 4
			date : ..
		}
		{
			Position : 3
			id : 23
			date : ..
		}
		*/
		function locking_ajax() {
	    	check_ajax_referer( 'internal-linking', '_ajax_linking_nonce' );
	    	/*
				_ajax_linking_nonce: "31cdb79227"
				action: "locking_ajax"
				element: "video"
				id: "95949"
				page: "home"
				position: "2"
				remove: "true"
	    	*/
			/*
				code d'erreur :
				1 : Date de début > date de fin
				2 : Conflit existant au niveau de certains interval de date avec d'autres Articles.
				3 : Erreur de parsing, vérifier le format des dates
				4 : Manque des éléments dans le POST
				5 : Element de verrouillage n'existant dans la config du site
				10 : Element non existant pour la suppression
				code success :
				100 : 1er enregistrement pour cette position
				101 : Enregistrement existe déjà pas de mise à jour necessaire.
				102 : Enregistrement mise à jour necessaire.
				103 : Ajout de cette article pour cette position.
				110 : Element supprimé avec success
			*/
			$error = 4;
			if(!empty($_POST['page']) && !empty($_POST['element']) && !empty($_POST['id'])){
				$locking = get_param_global('locking');
				$element=$_POST['element'];
				$page=$_POST['page'];
				$element_check=($page=='category' || $page=='sous-category')?preg_replace("/(_[0-9]+)$/","",$element):$element;
				if(isset($locking[$page][$element_check])){
					$key_opt = 'locking_'.$page.'_'.$element;
					$locked=get_option($key_opt);
					$config = $locking[$page][$element_check];
					$is_post = (!empty($config['args']) && !empty($config['args']['post_type']))?true:false;
					$post_rw_locked=($is_post)?get_post_meta($_POST['id'], '_post_rw_locked', true):[];
					if(!is_array($post_rw_locked))
						$post_rw_locked=[];

					if(isset($_POST['remove'])){
						$toRemove = array(
							'id' => $_POST['id'], 
							'position' => $_POST['position']
						);
						$remove = Locking::removeOption($locked,$toRemove);
						if($remove['code'] == 110 && isset($remove['key'])){
							unset($locked[$remove['key']]);
							foreach ($post_rw_locked as $i => $el) {
								if($el['element']==$element && $el['page']==$page && $el['position']==$toRemove['position']){
									unset($post_rw_locked[$i]);
									if(empty($post_rw_locked)){
										unset($post_rw_locked);
									}
									if($is_post){
										if(empty($post_rw_locked)){
											delete_post_meta($_POST['id'], '_post_rw_locked');
										}else{
											update_post_meta( $_POST['id'], '_post_rw_locked', $post_rw_locked );
										}
									}
									//break;
								}
							}
							update_option($key_opt,$locked,false);

							wp_cache_delete($key_opt , 'options');
							//wp_cache_delete('alloptions' , 'options');
							do_action('update_locking' , $key_opt );
						}
						$remove['removed']=true;
						$toRemove['element']=$element; 
						$toRemove['page']=$page;
						$remove["option"]=$toRemove;
						wp_send_json($remove);						
					}
					$toAdd = array(
						'id' => $_POST['id'], 
						'position' => $_POST['position'], 
						'start' => $_POST['start'], 
						'end' => $_POST['end'] 
					);
					$toSend = $toAdd;
					$toSend['element']=$element; 
					$toSend['page']=$page;
					$check = Locking::checkOption($locked,$toAdd);
					if(empty($check['success']) || $check['success']==false){

					}else{
						if(!empty($check['code']) && $check['code']==102){
							$locked[$check['key']] = $toAdd;
							$k_id=true;
							foreach ($post_rw_locked as $i => $el) {
								if($el['element']==$element && $el['page']==$page && $el['position']==$toAdd['position']){
									$post_rw_locked[$i]=$toSend;
									$k_id=false;
									break;
								}
							}
							if($k_id) $post_rw_locked[]=$toSend;

						}elseif(!empty($check['code']) && $check['code']!=101){
							if(!is_array($locked))
								$locked = array();
							$locked[] = $toAdd;
							$post_rw_locked[]=$toSend;
						}
						//update_option("posts_locking",$post_rw_locked);
						if($is_post)
							add_post_meta( $_POST['id'], '_post_rw_locked', $post_rw_locked, true ) or update_post_meta( $_POST['id'], '_post_rw_locked', $post_rw_locked );
						
						update_option($key_opt,$locked , false );
						wp_cache_delete($key_opt , 'options');
						//wp_cache_delete('alloptions' , 'options');
						do_action('update_locking' , $key_opt );

					}
					$check["option"]=$toSend;
					$error = false;
	    			wp_send_json($check);
				}else{
					$error=5;
				}
			}
			if($error)
				wp_send_json(array('success'=>false,'code'=>$error));
		}
		function removeOption($locked,$toRemove){
			$code = 10;
			$k = false;
			if( is_array($locked) ){
				foreach ($locked as $key => $value) {
					if($value['position'] == $toRemove['position'] && $value['id'] == $toRemove['id']){
						$k=$key;
						$code = 110;
					}
				}
			}
			if($code==10){
				return array('success'=>false,'code'=>10);
			}
			return array('success'=>true,'code'=>$code,'key'=> $k);
		}
		function checkOption($locked,$toAdd){
			try{
		    	$d1 = new DateTime($toAdd['start']);
				$d2 = new DateTime($toAdd['end']);
				if($d1>$d2){
					return array('success'=>false,'code'=>1);
				}
				if(empty($locked) || !is_array($locked))
					return array('success'=>true,'code'=>100);

				$checkConflict = array();
				$error = 0;
				$code = 103;
				$k = false;
				$now = new DateTime();
				foreach ($locked as $key => $value) {
					if($value['position'] == $toAdd['position']){
						if($value['id'] != $toAdd['id']){
							$d3 = new DateTime($value['start']);
							$d4 = new DateTime($value['end']);
							if(!Locking::testRange($d1,$d2,$d3,$d4)){
								$checkConflict[]=$value['id'];
								$error = 2;
							}	
						}else{
							if($value['start'] == $toAdd['start'] && $value['end'] == $toAdd['end']){
								return array('success'=>true,'code'=>101);
							}else{
								$k=$key;
								$code = 102;
							}
						}
					}
				}
				if($error==2){
					return array('success'=>false,'code'=>2,'ids'=>$checkConflict);
				}
				return array('success'=>true,'code'=>$code,'key'=> $k);
			}catch(Exception $e){
				return array('success'=>false,'code'=>3);
			}
		}
	    function testRange($ds1,$de1,$ds2,$de2){
	    	$resp = false;
	    	if($ds1 > $de2 || $de1 < $ds2)
	    		return true;
	    	return false;
	    }

		public static function get_locked_posts($ids){
       	    // force ids to less than 10 by default
       	    if ( count($ids) > LOCK_POSTS_LIMIT ){
				$ids=array_slice($ids, -LOCK_POSTS_LIMIT );
			}
			$args = array('include' =>  array_keys($ids)  , 'posts_per_page' => LOCK_POSTS_LIMIT ,  'post_type'=>'any');
			return self::get_not_locked_posts($args);
       	}
       	 
       	public static function get_not_locked_posts($args, $nb_left = 0){
			// force limit inludes to less than 10
			if ( isset($args['include']) && count($args['include']) > LOCK_POSTS_LIMIT){
				$args['include']= array_slice($args['include']  , -LOCK_POSTS_LIMIT); 
			}

			$key = serialize($args) ;
			$posts =false ;  
			if ( !isset($_GET['disable_cache'])) {
				$posts = wp_cache_get($key, 'locking');
			}
			
			if ( $posts === false ){
				$posts = get_posts($args);
				wp_cache_set( $key , $posts , 'locking' , 60*10 );
			}

			return $posts;       	    
       	}

       	public static function get_post_id($post){
       	    return $post->ID;
       	}

		public static function get_saved_locked_options($page , $element ){
			$key_locked = 'locking_'.$page.'_'.$element;
			return get_option( $key_locked ,  array() );
		}

		static $tz;
       	public static function get_locked_articles($args=array(),$query_args=array()){
       		global $site_config,$force_locking;
			$page = $args['page'];
			$element = $args['element'];
			// Is it really used ( cat_slug ) ??
			if(!empty($args['cat_slug']))
				$element.='_'.$args['cat_slug'];
			$config = (!empty($site_config['locking'][$page]) && !empty($site_config['locking'][$page][$element]))?$site_config['locking'][$page][$element]:array();
			//$config = $site_config['locking'][$page][$element] or array();
			if(!empty($args['cat_id']))
				$element.='_'.$args['cat_id'];
			

			$locked = self::get_saved_locked_options($page, $element);

			$locked_articles = $ids = array();
			if (!empty($query_args["posts_per_page"])){
				self::$nb_pos = $query_args["posts_per_page"];
			}elseif (!empty($query_args["limit"])){
                self::$nb_pos = $query_args["limit"];
            }
			if(!empty($locked)){
				// calc tz once
				if ( !isset(self::$tz) ){
					$tz= get_option('timezone_string');
					if ( $tz )
						self::$tz = new DateTimeZone( $tz ) ;
					else self::$tz=false;
				}

				$now = new DateTime('now' );
				
				// set the timezone
				if( self::$tz ){
					$now->setTimezone(self::$tz);
					if ( isset($_GET['debug_locking']) ){
						echo "LOCKING NOW ";
						var_dump( $now );
					}
				}

				foreach ($locked as $v) {
					try{
						
						if( self::$tz ){
							$end = new DateTime($v['end'],self::$tz);
							$start = new DateTime($v['start'],self::$tz);
						} else {
							$end = new DateTime($v['end']);
							$start = new DateTime($v['start']);
						}
						if ( isset($_GET['debug_locking']) ){
							echo "<br/>Testing against start ".$v['start'];
							var_dump( $start );
							echo "<br/>Testing against end ".$v['end'];
							var_dump( $end );
						}
						if($start <= $now && $now <= $end){
							$ids[$v['id']] =(int) $v['position'];
							if ( isset($_GET['debug_locking']) ){
								echo "++++++++++ Locking ok +++++++++++++++";
							}									
						}
					}catch(Exception $e){
	
					}
				}
				// getter for locked articles
                $getter_locking = apply_filters( 'getter_locking' , array('Locking' , 'get_locked_posts' ) , $query_args ); 
                if ( count($ids) ){
                	$locked_articles = $getter_locking($ids);
                } else {
                	$locked_articles = array();
                }
                if ( isset($_GET['debug_locking']) ){
                	echo "<br/>Locked Ids ";
                	var_dump($ids);
                	var_dump($locked_articles);
                }

                return array ( $ids, $locked_articles ,$config ) ;
            } else {
            	return false;
            }
       	}
       	public static function get_new_locked( $locked_articles , $ids, $query_args){
       		// get locked articles
			$new_locked = array();
			
			// getter for locked articles
			$p_ID = apply_filters( 'getter_loop_locked' , array('Locking' , 'get_post_id' ) , $query_args );
			
			// Set locked articles in new array with position
			foreach($locked_articles as $post){	
				if($the_post_id= $p_ID($post)){
					$pos = $ids[$the_post_id];
					$new_locked[$pos]=$post;					
				}
			}
			// Need this ordered
			ksort($new_locked);
			return $new_locked;

       	}
		public static function get_locking_ids_v2($args=array(),$query_args=array()){

			// Get locked 
			$locked = self::get_locked_articles($args , $query_args); 
			if( !$locked ){
				// Simple getter, no locking
				$getter_not_locked = apply_filters( 'getter_not_locked' , array('Locking' , 'get_not_locked_posts' ) , $query_args );
				return $getter_not_locked($query_args );
			} else {
				list( $ids , $locked_articles , $config ) = $locked;
			}

			$max_pos=(int) (empty($config['nb_pos']))?self::$nb_pos:$config['nb_pos'];
			$max_pos=(int) (!empty($config['nb_pos_max']))? $config['nb_pos_max']: $max_pos;

			
			$new_locked = self::get_new_locked( $locked_articles , $ids, $query_args ); 

			if(get_param_global('display_locked_posts_only')){
				return $new_locked;
			}

			$force_locking = $ids;
			if(count($new_locked) < $max_pos){
				$nb_left = ($max_pos - count($new_locked));
				if(!empty($config))
					$query_args['posts_per_page'] = $nb_left;
				if(!empty($query_args['post__not_in'])){
					foreach ($query_args['post__not_in'] as $id) {
						$ids[$id]="true";
					};
				}
				$query_args['exclude'] = array_keys($ids);

				// Complement getter.. 
				$getter_not_locked = apply_filters( 'getter_not_locked' , array('Locking' , 'get_not_locked_posts' ) , $query_args ) ;
				$complement = $getter_not_locked($query_args, $locked_articles, $nb_left);
				
				// order all
				$new_locked = self::order_posts_with_locked( $new_locked , $complement, $max_pos) ; 


			}

			return $new_locked;
		}
		public static function order_posts_with_locked( $new_locked , $complement, $max_pos=false){
			// Order posts with positions
			if(!$max_pos)
				$max_pos = count($new_locked) + count($complement);
			for($i=1;$i<=$max_pos;$i++){
				if(empty($new_locked[$i]) && count($complement))
					$new_locked[$i]=array_shift($complement);
			}
			// make sure is still ordered ( can be remove as ordered here get_new_locked)
			ksort($new_locked);
			return $new_locked;
		}

	    //get_locking_ids permet l'affichage sur le front
		public static function get_locking_ids($args=array(),$query_args=array()){
			// 
			return self::get_locking_ids_v2($args, $query_args);
		}

		/**
		 * autocomplete search component
		 */
		function gen_search_article_locking($key,$args){
			$title = (!empty($args['title'])) ? $args['title'] : $key ;
			$nb_pos = (!empty($args['nb_pos'])) ? $args['nb_pos'] : self::$nb_pos ;
			echo '<h3 class="hndle">'.$title.'</h3>';
			echo '<div>';
			//get_category_by_slug('
			$paramsAjax=array();
			$catAjax=$catInAjax=array();
			foreach ($args['args'] as $k=>$v){ 
				if($k == "cat_slug"){
					if(is_array($v)){
						foreach ($v as $slug) {
							$catInAjax[]=get_id_by_slug($slug);
						}
					}else{
						$catAjax[]=get_id_by_slug($v);
					}
				}elseif($k=='cat'){
					$catAjax[]=$v;
				}else{
					$paramsAjax[$k] = $v;
					//$paramsAjax .= "\t $k:\"$v\",\n"; 					
				}
			}
			if(!empty($catAjax)){
				//$paramsAjax .= "\t cat_and:\"".implode(',',$catAjax).'"';	
				$paramsAjax['cat_and']=	implode( ',' , $catAjax ) ;		
			}
			if(!empty($catInAjax)){
				//$paramsAjax .= "\t cat_in:\"".implode(',',$catInAjax).'"';
				$paramsAjax['cat_in']= implode( ',' , $catInAjax );				
			}
			//$paramsAjax = videos
			for ($i = 0; $i < $nb_pos; $i++):
				$j = $i+1; 
		?>	 
			<div class="search-rw inside <?="search-rw-$key";?>" id="<?='search-rw-'.$key.'_'.$j;?>">
				<div class="link-search-wrapper">
					<label>
						<span class="search-label"><?=(!empty($args['desc']))?$args['desc']:"Rechercher";?> (position: <?=$j;?>) </span>
						<input type="search" id="<?="search-field-$key".'_'.$j;?>" class="link-search-field" autocomplete="off" />
					</label>
				</div>
				<div class="next_gallery link-search-wrapper">
					<ul class="list_selected_articles">
					</ul>
				<?php /*
				    <strong id="next-gallery-id">
				    	<?php if ($next_gallery_id): ?>
				    		  <a onclick="removeNextGallery();" class="remove">X</a> <?php echo get_post($next_gallery_id)->post_title; ?>
						<?php endif ?>
					</strong>
				    <input id="next_gallery" type="hidden" name="next_gallery" value="<?php echo $next_gallery_id; ?>" />*/
				?>
				</div>

		  		<span class="spinner search-rw-load" style="float:none;"></span>
				<div id="<?="search-results-$key".'_'.$j;?>" class="query-results" tabindex="0">
					<div class="query-nothing" id="<?="no-results-$key".'_'.$j;?>">
						<em>Aucun résultat trouvé pour les <?=$title;?></em>
					</div>
					<ul></ul>
					<div class="query-results-loading">
		  				<span class="spinner" style="float:none;"></span>
		  			</div>
				</div>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#<?='search-rw-'.$key.'_'.$j;?>").searchRw({
					data:<?php echo json_encode($paramsAjax); ?> ,
					func:function(e) {	
						var a = jQuery(e);
						var id = a.attr('data-id');
						if(id && !jQuery("#<?='search-rw-'.$key.'_'.$j;?> #<?='search-rw-'.$key.'_'.$j;?>_"+id).length){
							var title = a.find('.item-title').html();
							var end = start = '';
							var new_line = tplLine.replace(/{{title}}/gi, title).replace(/{{id}}/gi, id).replace(/{{key}}/gi, "<?=$key;?>").replace(/{{j}}/gi, "<?=$j;?>").replace(/{{end}}/gi,end).replace(/{{start}}/gi,start);
							new_line = jQuery(new_line).liSelected();
							jQuery('#<?='search-rw-'.$key.'_'.$j;?> .list_selected_articles').append(new_line);
						}
						return false;
					}
				}).show();
			});
			</script>
		<?php endfor;
		echo '<div class="clear"></div></div>'; 
		}

        function cat_lock($parent=0){
        	$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');
    		$menu_items = wp_get_nav_menu_items($menu_id);
    		$all_cat = [];   
    		foreach ($menu_items as $menu_item){
				if($menu_item->menu_item_parent == $parent ){
					$all_cat[]=$menu_item;	
				}		
			}
    		return $all_cat;
		}

        function locking_admin() {
            $chars=array('\\','\"',"\'");		

            $locking = get_param_global('locking');
            if($locking && !get_option('locking')){
            	$locking_opt = array();
            	foreach ($locking as $key => $value){
            		$lock = array();
            		foreach ($value as $k => $v){
            			if(is_array($v)){
            				$lock[$k] = 0;
            			}
            		}
            		$locking_opt[$key]=$lock;
            	}
            	add_option('locking',$locking_opt);
            }
            $locking_opt = get_option('locking');
            //$locking_byId = get_option('lockingById');
			$locking_page_id = false;
			if (isset($_REQUEST['locking_page']) && !empty($locking[$_REQUEST['locking_page']])): 
				$locking_page_id=$_REQUEST['locking_page'];
			endif;
			if (!$locking_page_id) {
				$locking_keys = array_keys($locking);
				$locking_page_id = $locking_keys[0];
			}
			if($locking_page_id){
				$locking_page=$locking[$locking_page_id]; //Verrouillage parametré dans la page function.php
			}
			$locking_page_title=(!empty($locking_page['title']))?$locking_page['title']:$locking_page_id;
			unset($locking_page['title']);
			?>
	
			<div class="wrap">

			<h2><?php _e('Réglage verrouillage',REWORLDMEDIA_TERMS )  ; ?></h2>
			<form id="locking_form" method="get" class="validate">
				<input type="hidden" name="page" value="<?=$_REQUEST['page'];?>" />
				<div class="tablenav top">
					<div class="alignleft actions bulkactions">
						<label for="locking_page" class="screen-reader-text"><?php _e('Page',REWORLDMEDIA_TERMS )  ; ?></label>
						<span class="spinner" style="float: none; visibility: visible; display: block;"></span>
						<select name="locking_page" id="locking_page" class="hidden">
							<!--option><?php _e('Selectionne une page',REWORLDMEDIA_TERMS )  ; ?></option-->
							<?php 
							foreach ($locking as $key => $value): 
								if($key!="sous-category" || $locking_page_id=="sous-category"):
									$option_title = (!empty($value['title']))?str_replace("%cat%", '', $value['title']):$key;
							?>
							    <option value="<?php echo $key; ?>" <?=($key==$locking_page_id)?'selected="selected"':'';?>><?php echo $option_title; ?></option>

							<?php
								endif;
							endforeach;
							?>
						</select>
					</div>
				</div>
			</form>
        		<?php 
        		if (!empty($locking_page)): ?>
				<form id="locking_<?=$locking_page_id;?>" method="post" class="validate locking_pages">
					<input type="hidden" name="page_id" id="input_page_id" value="<?=$locking_page_id;?>" />
					<div class="wp-list-table widefat fixed striped elements" style="border-color:#CFCFCF;">
					<script type="text/javascript">
						var locking_ids = [];
					</script>
					<?php
						RW_Utils::add_search_rw();
				        $custom_css = "
				        		.tablenav{
				        			margin-bottom:15px;
				        		}
				        		.validate .striped{
				        			border:1px solid #CFCFCF;
				        			background:#FFF;
									-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.07);
									box-shadow: 0 1px 2px rgba(0,0,0,.07);
				        		}
				        		.validate .striped h2{
				        			margin:.83em;
				        		}
				        		.validate .striped > div{
				        			border-top:1px solid #CFCFCF;
				        			padding: 0 10px;
				        			/*margin:0 -12px;*/
				        		}
				        		.validate .striped > div:nth-child(even){
				        			background-color:#f9f9f9;
				        		}
				                .search-rw{
			                        display:inline-block;
			                        width: 49%;
									max-width: 49%;
									margin: 10px 0% 10px 0.5%;
			                        padding:10px 0;
			                        vertical-align: top;
				        			background-color:#fff;
				        		}
				                .search-rw > div{
				                	margin:0 10px;
				                }
				                .search-rw .list_selected_articles li{
				                	cursor: default;
				                }
				                .search-rw li.loading:before{
								  content: 'loading...';
								  display: block;
								  position: absolute;
								  background: rgba(205,205,205,0.8);
								  width: 100%;
								  color: #000;
								  line-height: 40px;
								  height: 100%;
								  left: 0;
								  top: 0;
								  text-align: center;
								}
				        		.search-rw .success .input-daterange input[type=text]{
				        			border:1px solid transparent; font-weight:bold; box-shadow:none; width:130px; background: transparent;cursor:pointer;
				        		}
				        		.search-rw .error .input-daterange input[type=text]{
				        			border:1px solid #d9534f; background: #f2dede;
				        		}";

				        wp_add_inline_style( 'search-rw-css', $custom_css );

        				if(!empty($locking_page_title) && $locking_page_id!="sous-category"){	
							echo '<h2>'.$locking_page_title.'</h2>';
        				}
        				$locked_ids = array();
        				$now = new DateTime();
        				$is_post = true;
        				if(preg_match("/^(category|sous-category)$/",$locking_page_id)){
        					$parentId= (!empty($_REQUEST['cat']) && $locking_page_id=="sous-category")?$_REQUEST['cat']:0;
							$categories = Locking::cat_lock($parentId);
							if($locking_page_id=="sous-category")
								echo '<h2>'.str_replace("%cat%", " : ".get_the_category_by_ID($parentId), $locking_page_title).'</h2>';
							$locking_page_new=array();
							foreach ($locking_page as $key => $value) {
								foreach ($categories as $v) {
									$new_value = $value;
									$new_value['title']=(!empty($value['title']))?str_replace("%cat%", $v->title, $value['title']):"$key $v->title";
									$new_value['cat_name']=$v->title;
									$new_value['id']=$v->ID;
									if(empty($value['args']))
										$new_value['args']=array();
									$new_value['args']['cat']=$v->object_id;

									$locking_page_new[$key.'_'.$v->object_id]=$new_value;
								}
							}
							$locking_page = $locking_page_new;
						}	
						//var_dump( $locking_page);exit();		            
						foreach ($locking_page as $key => $value): 
							if(empty($value['id']))
								echo "<div>";
							else
								echo '<div class="categorie"  data-cat_id="'.$value['id'].'" data-cat_name="'.$value['cat_name'].'">';
							Locking::gen_search_article_locking($key, $value);
	        				$key_opt = 'locking_'.$locking_page_id.'_'.$key;
							$locked=get_option($key_opt);
							$expires=array();
							if($locked && is_array($locked)){
								foreach ($locked as $k => $v){
									if(!empty($v['id'])){
										$d_end = (!empty($v['end']))?new DateTime($v['end']):new DateTime( '2014/12/31 00:00' );
										if($now > $d_end){
											$expires[] = $k;
										}else{
											$locked_ids[$v['id']] = true;											
										}
									}
								}
								$is_post = (!empty($value['args']) && !empty($value['args']['post_type']))?true:false;
								if(!empty($expires)){
									foreach ($expires as $i => $k) {
										$toR = $locked[$k];
										$r_id = $toR['id'];
										$post_toR=($is_post)?get_post_meta($r_id, '_post_rw_locked', true):false;
										if(is_array($post_toR)){
											//Begin remove from post_meta
											foreach ($post_toR as $i => $el) {
												if($el['element']==$key && $el['page']==$locking_page_id && $el['position']==$toR['position']){
													unset($post_toR[$i]);
													if(empty($post_toR)){
														unset($post_toR);
													}
													if(empty($post_toR)){
														delete_post_meta($r_id, '_post_rw_locked');
													}else{
														update_post_meta($r_id, '_post_rw_locked', $post_toR );
													}
												}
											}
											//End remove from post_meta
										}elseif($post_toR){
											delete_post_meta($r_id, '_post_rw_locked');
										}

										unset($locked[$k]);
									}
									update_option($key_opt,$locked,false);
									wp_cache_delete( $key_opt, 'options' );
									//wp_cache_delete('alloptions' , 'options');
									do_action('update_locking' , $key_opt );

								}
							}
						?>
							<script type="text/javascript">
								locking_ids.push({key:"<?=$key;?>",articles:<?=json_encode($locked);?>});
							</script>
							</div>
						<?php
						endforeach;?>
					</div>
				</form>
        		<?php 
        			
        		endif;
        		?>
			<script type="text/javascript">
				var remove_btn_html = '<a class="remove">X</a>';
				var tplLine = '<li id="search-rw-{{key}}_{{j}}_{{id}}" data-id="{{id}}" data-key="{{key}}" data-pos="{{j}}">'+remove_btn_html;
				var dateRange='<div class="input-daterange input-group" id="datepicker{{key}}_{{j}}_{{id}}"><input type="text" READONLY data-id="{{key}}_{{j}}_{{id}}" id="start_{{key}}_{{j}}_{{id}}" class="input-sm form-control start" value="{{start}}" /><span class="input-group-addon">à</span><input type="text" data-id="{{key}}_{{j}}_{{id}}" id="end_{{key}}_{{j}}_{{id}}" class="input-sm form-control end" value="{{end}}" READONLY /></div>';
				tplLine += '<span class="newsletter_article_title">{{title}}</span>'+dateRange+'<!--span class="dashicons dashicons-yes"></span><span class="dashicons dashicons-no"></span--></li>';
							
				(function($){
					$.fn.liSelected = function() {
						return this.each(
							function(){		
								var a=$(this);
								var keyLi = a.data('key');
								var posLi = a.data('pos');
								var idLi = a.data('id');
								function verifDate(d1,d2){
    								re = /^(\d{1,2})\/(\d{1,2})\/(\d{4}) (\d{2})\:(\d{2})$/;
									reg1=d1.match(re);
									reg2=d2.match(re);
									//console.log(reg1);
									//console.log(reg2);
									try{
										start=reg1[3]+'-'+reg1[2]+'-'+reg1[1]+' '+reg1[4]+':'+reg1[5];
										//console.log(start);
										end=reg2[3]+'-'+reg2[2]+'-'+reg2[1]+' '+(reg2[4]-1)+':59';
										//console.log(end);
										date1 = new Date(reg1[3] ,reg1[2] ,  reg1[1] ,  reg1[4] , reg1[5] );
										date2 = new Date(reg2[3] ,reg2[2] ,  reg2[1] ,  parseInt(reg2[4])-1 , 59);
										/*console.log(date1);
										console.log(date2);*/
										if(date2>date1)
											return [start,end];

									}catch(e){
										console.log(e);
									}
									return false;
								}
								function checkMinDate(c){
									//console.log(jQuery(c));
									return 0;
								}
								function checkMaxDate(c){
									//console.log(jQuery(c).data("input"));
									return false;
								}
								function setDefault(c){
									//console.log(jQuery(c).data("input"));
									return 0;
								}
								function sendData(params){
									params=$.extend({
										action: 'locking_ajax',
										_ajax_linking_nonce:jQuery('#_ajax_linking_nonce').val()
								    },params);
								    /*console.log('Sending data');
								    console.log(  params );*/
									jQuery.post("/wp-admin/admin-ajax.php",params, function(data){
										respClass=(data.success)?'success':'error';
										if(obj = data.option){
											objId = "#search-rw-"+obj.element+"_"+obj.position+"_"+obj.id;
											if(data.removed)
												$(objId).fadeOut(400).remove();
											else
												$(objId).removeAttr('class').addClass(respClass);
										}
										
									},"json");
								}
								inpRange =  a.find(".input-daterange input");
								aRemove =  a.find(".remove");
								inpRange.datetimepicker({
								    format: "d/m/Y H:00",
								    formatDate:"d/m/Y H:00",
								    lang: "fr",
								    defaultDate:new Date(),
								    todayHighlight: true,
								    //timepicker:false,
								    scrollMonth:false,
								    scrollInput:false,
								    validateOnBlur:true,
								    allowBlank:true,
								    closeOnDateSelect:true,
								    startDate:new Date(),
								    onClose:function(ct,inp){
										var $parentId = jQuery("#search-rw-"+jQuery(inp).data('id'));
										if($parentId.hasClass('pending')){
											$parentId.removeAttr('class').addClass('success');
										}
								    },
									onShow:function(ct, inp){
										var $parentId = jQuery("#search-rw-"+jQuery(inp).data('id'));
										pending = ($parentId.hasClass('success'))?'pending':'';
										$parentId.removeAttr('class').addClass(pending);
										this.setOptions({
	    									maxDate:checkMaxDate(this),
											minDate:checkMinDate(this)
											// TODO: Fix les dates min et max
	    									//maxDate:(jQuery(input).hasClass('start') && inpEnd.val())?inpEnd.val():false,
											//minDate:(jQuery(input).hasClass('end') && inpStart.val())?inpStart.val():0
										})
									},
									onChangeDateTime:function(ct,inp){
										var $parentId = jQuery("#search-rw-"+jQuery(inp).data('id'));
										inpEnd = $parentId.find(".input-daterange input.end");
										inpStart = $parentId.find(".input-daterange input.start");
										console.log( 'Verif' + inpStart + ' '+inpEnd);
										if(dateResp=verifDate(inpStart.val(),inpEnd.val())){
											$parentId.removeAttr('class').addClass('loading');
											sendData({
												page:jQuery('#input_page_id').val(),
												element:keyLi,
												position:posLi,
												start:dateResp[0],
												end:dateResp[1],
												id:idLi
											});
										} else {
											console.log('Verif date Failed ');
										}
									}
								});
								aRemove.click(function(){
									$(idLi).addClass('loading');
									sendData({
										page:jQuery('#input_page_id').val(),
										element:keyLi,
										position:posLi,
										remove:true,
										id:idLi
									});
								});
								return false;
							}
						);
					};
				})(jQuery);
				jQuery(function($){
					$("#locking_page").on('change.locking',function(){
						var $locking_form = $('#locking_form');
						$locking_form.submit();
						$('.spinner' , $locking_form ).show();
						$(this).hide();
					});
					$.each(locking_ids,function(k, val){
						if(val.key && val.articles){
							var idtocomp="search-rw-"+val.key+"_",
							lockid = val.key,
							re = /^(\d{4})\-(\d{1,2})\-(\d{1,2}) (\d{2}\:\d{2})$/;
							$.map(val.articles,function(a){
								var art_id=a.id,
								pos = a.position,
								title=article_title(art_id);
								var end = function(){
									var dateEnd= a.end.match(re);
									if(dateEnd && dateEnd.length==5){
										return dateEnd[3]+'/'+dateEnd[2]+'/'+dateEnd[1]+' '+dateEnd[4];
										//return dateEnd[2]+'/'+dateEnd[1]+'/'+dateEnd[0];
									}
									return a.end;
								}
								var start = function(){
									var dateStart = a.start.match(re);
									if(dateStart && dateStart.length==5){
										return dateStart[3]+'/'+dateStart[2]+'/'+dateStart[1]+' '+dateStart[4];
									}
									return a.start;
								}	
								//start = (a.start && new Date(a.start))?new Date(a.start).format0():"";		
								var new_line = tplLine.replace(/{{title}}/gi, title).replace(/{{id}}/gi, art_id).replace(/{{key}}/gi, lockid).replace(/{{j}}/gi, pos).replace(/{{end}}/gi,end).replace(/{{start}}/gi,start);
								new_line = jQuery(new_line).addClass('success').liSelected();
								jQuery('#'+idtocomp+pos+' .list_selected_articles').append(new_line);
							});
						}
					});
					<?php if(preg_match("/^(category|sous-category)$/",$locking_page_id)): ?>
					if($('form.locking_pages .elements > div.categorie').length > 0){
						var selectCat = [];
						var checkCat = [];
						$('form.locking_pages .elements > div.categorie').each(function(){
							cat_id=$(this).data('cat_id');
							if(!checkCat[cat_id]){
								selectCat.push({
									name:$(this).data('cat_name'), 
									id:cat_id
								});
								checkCat[cat_id]=true;
							}
							//$(this).hide();
						});
						newSelect='<select name="cat" id="cat_locked" class="postform">';
						$.each(selectCat,function(k,val){
							newSelect+='<option value="'+val.id+'">'+val.name+'</option>';
						});
						newSelect+='</select>';
						newActions = $('<div class="alignleft actions"></div>');
						$(newSelect).on('change.locking',function(){
							$('form.locking_pages .elements > div.categorie').hide();
							$('form.locking_pages .elements > div[data-cat_id='+$(this).val()+']').slideDown();							
						}).appendTo(newActions);
						<?php if($locking_page_id=="category" || $locking_page_id=="sous-category"  ): ?>
						butCat=$('<input type="submit" id="post-query-submit" class="button" value="<?php _e('Voir les sous catégories', REWORLDMEDIA_TERMS ); ?>" />');

						butCat.on('click.locking',function(){
							$("#locking_page").append($('<option value="sous-category">sous-category</option>')).val('sous-category');
							$("#locking_form select,#locking_form input").hide();
							$('#locking_form .spinner').show();
							$('#locking_form').submit();
							return false;
						}).appendTo(newActions);
						<?php endif; ?>
						$('#locking_form .tablenav').append(newActions);
						$cat_locked=$("#cat_locked");
						<?php if(!empty($_GET['cat']) && $locking_page_id=="category"){ ?>
						if($cat_locked.find("option[value=<?=$_GET['cat'];?>]").length>0){
							$cat_locked.val(<?=$_GET['cat'];?>);
						}
						<?php } ?>
						$cat_locked.trigger('change.locking');
					}
					<?php endif; ?>
				    window.onbeforeunload = confirmExit;
				    function confirmExit() {
				    	confirmExit=0;
				    	$('ul.list_selected_articles li').each(function(){
				    		if(!$(this).hasClass('success')){
				    			confirmExit=+1;
				    		}
				    	});
				    	if(confirmExit > 0){
				    		return "Vous avez "+confirmExit+" verrouillage(s) non sauvegardé, voulez-vous vraiment quitter ?";
				    	}
				    }
					$('#locking_form .spinner').hide();
					$('#locking_page').show();
				});
				function article_title(id){
					ids = {
					<?php
					if(count($locked_ids)>0){
						$ids_search= implode(",", array_keys($locked_ids));
						if($is_post){
							global $wpdb;
							$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE id in (".$ids_search.");");
							foreach ( $posts as $post ){
								echo "\t '$post->ID' : ".json_encode($post->post_title).", \n";
							}							
						}else{
							$new_search = new CF_search_media(CF_search::COUNT_STRICT);
							$in_ids=array_keys($locked_ids);
							$new_search->add_in_id_media($in_ids);
							$new_search->encode();
							$posts = ($new_search->count())?$new_search->search(1,count($in_ids)):[];
							//$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE id in (".$ids_search.");");
							foreach ( $posts as $post ){
								echo "\t '".$post->get_id()."' : ".json_encode($post->get_title()).", \n";
							}							
						}
					}
					?>
					};
					return (ids[id])?ids[id]:'pas de titre';
				}
			</script>
			</div>
		<?php
        }
    }
    new Locking();

?>