<?php
/**
 * Helper functions.
 *
 * @package   Cedaro\WP\Tests
 * @copyright Copyright (c) 2019 Cedaro, LLC
 * @license   MIT
 */

declare ( strict_types = 1 );

namespace Cedaro\WP\Tests;

use PHPUnit\TextUI\Command;
use PHPUnit\Util\Getopt;
use ReflectionClass;

/**
 * Retrieve the current test suite.
 *
 * @return string
 */
function get_current_suite() {
	$suite = '';

	$options = get_phpunit_options();

	if ( ! empty( $options[1] ) ) {
		// File or directory.
		$source = $options[1][1] ?? $options[1][0];
		$source = str_replace( 'tests/phpunit', '', $source );

		if ( 0 === strpos( $source, '/Unit' ) ) {
			$suite = 'Unit';
		}
	}

	foreach ( $options[0] as $arg ) {
		if ( '--testsuite' === $arg[0] ) {
			$suite = $arg[1];
			break;
		}
	}

	return $suite;
}

/**
 * Retrieve PHPUnit CLI arguments.
 *
 * @return array
 */
function get_phpunit_options() {
	$class    = new ReflectionClass( Command::class );
	$property = $class->getProperty( 'longOptions' );
	$property->setAccessible( true );

	$value        = $property->getValue( new Command() );
	$long_options = array_keys( $value );

	return Getopt::getopt(
		$GLOBALS['argv'],
		'd:c:hv',
		$long_options
	);
}
