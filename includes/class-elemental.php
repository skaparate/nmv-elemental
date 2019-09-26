<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://nicomv.com/
 * @since      1.0.0
 *
 * @package    Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */

namespace Nicomv\Elemental\Includes;

use Nicomv\Elemental\Admin\Admin;

use Nicomv\Elemental\Frontend\Elemental_Public;

use Nicomv\Elemental\Components\Elementor_Manager;

 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
	die;
}

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
 * @package    Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 * @author     Nicolas Mancilla <info@nicomv.com>
 */
class Elemental {
	/**
	 * The plugin slug.
	 */
	const PLUGIN_SLUG = 'nmv-elemental';

	/**
	 * The plugin name.
	 */
	const PLUGIN_NAME = 'NMV Elemental';

	/**
	   * Currently plugin version.
	   * Start at version 1.0.0 and use SemVer - https://semver.org
	   * Rename this for your plugin and update it as you release new versions.
	   */
	const PLUGIN_VERSION = '1.0.0';

	/**
	 * The minimum elementor version for this plugin to work.
	 *
	 * @since 1.0.0
	 */
	const MIN_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Because of the features used, we require a version of
	 * PHP 7 or more to work properly.
	 *
	 * @since 1.0.0
	 */
	const MIN_PHP_VERSION = '7.0.0';

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Nicomv\Elemental\Elemental
	 */
	private static $instance = null;

	/**
	 * Class used to register and auto load other classes.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Nicomv\Elemental\Includes\Auto_Loader
	 */
	private $auto_loader;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nicomv\Elemental\Includes\Action_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {

	}

	/**
	 * Retrieves the singleton instance of this class.
	 *
	 * @return Nicomv\Elemental\Elemental.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Begins the plugin's execution.
	 */
	public function run() {
		if ( false === $this->is_php_version_requirement_met() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}
		if ( false === $this->is_elementor_installed() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
			return;
		}
		if ( false === $this->is_elementor_version_requirement_met() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		$this->auto_load();
		$this->loader = new Action_Loader();
		$this->register_plugin_hooks();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		require_once NMV_ELEMENTAL_PATH . '/components/class-elementor-manager.php';
		$elementor_mgr = Elementor_Manager::get_instance();
		// $this->loader->add_action( 'elementor/controls/controls_registered', $elementor_mgr, 'register_controls' );
		$elementor_mgr->init();
	}

	/**
	 * Register namespaces and classes to auto load.
	 */
	private function auto_load() {
		require_once NMV_ELEMENTAL_PATH . 'includes/class-auto-loader.php';

		$loader = new Auto_Loader( '\Nicomv\Elemental', NMV_ELEMENTAL_PATH );
		$loader->register();
		$loader->add_namespace( '', '' );
		$loader->add_namespace( '\Includes', 'includes' );
		$loader->add_namespace( '\Frontend', 'public' );
		$loader->add_namespace( '\Admin', 'admin' );

		$this->auto_loader = $loader;
	}

	/**
	 * Registers the plugin's lifecycle hooks.
	 */
	private function register_plugin_hooks() {
		$activator = new Activator();
		register_activation_hook( NMV_ELEMENTAL_PATH . self::PLUGIN_SLUG . '.php', [ $activator, 'activate' ] );

		$deactivator = new Deactivator();
		register_deactivation_hook( NMV_ELEMENTAL_PATH . self::PLUGIN_SLUG . '.php', [ $deactivator, 'deactivate' ] );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nicomv\Elemental\Includes\I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n();
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
		$plugin_admin = new Admin( self::PLUGIN_SLUG, self::PLUGIN_VERSION );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Elemental_Public( self::PLUGIN_SLUG, self::PLUGIN_VERSION );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nicomv\Elemental\Includes\Action_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Checks if elementor is installed and active.
	 */
	private function is_elementor_installed() {
		return did_action( 'elementor/loaded' );
	}

	/**
	 * Checks if the minimum elementor version is installed.
	 */
	private function is_elementor_version_requirement_met() {
		return version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '>=' );
	}

	/**
	 * Check if the PHP version is equal or higher than the required by the plugin.
	 */
	private function is_php_version_requirement_met() {
		return version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '>=' );
	}

	/**
	 * Checks if the plugin nmv-posts-grid is installed.
	 */
	private function is_nmv_posts_grid_installed() {
		return is_defined( 'NMV_POSTSGRID' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_elementor() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			__( '"%1$s" requires "%2$s" to be installed and activated.', 'nmv-elemental' ),
			'<strong>NMV Elemental</strong>',
			'<strong>Elementor</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nmv-elemental' ),
			'<strong>NMV Elemental</strong>',
			'<strong>Elementor</strong>',
			 NMV_ELEMENTAL_MIN_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nmv-elemental' ),
			'<strong>' . self::PLUGIN_NAME . '</strong>',
			'<strong>PHP</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );

	}
}
