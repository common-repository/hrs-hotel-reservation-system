<?php
/*
  Plugin Name: MHotel Reservation System
  Plugin URI: http://hotelreservationwp.com/
  Description: An Online Hotel Room Search and Reservation System
  Version: 1.2.0
  Author: Plugin Creations
  Author URI: http://hotelreservationwp.com/
  Text Domain: mhrs
 */

if(!defined('ABSPATH')) {exit;}

require_once 'define.php';

if( !class_exists( 'MHRS_Init' ) ){
	class MHRS_Init{
		private static $_instance = null;
		
		public static function get_instance(){
			if( is_null( self::$_instance ) ){
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		public function __construct(){
			if(!session_id()) {
				session_start();
			}
			register_activation_hook( __FILE__, array( get_class($this), 'on_plugin_activate' ) );
			register_deactivation_hook( __FILE__, array( get_class($this), 'on_plugin_deactivate' ) );
			
			add_action('admin_menu', array( $this, 'main_menu_reg' ) );
			require_once 'inc/autoloader.php';
		}
		
		function on_plugin_activate() {
			do_action( 'mhrs_plugin_activate' );
		}
		
		function on_plugin_deactivate() {
			//update_option( 'mhrs_plugin_activated', 'no' );
		}
		
		function main_menu_reg(){
			$page_title		=	'MHRS Admin Area';
			$menu_title		=	'MHRS';
			$capability		=	'manage_options';
			$plugin_slug	=	'mhrs';
			$function		=	array( $this, 'main_menu' );
			$icon_url		=	'dashicons-store';
			//$position		=	'';
			 
			add_menu_page(
				$page_title,
				$menu_title,
				$capability,
				$plugin_slug,
				$function,
				$icon_url
			);
		}
		
		function main_menu(){}
		
		
	}/* end of class */
}

if( !function_exists( 'mhrs_init_start') ){
	function mhrs_init_start(){
		return MHRS_Init::get_instance();
	}
}
mhrs_init_start();