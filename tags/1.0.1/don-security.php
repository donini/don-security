<?php
/**
 * @link              https://wordpress.org/plugins/don-security
 * @since             1.0.0
 * @package           Don_Security
 *
 * @wordpress-plugin
 * Plugin Name:       Don Security
 * Plugin URI:        https://wordpress.org/plugins/don-security
 * Description:       This plugin allows to set some security improvements to your WordPress site.
 * Version:           1.0.1
 * Author:            Rodrigo Donini
 * Author URI:        http://profiles.wordpress.org/rodrigodonini
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       don-security
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-don-security-activator.php
 */
function activate_don_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-don-security-activator.php';
	Don_Security_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-don-security-deactivator.php
 */
function deactivate_don_security() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-don-security-deactivator.php';
	Don_Security_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_don_security' );
register_deactivation_hook( __FILE__, 'deactivate_don_security' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-don-security.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_don_security() {

	$plugin = new Don_Security();
	$plugin->run();

}
run_don_security();
