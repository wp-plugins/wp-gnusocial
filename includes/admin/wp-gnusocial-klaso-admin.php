<?php
/**
 * Define the admin class
 * 
 * @since 0.2.6
 * 
 * @package Wp_Gnusocial\Admin
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The admin class.
 * 
 * @since 0.2.6
 */
final class Wp_Gnusocial_Admin {
	
	/**
	 * The only instance of this class.
	 * 
	 * @since 0.2.6
	 * @access protected
	 */
	protected static $instance = null;
	
	/**
	 * Get the only instance of this class.
	 * 
	 * @since 0.2.6
	 * 
	 * @return object $instance The only instance of this class.
	 */
	public static function get_instance() {
		
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	/**
	 * Prevent cloning of this class.
	 *
	 * @since 0.2.6
	 * 
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Ne permesite', Merlo::SLUG ), Merlo::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.2.6
	 * 
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Ne permesite', Merlo::SLUG ), Merlo::VERSION );
	}
	
	/**
	 * Construct the class!
	 *
	 * @since 0.2.6
	 * 
	 * @return void
	 */
	public function __construct() {
		
		/**
		 * The settings callbacks.
		 */
		require( plugin_dir_path( __FILE__ ) . 'settings.php' );
		
		// Add the settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		
		// Add and register the settings sections and fields.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		
		// Enqueue the script.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			
	}
	
	/**
	 * Add to the settings page.
	 *
	 * @since 0.2.6
	 * 
	 * @return void
	 */
	public function add_settings_page() {
	
		add_options_page (
			__( 'WP-GNU social', Wp_Gnusocial::SLUG ),
			__( 'WP-GNU social', Wp_Gnusocial::SLUG ),
			'manage_options',
			'wp-gnusocial',
			'av_settings_page'
		);
	}
	
	/**
	 * Enqueue the scripts.
	 *
	 * @since 0.0.1
	 * 
	 * @param string $page The current admin page.
	 * @return void
	 */
	public function enqueue_scripts( $page ) {


	}
	
		
	/**
	 * Add and register the settings sections and fields.
	 *
	 * @since 0.2.6
	 *
	 * @return void
	 */
	public function register_settings() {
		
		/* Ĝeneralaj agordoj */
		add_settings_section( 'wpgs_gheneralaj_agordoj', null, 'wpgs_agordoj_retrovoko_gheneralaj_agordoj', 'wp-gnusocial' );
	 	
	 	// API-url
	 	add_settings_field( '_wpgs_apiurl', '<label for="_wpgs_apiurl">' . __( 'API-url de via nodo', 'wp_gnusocial' ) . '</label>', 'wpgs_agordoj_retrovoko_apiurl', 'wp-gnusocial', 'wpgs_gheneralaj_agordoj' );
	 	register_setting  ( 'wp-gnusocial', '_wpgs_apiurl', 'esc_attr' );
	 	
	 	// Salutnomo
	 	add_settings_field( '_wpgs_salutnomo', '<label for="_wpgs_salutnomo">' . __( 'Salutnomo', 'wp_gnusocial' ) . '</label>', 'wpgs_agordoj_retrovoko_salutnomo', 'wp-gnusocial', 'wpgs_gheneralaj_agordoj' );
	 	register_setting  ( 'wp-gnusocial', '_wpgs_salutnomo', 'esc_attr' );
	 	
	 	// Parvorto
	 	add_settings_field( '_wpgs_pasvorto', '<label for="_wpgs_pasvorto">' . __( 'Pasvorto', 'wp_gnusocial' ) . '</label>', 'wpgs_agordoj_retrovoko_pasvorto', 'wp-gnusocial', 'wpgs_gheneralaj_agordoj' );
	 	register_setting  ( 'wp-gnusocial', '_wpgs_pasvorto', 'esc_attr' );	 	
		
		do_action( 'av_register_settings' );
	}
}
