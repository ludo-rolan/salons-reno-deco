<?php

class ScriptsRecorder extends WP_Scripts {
	
	public $wp_scripts; 
	public $dep;


	// new recording
	public function flush(){
		$this->dep = array();
	}

	public function dump() {
		print_r($this->wp_scripts);
	}

	// add a script
	public function add( $handle, $src, $deps = array(), $ver = false, $args = null ) {
		if ( !isset( $this->dep[$handle] ) )
			$this->dep[$handle] = new _WP_Dependency( $handle, $src, $deps, $ver, $args );
		return  $this->wp_scripts->add(  $handle, $src, $deps , $ver , $args  ) ;
	}


	public function localize( $handle, $object_name, $l10n ) {
		$before = $this->get_data( $handle , 'data'  );
		$return = $this->wp_scripts->localize($handle, $object_name, $l10n) ;
		$after = $this->get_data( $handle , 'data' );


		$this->dep[$handle]->extra['data']= isset($this->dep[$handle]->extra['data'])  ? $this->dep[$handle]->extra['data']."\n" : '' ;
		$this->dep[$handle]->extra['data'] .= str_replace($before."\n" , '' , $after); 
		return $return;
		
	}

	private function _init_dep($handle){
		if ( isset( $this->wp_scripts->registered[$handle] )){
			$this->dep[$handle] = clone  $this->wp_scripts->registered[$handle];
			$this->dep[$handle]->extra['data']= '';
			// force in footer
			$this->dep[$handle]->extra['group']=1;
		}
	}


	// add data
	public function add_data( $handle, $key, $value ) {
		if ( !isset( $this->dep[$handle] ) ){
			$this->_init_dep($handle);
		}

		$this->dep[$handle]->add_data($key,$value); 
		
		return $this->wp_scripts->add_data( $handle, $key, $value );
		
	}

	
	public function get_data( $handle, $key ) {
		
		$data =  $this->wp_scripts->get_data( $handle, $key ) ;
		return $data;
	} 	
	

	public function __construct($wp_scripts) {
		$this->wp_scripts = $wp_scripts;
		$this->dep = array();
	}

	public function __call($method, $arguments){
		if ( is_callable($this->wp_scripts, $method)) {
            return call_user_func_array(array($this->wp_scripts, $method), $arguments);
        }
        throw new Exception(
            'Undefined method - ' . get_class($this->wp_scripts) . '::' . $method
        );
	}

	public function __get($property) {
        if (property_exists($this->wp_scripts, $property)) {
            return $this->wp_scripts->$property;
        }
        return null;
    }

    public function __set($property, $value){
        $this->wp_scripts->$property = $value;
        return $this;
    }

    public function enqueue( $handles ) {
		$this->wp_scripts->enqueue($handles);
	}

}

class StylesRecorder extends WP_Styles {
	
	public $wp_styles; 
	public $dep;

	// new recording
	public function flush(){
		$this->dep = array();
	}
	public function dump() {
		print_r($this->wp_styles);
	}

	// add a script
	public function add( $handle, $src, $deps = array(), $ver = false, $args = null ) {
		if ( !isset( $this->dep[$handle] ) )
			$this->dep[$handle] = new _WP_Dependency( $handle, $src, $deps, $ver, $args );

		$return = $this->wp_styles->add(  $handle, $src, $deps , $ver , $args  ) ;
		$this->registered =  $this->wp_styles->registered;
		return $return;
	}

	// add data
	public function add_data( $handle, $key, $value ) {
		if ( !isset( $this->dep[$handle] ) )
			return false;

		$this->dep[$handle]->add_data($key,$value); 
		
		$return = $this->wp_styles->add_data( $handle, $key, $value );
		$this->registered =  $this->wp_styles->registered;
		return $return;
	}


	public function __construct($wp_styles) {
		$this->wp_styles = $wp_styles;
		$this->dep = array();
	}

