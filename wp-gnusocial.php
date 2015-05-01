<?php
/**
 * The main plugin file.
 * 
 * This file loads the main plugin class and gets things running.
 *
 * @since 0.0.1
 * 
 * @package WP_Gnusocial
 */

/**
 * Plugin Name: WP-GNU social
 * Description: GNU social based comment system for WordPress.
 * Author:      Las Indias
 * Author URI:  http://lasindias.com
 * Version:     0.2
 * Text Domain: wp_gnusocial
 * Domain Path: /languages/
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}


/*
 * Enable Debug Mode
 * Uncomment the following lines to see PHP errors
 */
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_errors', 1);
//error_reporting(E_ALL);


/**
 * The main class definition.
 */
require( plugin_dir_path( __FILE__ ) . 'includes/wp-gnusocial-klaso.php' );

// Get the plugin running.
add_action( 'plugins_loaded', array( 'Wp_Gnusocial', 'get_instance' ) );

// Check that the admin is loaded.
if ( is_admin() ) {
	
	/**
	 * The admin class definition.
	 */
	require( plugin_dir_path( __FILE__ ) . 'includes/admin/wp-gnusocial-klaso-admin.php' );
	
	// Get the plugin's admin running.
	add_action( 'plugins_loaded', array( 'Wp_Gnusocial_Admin', 'get_instance' ) );
}
