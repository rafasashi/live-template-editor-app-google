<?php
/*
 * Plugin Name: Live Template Editor App Google
 * Version: 1.0
 * Plugin URI: https://github.com/rafasashi
 * Description: Google.com API integrator for Live Template Editor.
 * Author: Rafasashi
 * Author URI: https://github.com/rafasashi
 * Requires at least: 4.6
 * Tested up to: 4.7
 *
 * Text Domain: ltple
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Rafasashi
 * @since 1.0.0
 */
	
	/**
	* Add documentation link
	*
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	/**
	 * Returns the main instance of LTPLE_App_Google to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object LTPLE_App_Google
	 */
	function LTPLE_App_Google ( $version = '1.0.0' ) {
		
		$instance = LTPLE_Client::instance( __FILE__, $version );
		
		if ( empty( $instance->App_Google ) ) {
			
			$instance->App_Google = new stdClass();
			
			$instance->App_Google = LTPLE_App_Google::instance( __FILE__, $instance, $version );
		}

		return $instance;
	}	
	
	add_filter( 'plugins_loaded', function(){

		$dev_ips = array();
		
		if( defined('MASTER_ADMIN_IPS') ){
			
			$dev_ips = MASTER_ADMIN_IPS;
		}
		
		$mode = ( ( in_array( $_SERVER['REMOTE_ADDR'], $dev_ips ) || ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && in_array( $_SERVER['HTTP_X_FORWARDED_FOR'], $dev_ips ) )) ? '-dev' : '');
		
		if( $mode == '-dev' ){
			
			ini_set('display_errors', 1);
		}

		// Load plugin functions
		require_once( 'includes'.$mode.'/functions.php' );	
		
		// Load plugin class files

		require_once( 'includes'.$mode.'/class-ltple.php' );
		require_once( 'includes'.$mode.'/class-ltple-settings.php' );

		// Autoload plugin libraries
		
		$lib = glob( __DIR__ . '/includes'.$mode.'/lib/class-ltple-*.php');
		
		foreach($lib as $file){
			
			require_once( $file );
		}
	
		if( $mode == '-dev' ){
			
			LTPLE_App_Google('1.1.1');
		}
		else{
			
			LTPLE_App_Google('1.1.0');
		}		
	});