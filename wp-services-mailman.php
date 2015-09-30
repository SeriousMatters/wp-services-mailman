<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/SeriousMatters
 * @since             1.0.0
 * @package           Wp_Services_Mailman
 *
 * @wordpress-plugin
 * Plugin Name:       Services Mailman
 * Plugin URI:        https://github.com/SeriousMatters/wp-services-mailman
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Howard Li
 * Author URI:        https://github.com/SeriousMatters
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-services-mailman
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-services-mailman-activator.php
 */
function activate_wp_services_mailman() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-services-mailman-activator.php';
	Wp_Services_Mailman_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-services-mailman-deactivator.php
 */
function deactivate_wp_services_mailman() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-services-mailman-deactivator.php';
	Wp_Services_Mailman_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_services_mailman' );
register_deactivation_hook( __FILE__, 'deactivate_wp_services_mailman' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-services-mailman.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_services_mailman() {

	$plugin = new Wp_Services_Mailman();
	$plugin->run();

}
run_wp_services_mailman();
