<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Tests_Phalcon
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '../../../../wordpress-tests-lib/';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the child theme template
 */
function _manually_load_child_theme() {
	//echo "LOADING Theme";

	return  dirname( dirname( __FILE__ ) ) ; // . '/functions.php';
}
tests_add_filter( 'stylesheet_directory', '_manually_load_child_theme' );

/**
 * Manually load the parent being tested.
 */
function _manually_load_parent_theme() {
	//echo "LOADING Theme";

	return  str_replace( 'be', 'reworldmedia', dirname( dirname( __FILE__ ) )) ; // . '/functions.php';
}
tests_add_filter( 'template_directory', '_manually_load_parent_theme' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