	public function __call($method, $arguments){
		if ( false && is_callable($this->wp_styles, $method)) {
            return call_user_func_array(array($this->wp_styles, $method), $arguments);
        }
        throw new Exception(
            'Undefined method - ' . get_class($this->wp_styles) . '::' . $method
        );
	}

	public function __get($property) {
        if (property_exists($this->wp_styles, $property)) {
            return $this->wp_styles->$property;
        }
        return null;
    }

    public function __set($property, $value){
        $this->wp_styles->$property = $value;
        return $this;
    }

    public function enqueue( $handles ) {
		$this->wp_styles->enqueue($handles);
	}

}

class Rw_Cache {

	private static $_instance;
	public $key_prefix;
	public $key_partners;
	private $groups = [];

	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new Rw_Cache();
		}
		return self::$_instance;
	}

	public function __construct(){

		$prefix_key = rw_is_mobile()? "m_" : "d_";
		$prefix_key .= SITE_SCHEME ;
		$prefix_key = apply_filters('object_cache_key_prefix', $prefix_key);
		$this->key_prefix = get_current_blog_id().'_'.$prefix_key;
		
	}


/**
 * [echo_from_cache description]
 * @param  $key      : cache key
 * @param  $group    : cache group
 * @param  $duration : cache duration
 * @param  $callback : callback to cache
 * @return            echo the cached data
 */
	public function echo_from_cache( $key , $group , $duration, $callback){
		$key = get_cache_key($key);
		// check key html
		$data = wp_cache_get($key , $group);

		if ( is_array($data) && !isset($_GET['disable_cache'])) {
			// check key js/css
			echo $data[0];
			if(isset($_GET['debug_objet_cache'])){
				echo "from_cache  key = $key , goup $group , duration $duration  (echo_from_cache)" ;
			}
			if( !empty($data[1]) ){
				$wp_scripts = wp_scripts();


				foreach ( $data[1] as $handle=> $dep){
					if(  !isset( $wp_scripts->registered[$handle] )){
						$wp_scripts->registered[$handle] = $dep;
						$wp_scripts->queue[] = $handle;
					} else {
						// merge
						if ( isset($dep->extra['data'])){
							if( isset( $wp_scripts->registered[$handle]->extra['data'] )){
								$orgin_extra = $wp_scripts->registered[$handle]->extra['data'];
								$merged_extra = $orgin_extra ."\n". $dep->extra['data'] ;
								$wp_scripts->registered[$handle]->extra['data'] = $merged_extra;
							} else {
								$wp_scripts->registered[$handle]->extra['data'] = $dep->extra['data']  ;
							}
							// TODO : be careful!
							if( isset( $dep->extra['group'] )){
								$wp_scripts->registered[$handle]->extra['group'] = $dep->extra['group']  ;
							}
							
						}
						
					}
				}




			}

			if( !empty($data[2]) ){
				$wp_styles = wp_styles();
				$wp_styles->registered = array_merge( $wp_styles->registered  , $data[2] ) ;
				$wp_styles->queue = array_merge( $wp_styles->queue  , array_keys($data[2]) ) ;

			}

		}  else {
			// save enqueued scripts
			
			if(isset($_GET['debug_objet_cache'])){
				echo "no_from_cache  key = $key , goup $group , duration $duration  (echo_from_cache)" ;
			}

			global $wp_scripts;

			$wp_scripts = wp_scripts();
			$wp_scripts = new ScriptsRecorder( $wp_scripts );
	
			global $wp_styles;

			$wp_styles = wp_styles();
			$wp_styles = new StylesRecorder( $wp_styles );
			
			
			// start buffer
			ob_start();
			$callback();			
			$data = ob_get_contents();
			ob_end_clean();

			
			$js = $wp_scripts->dep;
			$css = $wp_styles->dep;


			$wp_scripts = $wp_scripts->wp_scripts;
			$wp_styles = $wp_styles->wp_styles;
			

			wp_cache_set($key , [ $data, $js , $css ]  , $group , $duration );
			echo $data;
		}
	}
	/**
	 * get_data_from_cache :  Utiliser pour cacher des objets
	 * @param  int | string $key    - La clé de cache
	 * @param  string  $group       - group de cache
	 * @param  int  $duration       - La durée de cache
	 * @param  function  $callback  - call back function
	 * @param  boolean $use_key_prefix - S'il faut utiliser le prefix avec le type de devices, les parteners ...
	 * @return Mixed                Le type de l'objet à cacher
	 */
	public function get_data_from_cache( $key , $group , $duration, $callback, $use_key_prefix = false ){
		
		if( $use_key_prefix ){
			$key = get_cache_key($key);
		}else{
			$key = get_current_blog_id().'_'.$key;
		}

		$cached_data = wp_cache_get($key , $group);
		
		if ( !empty($cached_data) && !isset($_GET['disable_cache'])) {
			if(isset($_GET['debug_objet_cache'])){
				echo "from_cache  key = $key , goup $group , duration $duration  (get_data_from_cache)" ;
			}
			return $cached_data;
		}  else {	
			$data = $callback();
			if(isset($_GET['debug_objet_cache'])){
				echo "no_from_cache  key = $key , goup $group , duration $duration  (get_data_from_cache)" ;
			}
			wp_cache_set($key, $data, $group, $duration );
			return $data;
		}
	}

	/**
	 * Gets the key partners.
	 *
	 * @return     string  The key partners.
	 */
	public function get_key_partners(){
		global $site_config_js;
		if(isset($this->key_partners)){
			return $this->key_partners;
		}else if(isset($site_config_js['partners'])){
			$this->key_partners = substr( md5(serialize($site_config_js['partners'])) , 0, 6 ).'_' ;
			return $this->key_partners ;
		}else{
			return '' ;
		}
	}

	/**
	 * Gets the cache key.
	 *
	 * @param      <String>  $key    The key
	 *
	 * @return     <String>  The cache key.
	 */
	public function get_cache_key($key){
		return $this->key_prefix.$this->get_key_partners().$key;
	}

	private function get_option_name($group){
		return 'cg_'.$group ;
	}

    public function  get_cache_group_inc($group ){
	    
	    if( isset( $this->groups[$group]) ) {
	        return $this->groups[$group] ;
	    }
	    $option_name = $this->get_option_name($group);
	    return $this->groups[$group]  = $group .'_'. get_option($option_name  , 0) ;
	}

	public function  update_cache_group_inc($group){
		
		$option_name = $this->get_option_name($group) ;
	    $current =  get_option( $option_name , 0 ) ;
	    $current = (int)$current >= 0 ? (int) $current + 1 :  0;
	    
	    update_option($option_name , $current ) ;
	    // update the current number
	    $this->groups[$group]  =  $group  .'_'.  $current ; 

	}

	/**
	 * delete_cache_object_data Pour supprimer le cache objet dont la clé est de type [id_blog]_[cle_cache] 
	 * $key - la clé de cache
	 * $group - le groupe de cache
	 * @return  boolean : True on successful removal, false on failure (same result as wp_cache_delete ) .
	 */
	public function delete_cache_object_data( $key, $group){
		$key = get_current_blog_id().'_'.$key;
		return wp_cache_delete ( $key , $group);
	}
}

