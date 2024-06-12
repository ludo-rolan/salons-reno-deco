<?php


//$sapi_type = php_sapi_name();
error_reporting(E_ALL);
set_time_limit(0);


$host = @$argv[1];


$host_from_optins = getopt('', ['host::']);
if(isset($host_from_optins['host']))
	$host = $host_from_optins['host'];

// console mode

$use_console = (php_sapi_name() == 'cli');
$args_copy = $argv;
$script_name = basename( array_shift($args_copy));

if( $use_console ){
	defined('USE_CONSOLE') or define('USE_CONSOLE' , true );
	$rw_linebreak = "\n";
}else{
	$rw_linebreak = "<br/>\n";
}

if(defined('USE_CONSOLE')){
	if( defined('DO_MONITOR') && defined('DG_CHECK')) {
		$dg_tags = array( 'script' => $script_name  , 'args' => implode(',',$args_copy) ) ;
	}
}

if ($use_console && !$host) {
    echo ("Il faut mettre le 1er parametre qui concerne le host exemle content.viepratique.fr\n");
    exit();
}
if ($use_console) {
    define('HTTP_HOST', $host);

    $_SERVER['HTTP_HOST'] = $host;
    $_SERVER['SERVER_NAME'] = $host;
}



if ( defined('LOCK_FILE')) {
	
	if(defined('CHECK_SHELL')){
		$ps_ax = shell_exec('ps ax');
		// delete lock_file if exists and only this current script is executed 
		if((substr_count($ps_ax, $script_name) == 1 || substr_count($ps_ax, $script_name) == 2) && file_exists(LOCK_FILE )){ 
			unlink(LOCK_FILE);
		}
	}

	if(defined('CHECK_SHELL_PARAMS')){
		$ps_ax = shell_exec('ps ax');
		// delete lock_file if exists and only this current script is executed 
		$script_cmd = implode('( +)', $argv) ;
		$script_cmd = str_replace(array('.', '/'), array('\.', '\/'), $script_cmd) ;
		$script_cmd  = '/' . $script_cmd  .'/';

		if(file_exists(LOCK_FILE ) AND preg_match_all($script_cmd , $ps_ax, $matches) AND count($matches[0]) <= 2){ 
			unlink(LOCK_FILE);
		}
	}


	if( !file_exists(LOCK_FILE ))
		touch(LOCK_FILE );
	else {
		//trigger_error( 'Lock File Present '. LOCK_FILE  , E_USER_ERROR );
		echo( 'Lock File Present '. LOCK_FILE  );
		exit();
	}
}


// load env config
if (file_exists(dirname(__FILE__) . '/config.php')) {
    require_once (dirname(__FILE__) . '/config.php');
}

//set_time_limit (  5000 ) ;
define("WP_PATH", dirname(__FILE__) . '/../');
require_once (WP_PATH . 'wp-load.php');

if ( !$use_console && !is_user_logged_in() ) {
	echo 'Vous devez vous connecter pour exécuter cette tache' ;
	exit();
}

$_SERVER['SERVER_PROTOCOL'] = null;
wp();
set_time_limit(0);
if (!$use_console)
	header("HTTP/1.1 200 OK" , true, 200);

// Force implicite flush
ob_implicit_flush(true);

function lock_required_file( $file ) {
	$fp = fopen( $file, 'c' );

	if ( flock( $fp, LOCK_EX ) ) { // acquière un verrou exclusif
		$content = require( $file );

		fflush( $fp ); // libère le contenu avant d'enlever le verrou
		flock( $fp, LOCK_UN ); // libère le verrou
		fclose( $fp );

		return $content;
	}
	else {
		echo 'Unable to lock the file ! : ' . $file;
		usleep( 10000 );
		fclose( $fp );
		lock_required_file( $file );
	}
}

