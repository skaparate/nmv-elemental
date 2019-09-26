<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://nicomv.com/
 * @since      1.0.0
 *
 * @package    Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */

namespace Nicomv\Elemental\Includes;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Nicomv\Elemental
 * @package Nicomv\Elemental/includes
 * @author     Nicolas Mancilla <info@nicomv.com>
 */
class Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		Logger::log( '### Starting NMV Elemental Deactivation ###' );
	}

}
