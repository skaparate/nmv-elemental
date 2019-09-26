<?php
/**
 * Utility class.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */

namespace Nicomv\Elemental\Includes;

use Nicomv\Elemental\Includes\Logger;

/**
 * Class with utility methods used within the plugin.
 *
 * @since 1.0.0
 * @package Nicomv/Elemental
 * @subpackage Nicomv/Elemental/Includes
 */
class Utils {
	/**
	 * Parses a list of comma separated css classes and normalizes them.
	 *
	 * @param string $classes A comma separated list of classes.
	 * @param array  $defaults An array of default classes to apply.
	 * @return string The normalized list of classes. If the $classes is empty, the
	 * defaults is returned instead.
	 */
	public static function parse_classes( string $classes, array $defaults = [] ) {
		Logger::log( 'Entering Nicomv/Elemental/Includes/Utils->parse_classes' );
		$classes = trim( $classes );
		if ( empty( $classes ) ) {
			return trim( implode( ' ', $defaults ) );
		}
		if ( empty( $defaults ) ) {
			return $classes;
		}
		$result = explode( ',', $classes );

		foreach ( $defaults as $class ) {
			if ( in_array( $class, $result ) ) {
				continue;
			}
			$result[] = trim( $class );
		}
		$class_str = implode( ' ', $result );
		return $class_str;
	}
}
