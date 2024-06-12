<?php
/**
* 
*/
class RW_Widget {
	

	private static $_instance;

	function __construct(){
		# code...
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Widget();
		}
		return self::$_instance;
	}
	
	static function deregister_styles_ninja_form() {
		wp_deregister_style( 'ninja-forms-display' );
		wp_deregister_style( 'quizz_styling' );
	}

}