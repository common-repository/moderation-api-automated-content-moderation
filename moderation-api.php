<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://moderationapi.com
 * @since             1.0.0
 * @package           Moderation_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Moderation API: Automated Content Moderation
 * Plugin URI:        https://moderationapi.com/integrations/wordpress-content-moderation
 * Description:       Use Moderation API to automatically moderate comments on your WordPress site. Detects a large range of contet such as bullying, discrimination, sentiment, NSFW, PII, and much more.
 * Version:           1.0.2
 * Author:            Moderation API
 * Author URI:        https://moderationapi.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       moderation-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MODERATION_API_VERSION', '1.0.2' );

define( 'MODERATION_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-moderation-api-activator.php
 */
function moderation_api_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-moderation-api-activator.php';
	Moderation_API_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-moderation-api-deactivator.php
 */
function moderation_api_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-moderation-api-deactivator.php';
	Moderation_API_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'moderation_api_activate' );
register_deactivation_hook( __FILE__, 'moderation_api_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-moderation-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function moderation_api_run() {
	$plugin = new Moderation_API();
	$plugin->run();
}
moderation_api_run();