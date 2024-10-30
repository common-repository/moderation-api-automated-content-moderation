<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://moderationapi.com
 * @since      1.0.0
 *
 * @package    Moderation_Api
 * @subpackage Moderation_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Moderation_Api
 * @subpackage Moderation_Api/admin
 * @author     Moderation API <support@moderationapi.com>
 */
class Moderation_Api_Admin {

	const NONCE = 'modapi-update-key';


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Moderation_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Moderation_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/moderation-api-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Moderation_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Moderation_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/moderation-api-admin.js', array( 'jquery' ), $this->version, false );
	}

	function check_nonce(){
		// Verify nonce for security
		if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) , Moderation_Api_Admin::NONCE)) {
				wp_nonce_ays("error");
		}
	}

	public function init() {
		// check page is moderation-api
		if (!isset($_GET['page']) || $_GET['page'] !== 'moderation-api') {
			return;
		}

		// current_user_can manage_options
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}

		// Handle form submissions
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->handle_post_request();
		}

		// Handle get requests
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->handle_get_request();
		}
	}

	private function handle_post_request(){
		$this->check_nonce();
		$action = $this->get_action();
		$this->handle_action($action);
	}

	private function handle_get_request(){
		$action = $this->get_action();
		$this->handle_action($action);
	}

	private function get_action(){
		// get action from post or get
		$action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : null;
		if (!$action) {
			$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : null;
		}
		if(!$action && isset($_GET['token'])){
			$action = 'enter-key';
		}
		return $action;
	}


	private function handle_action($action){
		if ($action === 'enter-key') {
			$api_key = sanitize_text_field($_POST['key'] ?? $_GET['token']);
			$this->save_key($api_key);
		}
		if ($action === 'delete-key') {
			$this->disconnect_key();
		}
	}


  private function save_key($api_key){
		if (!$api_key) {
			return;
		}

		if (!$this->validate_api_key($api_key)) {

			// invalid key show notice
			add_action('admin_notices', function() {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e('Invalid API key, please try again.', 'modapi'); ?></p>
				</div>
				<?php
			});
			return;
		}

    foreach (array('modapi_flagged_action') as $option) {
      // Sanitize the input
      $input_value = isset($_POST[$option]) ? sanitize_text_field($_POST[$option]) : '3';
    
      // Escape the output before saving it to the database
      $escaped_value = esc_attr($input_value);
    
      // Update the option in the database
      update_option($option, $escaped_value);
    }

		$existingKey = Moderation_Api::get_api_key();
  
    update_option('moderation_api_key', $api_key);

    if(!$existingKey){
      $this->sync_actions();
    }

  }


	private function disconnect_key() {
		// remove the api key
		update_option('moderation_api_key', '');
	}

	function get_account($key) {
		// include key as header bearer
		$headers = array(
			'Authorization' => 'Bearer ' . sanitize_text_field($key),
			'Content-Type' => 'application/json'
		);
		$url = Moderation_Api::API_URL . "/api/v1/account";
		

		$response = wp_remote_get($url, array(
			'headers' => $headers
		));

		// check status code is 200
		if ($response['response']['code'] != 200) {
			return false;
		}

		// check we have id
		$data = json_decode($response['body']);
		return $data;
	}


	function validate_api_key($key) {

		if (!$key) {
			return false;
		}
		try {
		
			$account = $this->get_account($key);
			if ($account && $account->id) {
				return true;
			}
			return false;
			//code...
		} catch (\Throwable $th) {
			// remove the api key
			return false;
		}

	}


	public function add_admin_menu() {
		add_options_page('Moderation API', 'Moderation API', 'manage_options', 'moderation-api', array($this, 'display_page'));
	}

	public function display_page() {
		$api_key = Moderation_Api::get_api_key();

		if ($api_key) {
				$account = $this->get_account($api_key);
				if (!$account) {
					$this->disconnect_key();
					return;
				}

				Moderation_Api::view('config', array('modapi_user' => $account));
				return;
		}


		Moderation_Api::view('start');


	}



	public static function get_page_url($page = 'config') {

		$args = array('page' => 'moderation-api');

		if ($page == 'stats') {
			$args = array('page' => 'moderation-api', 'view' => 'stats');
		} elseif ($page == 'delete_key') {
			$args = array('page' => 'moderation-api', 'view' => 'start', 'action' => 'delete-key', '_wpnonce' => wp_create_nonce(self::NONCE));
		} elseif ($page === 'init') {
			$args = array('page' => 'moderation-api', 'view' => 'start');
		}

		return add_query_arg($args, menu_page_url('moderation-api', false));
	}
	

	// Add a custom column to the comments list table
	function custom_comments_column($columns) {
		// Add a new column
		$columns['custom_flagged'] = 'Flagged';
		return $columns;
	}

	// Display the flagged value in the custom column
	function show_flagged_custom_field($column_name, $comment_id) {
		if ($column_name === 'custom_flagged') {
				$flagged = get_comment_meta($comment_id, 'modapi_flagged', true);
				if($flagged){
					echo '<span style="color: red;">' . __('Flagged', 'moderation-api') . '</span>';
				}else{
					echo '<span style="color: green;">' . __('Not Flagged', 'moderation-api') . '</span>';
				}
		}
	}


	function add_modapi_error_column($columns) {
			$columns['modapi_error'] = __('Moderation API error', 'moderation-api');
			return $columns;
	}


	function populate_modapi_error_column($column, $comment_ID) {
			if ('modapi_error' === $column) {
					$modapi_error = get_comment_meta($comment_ID, 'modapi_error', true);
					if ($modapi_error) {
							echo esc_html($modapi_error);
					} else {
							echo __('', 'moderation-api');
					}
			}
	}


	private function sync_actions() {

		$apiKey = Moderation_Api::get_api_key();
		if (!$apiKey) {
			return;
		}
		$url = Moderation_Api::API_URL . '/api/account/integration-actions-setup';
		$data = array(
			'actions' => array(
				array(
					'name' => 'Remove on WP',
					'description' => 'Move the comment to spam on WordPress and remove from queue.',
					'webhooks' => array(
						array(
							'url' => Moderation_Api::get_webhook_url() . '?action=remove',
						)
					)
						),
				array(
					'name' => 'Show on WP',
					'description' => 'Move the comment to approved on WordPress and remove from queue.',
					'webhooks' => array(
						array(
							'url' => Moderation_Api::get_webhook_url() . '?action=show',
						)
					)
				)
			)
		);

		// call the api
		wp_remote_post($url, array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $apiKey,
				'Content-Type' => 'application/json'
			),
			'body' => wp_json_encode($data)
		));



	}



}