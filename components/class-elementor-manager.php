<?php
/**
 * Elementor management file.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Components
 */

namespace Nicomv\Elemental\Components;

use Nicomv\Elemental\Includes\Action_Loader;
use Nicomv\Elemental\Includes\Logger;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Manages the registration of the Elementor controls.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Components
 */
class Elementor_Manager {
	/**
	 * Singleton class instance.
	 *
	 * @var Elementor_Manager
	 */
	private static $instance = null;

	/**
	 * Default Constructor.
	 */
	private function __construct() {
		Logger::log( 'New Elementor_Manager instance' );
	}

	private function require_widget_files() {
		require_once __DIR__ . '/class-badge-widget.php';
	}

	/**
	 * Adds the required actions to register controls and widgets.
	 */
	public function init() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_elementor_categories' ] );
	}

	/**
	 * Retrieves an instance of this class.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Elementor_Manager();
		}
		return self::$instance;
	}

	/**
	 * Registers the Elementor controls.
	 */
	public function register_controls() {
		Logger::log( 'Registering elementor controls' );
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
	}

	/**
	 * Register the Elemento widgets.
	 */
	public function register_widgets() {
		$this->require_widget_files();
		Logger::log( 'Registering NMV Elemental Widgets' );
		$widget_mgr = \Elementor\Plugin::instance()->widgets_manager;

		$widget_mgr->register_widget_type( new Badge_Widget() );
	}



	/**
	 * Registers the elementor categories.
	 *
	 * @param Object $elements_mgr The elementor manager.
	 */
	public function register_elementor_categories( $elements_mgr ) {
		Logger::log( 'Registering elementor categories' );
		$elements_mgr->add_category(
			'nmv-elemental-general',
			[
				'title' => __( 'NMV Elemental', 'nmv-elemental' ),
				'icon'  => 'fa fa-plug',
			]
			);

		return $elements_mgr;
	}
}
