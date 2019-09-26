<?php
/**
 * A logger class.
 *
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */

namespace Nicomv\Elemental\Includes;

 /**
  * A simple logger class.
  */
class Logger {
	/**
	 * Logs the message to the WordPress debug file when debugging is enabled.
	 *
	 * @param string  $msg The message to log.
	 * @param boolean $is_error If set to true, the message is logged even if
	 * debugging is disabled.
	 */
	public static function log( $msg, $is_error = false ) {
		if ( $is_error ) {
			error_log( $msg );
			return;
		}

		if ( true === WP_DEBUG ) {
			error_log( $msg );
		}
	}
}
