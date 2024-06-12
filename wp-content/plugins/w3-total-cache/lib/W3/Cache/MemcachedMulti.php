<?php

/**
 * PECL Memcached class
 */
if (!defined('W3TC')) {
    die();
}
define('EXPIRE_MEMCACHE_MULTI' , 60*60*2 );
w3_require_once(W3TC_LIB_W3_DIR . '/Cache/Memcached.php');

/**
 * Class W3_Cache_MemcachedMulti
 */
class W3_Cache_MemcachedMulti extends W3_Cache_Memcached {
	private $current_url = false;
	private $called_keys = array();
	function __construct($config) {
        parent::__construct($config);
        // detect url
        add_action('parse_request' , array( $this , 'parse_request' ) , 1 ) ; 


        // detect url
        add_action('shutdown' , array( $this , 'shutdown' ) , 1 ) ;


        return true;
    }

    function parse_request($query){
    	$this->current_url = $this->_host.md5( $_SERVER['REQUEST_URI'] ) ;
    	

    	$keys = $this->get( $this->current_url , 'multi_keys' );
    	// now get all the keys
    	$to_get = array();
    	//var_dump( $keys );
    	$groups = array();
    	foreach ( $keys as $key_cache) {
    		list( $key , $group) = $key_cache;
    		$keycache = $this->get_item_key($key) . '_' . $this->_blog_id ;
    		if ( !in_array( $keycache, $to_get )){
    			$to_get[] = keycache;
    		}
    		$keygroup= $this->_get_key_version_key($group);
    		if ( !in_array( $keygroup, $to_get )){
    			$to_get[] = $keygroup;
    		}
    	}

    	//var_dump( $to_get );
    	$this->_all = $this->_memcache->get($to_get , false );


    }

    function get_with_old($key, $group = '0') { 
    	// save the key
    	if ($group!='multi_keys')
    		$this->called_keys[]=array($key, $group);

    	$has_old_data = false;
    	$key_item = $this->get_item_key($key). '_' . $this->_blog_id;
    	if (  isset($this->_all[$key_item ]) ){
    		$v = $this->_all[$key_item];
    		// we dont need it anymore.. free some space
    		unset($this->_all[$key_item]);

    		if (!is_array($v) || !isset($v['key_version'])){
               	return array(null, $has_old_data);
    		}
            
            $key_group = $this->_get_key_version_key($group) ;
            if (  isset( $this->_all[$key_group])){
            	$key_version = $this->_all[$key_group];
		        
		        if ($v['key_version'] == $key_version){
		        	//echo "$key_item from cache";
		            return array($v, $has_old_data);
		        }

		        if ($v['key_version'] > $key_version) {
		            $this->_set_key_version($v['key_version'], $group);
		            return array($v, $has_old_data);
		        }
            }
            // let the process calculate again..
            return array(null, $has_old_data);

    	} 
    	// ask memcached
    	return parent::get_with_old($key, $group);

    }

    function shutdown() { 
    	$this->set( $this->current_url , $this->called_keys , EXPIRE_MEMCACHE_MULTI , 'multi_keys');

    }

}
