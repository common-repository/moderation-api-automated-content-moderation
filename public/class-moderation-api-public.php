<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://moderationapi.com
 * @since      1.0.0
 *
 * @package    Moderation_Api
 * @subpackage Moderation_Api/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Moderation_Api
 * @subpackage Moderation_Api/public
 * @author     Moderation API <support@moderationapi.com>
 */
class Moderation_Api_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add the pre-approval filter with priority 20 (after default filters)
		add_filter('pre_comment_approved', array($this, 'pre_comment_approved_filter'), 20, 2);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/moderation-api-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/moderation-api-public.js', array( 'jquery' ), $this->version, false );

	}


	public function analyze_comment($comment){
		$api_key = Moderation_Api::get_api_key();

		// Sanitize input data
		$text = sanitize_textarea_field($comment->comment_content);
		$user_id = absint($comment->user_id);
		$user_email = sanitize_email($comment->comment_author_email);
		$user_ip = sanitize_text_field($comment->comment_author_IP);
		// $comment_id = absint($comment->comment_ID);
		$post_id = absint($comment->comment_post_ID);
		$post_url = esc_url(get_permalink($post_id));

		$authorId = $user_id;
		if (!$authorId) {
			$authorId = $user_email;
		}
		if (!$authorId) {
			$authorId = $user_ip;
		}

		$url = Moderation_Api::API_URL . "/api/v1/moderate/text";
		$headers = array(
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type' => 'application/json',
		);
		$data = array(
			'value' => $text,
			'authorId' => (string) $authorId,
			'contextId' => (string) $post_id,
			'metadata' => array(
				// 'comment_id' => $comment_id,
				'url' => $post_url
			)
		);

		$response = wp_remote_post($url, array(
			'headers' => $headers,
			'body' => wp_json_encode($data),
		));

		if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
			$body = wp_remote_retrieve_body($response);
			// Decode the JSON response
			$responseData = json_decode($body, true);
			// Return the response data
			return $responseData;
		} else {
			// Log the error
			error_log("Error analyzing comment: " . print_r($response, true));
			// Handle error
			return false;
		}		
		
	}
	

	function my_plugin_register_webhook_endpoints() {
			add_rewrite_rule('^moderation-api-webhook$', 'index.php?moderation_api_webhook=1', 'top');
			flush_rewrite_rules();
	}

	function my_plugin_webhook_query_vars($query_vars) {
			$query_vars[] = 'moderation_api_webhook';
			return $query_vars;
	}

	public function my_plugin_process_webhook() {

    if (get_query_var('moderation_api_webhook')) {
     
        // get the JSON data from webhook payload
        $raw_data = file_get_contents('php://input');
        $json = json_decode($raw_data, true);
        $type = isset($json['type']) ? sanitize_text_field($json['type']) : '';
        $metadata = isset($json['metadata']) ? $json['metadata'] : array();
        $comment_id = isset($metadata['comment_id']) ? absint($metadata['comment_id']) : 0;

        if($type == 'QUEUE_ITEM_ACTION' && $comment_id ){
            // Sanitize and validate the action
            $action = isset($_GET['action']) ? sanitize_key($_GET['action']) : '';
            
            if (!in_array($action, array('remove', 'show'))) {
                wp_send_json_error('Invalid action', 400);
                exit();
            }

            $comment = get_comment($comment_id);

            if(!$comment){
                wp_send_json_error('Comment not found', 404);
                exit();
            }
                    
            switch ($action) {
                case 'remove':
                    wp_spam_comment($comment_id);
                    break;
                
                case 'show':
                    wp_set_comment_status($comment_id, 'approve');
                    break;

                default:
                    // This should never be reached due to the earlier validation
                    wp_send_json_error('Invalid action', 400);
                    exit();
            }

            wp_send_json_success($action);
            exit();
        }

        // Return a response if necessary
        wp_send_json_success('Webhook processed successfully');
        exit();
    }
	}


	/**
	 * Filter the comment approval status before it's set
	 *
	 * @param int|string|WP_Error $approved The approval status
	 * @param array $commentdata Comment data
	 * @return int|string|WP_Error Modified approval status
	 */
	public function pre_comment_approved_filter($approved, $commentdata) {
		try {
			// Convert commentdata array to object for consistency
			$comment = (object) $commentdata;
			
			// Analyze the comment
			$analysis = $this->analyze_comment($comment);

			if ($analysis === false) {
					// On API failure, let other filters handle it
					return $approved;
			}

			// Store analysis results in temporary location
			wp_cache_set('modapi_analysis_' . $comment->comment_author_IP, $analysis, '', 60);
			

			if ($analysis['flagged']) {
				$action = get_option('modapi_flagged_action', '0');
				
				switch ($action) {
					case '1':
							// Let comment through
							return '1';
					
					case '2':
							// Hold for moderation
							return '0';

					case '3':
							// Mark as spam
							return 'spam';

					case '4':
							// Send to trash
							return 'trash';
					
					default:
							// Default to moderation
							return $approved;
				}
			}

			
			// Return the original approval status if not flagged
			return $approved;

		} catch (\Throwable $th) {
			// On error, let other filters handle it
			return $approved;
		}
	}



	public function moderation_api_comment_post( $comment_ID, $comment_approved ) {
		// Get the cached analysis results
		$analysis = wp_cache_get('modapi_analysis_' . get_comment($comment_ID)->comment_author_IP);
		
		if ($analysis) {
			// Store the analysis results as comment meta
			add_comment_meta($comment_ID, 'modapi_flagged', $analysis['flagged']);

			// Clear the cache
			wp_cache_delete('modapi_analysis_' . get_comment($comment_ID)->comment_author_IP);
		}
	}

}
