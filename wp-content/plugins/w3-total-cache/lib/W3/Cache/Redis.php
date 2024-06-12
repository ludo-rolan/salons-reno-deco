<?php

/**
 * PECL Memcached class
 */
if (!defined('W3TC')) {
    die();
}

w3_require_once(W3TC_LIB_W3_DIR . '/Cache/Base.php');

/**
 * Class W3_Cache_Memcached
 */
class W3_Cache_Redis extends W3_Cache_Base {
    /**
     * Memcache object
     *
     * @var Memcache
     */
    protected $_redis = null;

    /*
     * Used for faster flushing
     *
     * @var integer $_key_version
     */
    protected $_key_version = array();

    /**
     * constructor
     *
     * @param array $config
     */
    function __construct($config) {
        
        $options = array(
		    'namespace' => 'w3tc_',
		    'servers'   => array(
		       array('host' => REDIS_HOST , 'port' => REDIS_PORT),
		    )
		);

        if ( defined('REDIS_HOST2') && defined('REDIS_PORT2')) {
			$option['servers'][]  = array(
                'host'=>REDIS_HOST2 , 'port'=> REDIS_PORT2
            );
		}
		if(  !class_exists('Rediska')){
			require_once REDISKA_PATH.'/Rediska.php';
		}
		
		$this->_redis = new Rediska($options);

        return true;
    }

    /**
     * Adds data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @param string $group Used to differentiate between groups of cache values
     * @return boolean
     */
    function add($key, &$var, $expire = 0, $group = '0') {
        return $this->set($key, $var, $expire, $group);
    }

    /**
     * Sets data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @param string $group Used to differentiate between groups of cache values
     * @return boolean
     */
    function set($key, $var, $expire = 0, $group = '0') {
        $keyname = $this->get_item_key($key);
        $rkey = new Rediska_Key($keyname );
        $rkey->expire($expire );
        $rkey->setRediska($this->_redis);
	    return $rkey->setValue($var);	    
    }

    /**
     * Returns data
     *
     * @param string $key
     * @param string $group Used to differentiate between groups of cache values
     * @return mixed
     */
    function get_with_old($key, $group = '0') {
        $has_old_data = false;

        $keyname = $this->get_item_key($key);
        $rkey = new Rediska_Key($keyname);
        $rkey->setRediska($this->_redis);

	    return array ($rkey->getValue() ,$has_old_data) ;	    
    }

    /**
     * Replaces data
     *
     * @param string $key
     * @param mixed $var
     * @param integer $expire
     * @param string $group Used to differentiate between groups of cache values
     * @return boolean
     */
    function replace($key, &$var, $expire = 0, $group = '0') {
        return $this->set($key, $var, $expire, $group);
    }

    /**
     * Deletes data
     *
     * @param string $key
     * @param string $group
     * @return boolean
     */
    function delete($key, $group = '') {
        $this->hard_delete($key);
        /*
        if ($this->_use_expired_data) {
            $v = @$this->_memcache->get($key . '_' . $this->_blog_id);
            if (is_array($v)) {
                $v['key_version'] = 0;
                @$this->_memcache->set($key . '_' . $this->_blog_id, $v, false, 0);
                return true;
            }
        }
        return @$this->_memcache->delete($key . '_' . $this->_blog_id, 0); */
    }

    /**
     * Key to delete, deletes .old and primary if exists.
     * @param $key
     * @return bool
     */
    function hard_delete($key) {

        $keyname = $this->get_item_key($key);
        $rkey = new Rediska_Key($keyname);
        $rkey->setRediska($this->_redis);
        return $rkey->delete();	
    }

    /**
     * Flushes all data
     *
     * @param string $group Used to differentiate between groups of cache values
     * @return boolean
     */
    function flush($group = '0') {
        return true;
    }

    /**
     * Checks if engine can function properly in this environment
     * @return bool
     */
    public function available() {
        return defined('REDIS_HOST');
    }

    /**
     * Returns key version
     *
     * @param string $group Used to differentiate between groups of cache values
     * @return integer
     */
    private function _get_key_version($group = '0') {
       return false;
    }

    /**
     * Sets new key version
     *
     * @param $v
     * @param string $group Used to differentiate between groups of cache values
     * @return boolean
     */
    private function _set_key_version($v, $group = '0') {
    	return false;    
    }
}
