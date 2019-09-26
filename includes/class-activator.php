<?php
/**
 * Fired during plugin activation
 *
 * @link       https://nicomv.com/
 * @since      1.0.0
 *
 * @package    Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */

namespace Nicomv\Elemental\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 * @author     Nicolas Mancilla <info@nicomv.com>
 */
class Activator {

	/**
	 * Executed when the plugin is activated (for setup).
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		Logger::log( '### Starting NMV Elemental Activation ###' );
	}

}