/* ============================================================*/
/*=====================Cache Object============================*/
/*=============================================================*/

function reset_scripts(){
	global $wp_scripts;
	$wp_scripts = null;
}

function echo_from_cache( $key , $group , $duration, $callback){
	$rw_cache_object = Rw_Cache::get_instance();
	$rw_cache_object->echo_from_cache( $key , $group , $duration, $callback);
} 

function get_data_from_cache( $key , $group , $duration, $callback, $use_key_prefix = true ){
	$rw_cache_object = Rw_Cache::get_instance();
	return $rw_cache_object->get_data_from_cache( $key , $group , $duration, $callback, $use_key_prefix);
} 

function get_cache_key($key){
	$rw_cache_object = Rw_Cache::get_instance();
	return $rw_cache_object->get_cache_key( $key );
}

function nginx_clearcache($url) {

	//if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && strpos( strtolower( $_SERVER['SERVER_SOFTWARE'] ) , 'nginx' ) !== false  ) {
		if (!function_exists('curl_init')) { die('cURL is not installed, cant purge cache' ); }
		
		// by pass cloudflare and send query directly to 127.0.0.1
		$hostname = parse_url($url ,  PHP_URL_HOST );
		$url_with_ip = str_replace ( $hostname.'/' , '127.0.0.1/purge/' , $url ) ;
		
		$ch = curl_init($url_with_ip);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Host: '.$hostname) );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		
		if (!curl_exec($ch)) { return false; }
	//}
	return true;
}

 /**
 * [modifier le cache nginx ]
 * @param  $duration : duration 
 * @return null
 */ 

