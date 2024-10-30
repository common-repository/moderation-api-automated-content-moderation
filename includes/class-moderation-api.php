<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://moderationapi.com
 * @since      1.0.0
 *
 * @package    Moderation_Api
 * @subpackage Moderation_Api/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Moderation_Api
 * @subpackage Moderation_Api/includes
 * @author     Moderation API <support@moderationapi.com>
 */
class Moderation_Api {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Moderation_Api_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	// the base api url
	const API_URL = 'https://moderationapi.com';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MODERATION_API_VERSION' ) ) {
			$this->version = MODERATION_API_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'moderation-api';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Moderation_Api_Loader. Orchestrates the hooks of the plugin.
	 * - Moderation_Api_i18n. Defines internationalization functionality.
	 * - Moderation_Api_Admin. Defines all hooks for the admin area.
	 * - Moderation_Api_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moderation-api-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moderation-api-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-moderation-api-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-moderation-api-public.php';

		$this->loader = new Moderation_Api_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Moderation_Api_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Moderation_Api_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Moderation_Api_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// add admin sidepanel
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );

		// hook for admin settings page
		$this->loader->add_action( 'admin_init', $plugin_admin, 'init' );


		$this->loader->add_filter( 'manage_edit-comments_columns', $plugin_admin, 'custom_comments_column' );
		$this->loader->add_action( 'manage_comments_custom_column', $plugin_admin, 'show_flagged_custom_field', 10, 2 );


		// Populate the custom column with the modapi_error meta value
		$this->loader->add_action('manage_comments_custom_column', $plugin_admin, 'populate_modapi_error_column', 10, 2);

		// Add a custom column to the comments list table
		$this->loader->add_filter('manage_edit-comments_columns', $plugin_admin, 'add_modapi_error_column');
	
		$this->loader->add_action( 'admin_notices', $this, 'add_admin_notices' );

	}



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Moderation_Api_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('comment_post', $plugin_public, 'moderation_api_comment_post', 10, 2);


		// add webhooks
		$this->loader->add_action('init', $plugin_public, 'my_plugin_register_webhook_endpoints');
		$this->loader->add_filter('query_vars', $plugin_public, 'my_plugin_webhook_query_vars');
		$this->loader->add_action('template_redirect', $plugin_public, 'my_plugin_process_webhook');
	}



	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Moderation_Api_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'moderation_api_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		// load_plugin_textdomain( 'modapi' );

		$file = MODERATION_API_PLUGIN_DIR . 'admin/views/'. $name . '.php';

		include( $file );
	}

	public static function get_api_key(){
		return get_option( 'moderation_api_key' );
	}


	public static function display_status() {
		
	}


	public static function get_webhook_url() {
		return home_url( '/moderation-api-webhook' );
	}

	/**
	 * Display admin notices on the Comments page.
	 */
	public function add_admin_notices() {
		// Get the current screen
		$screen = get_current_screen();

		// Check if we are on the Comments admin page
		if ( 'edit-comments' === $screen->id ) {
			$api_key = Moderation_Api::get_api_key();

			if (!$api_key) {
			Moderation_Api::view( 'notice', array(
				'message' => __( 'Activate your Moderation API account to protect your site.', 'moderation-api' ),
				'type'    => 'info', // Types: 'success', 'error', 'warning', 'info'
				) );
			}
		}
	}



}









