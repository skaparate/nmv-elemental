<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://nicomv.com/
 * @since             1.0.0
 * @package           Nicomv\Elemental
 *
 * @wordpress-plugin
 * Plugin Name:       NMV Elemental
 * Plugin URI:        https://github.com/skaparate/nmv-elemental
 * Description:
 * Version:           1.0.0
 * Author:            Nicolas Mancilla
 * Author URI:        https://nicomv.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nmv-elemental
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Defines the plugin base path.
 */
define( 'NMV_ELEMENTAL_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Defines the plugin base URL.
 */
define( 'NMV_ELEMENTAL_URL', plugins_url( '/', __FILE__ ) );

/**
 * Runs the plugin functions.
 */
function nmv_run_plugin() {
	require_once NMV_ELEMENTAL_PATH . 'includes/class-elemental.php';
	$plugin = \Nicomv\Elemental\Includes\Elemental::get_instance();
	$plugin->run();
}

nmv_run_plugin();