function rw_send_cache_headers($duration){
    if ( !defined('SENT_CACHE_HEADERS') ){
        header("X-Accel-Expires: ".$duration);
        header('Cache-Control: max-age='.$duration,true);
        define('SENT_CACHE_HEADERS' , true);
    }
}

 /**
 * [modifier le cache nginx des pages sitemap]
 * @param  $request : request 
 * @return null
 */ 

function rw_setup_caching_sitemap($request){
	switch ( $request['feed'] ) {
	    case 'sitemap-news': $cache = 60*5 ;break;
	    case 'feed_rss_rw' : $cache=60*30 ; break ; ///google news 
	    case 'rss_msn' : $cache=60*5 ; break ;
	    case 'feed' : $cache=60*60 ; break ;

	   // case 'sitemap':
		//sitemap-posttype-post
		//sitemap-posttype-page
		//sitemap-home
	    default:
	        $this_month = date("Ym" );
	        if ( isset($request['m']) && $this_month == $request['m'] ){
	            $cache = 60*60*24 ;
	        }elseif(!empty($request['m'])) {
	            $cache = 60*60*24*30 ;
	        }else{
	            $cache = 60*60*24 ;
	        }
	}
	rw_send_cache_headers($cache);
}

add_action( 'sitemap_request'  , 'rw_setup_caching_sitemap');
/**
 * delete_cache_object_data Pour supprimer le cache objet dont la clé est de type [id_blog]_[cle_cache] 
 * $key - la clé de cache
 * $group - le groupe de cache
 * @return  boolean : True on successful removal, false on failure (same result as wp_cache_delete ) .
 */
function delete_cache_object_data( $key, $group){
	$rw_cache_object = Rw_Cache::get_instance();
	return $rw_cache_object->delete_cache_object_data( $key, $group);
}


/**
 * Implémentation cache longue durée | Ajout date sur le header sur les articles 
 * selon la date de dernière modification
 * @return  null 
 */

add_action("wp", function(){
	global $post ;
	if(is_single()){
		$post_modified = strtotime($post->post_modified);
		$time= time() ; 
		$time_diff = $time - $post_modified  ;
		if($time_diff >= 60*60*24*30*3 ){
			//1 mois de cache pour les articles superieurs à 3 mois
			rw_send_cache_headers(60*60*24*30);
		}else{
			//4 heures de cache pour les articles de moins de 3 mois.
			rw_send_cache_headers(60*60*4);
		}
	}

});


function rw_cache_get($id, $group = 'default') {
	
	if(isset($_GET['disable_cache'])){
		return false ;
	}

	if(SITE_SCHEME == 'https'){
		$id = '1' . $id ;
	} 
	return wp_cache_get($id, $group);
}


function rw_cache_set($id, $data, $group = 'default', $expire = 0) {

	if(SITE_SCHEME == 'https'){
		$id = '1' . $id ;
	} 
	return wp_cache_set($id, $data, $group, $expire) ;
}

function rw_cache_delete($id, $group = 'default') {

	if(SITE_SCHEME == 'https'){
		$id = '1' . $id ;
	} 
	return wp_cache_delete($id, $group ) ;
}

