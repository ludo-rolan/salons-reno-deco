<?php
if ( ! class_exists( 'Autoload_WP' ) ) {
	/**
	 * Generic autoloader for classes named in WordPress coding style.
	 */
	class Autoload_WP {
		public $dir = __DIR__;
		function __construct( $dir = '' ) {
			if ( ! empty( $dir ) )
				$this->dir = $dir;
			spl_autoload_register( array( $this, 'spl_autoload_register' ) );
		}
		function spl_autoload_register( $class_name ) {
			if ( strpos(strtoupper($class_name), 'RW') === 0 ){
				$class_path = $this->dir . '/' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
				if ( file_exists( $class_path ) )
					include $class_path;
			}
		}
	}
}