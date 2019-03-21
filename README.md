# WordPress Test Suite

A loader for the WordPress test suite that:

* Makes it easier to conditionally load the WordPress test suite, so unit tests can be run without loading integration tests.
* Minimizes configuration to make it easier to create tests for new plugins.
* Allows for managing test dependencies with Composer.

_**Note:** This is currently an experiment. Any feedback or suggested alternatives are appreciated._

Requires PHP 7.1+.

## Installation

To use this library in your project, add it to `composer.json`:

```sh
composer require --dev cedaro/wp-test-suite
```

WordPress and its test suite should also be added as a development dependency:

```sh
composer config repositories.wordpress vcs https://github.com/WordPress/wordpress-develop
composer require --dev wordpress/wordpress
```

## Bootstrap

In your PHPUnit bootstrap file, the test suite can be loaded like this:

```php
<?php
use Cedaro\WP\Tests\TestSuite;

require dirname( __DIR__, 2 ) . '/vendor/autoload.php';

$suite = new TestSuite();

$suite->addFilter( 'muplugins_loaded', function() {
	require dirname( __DIR__, 2 ) . '/satispress.php';
} );

$suite->bootstrap();
```

The WordPress test suite requires a database to be created before running tests, which is currently beyond the scope of this package. Beyond that, no additional configuration or set up is needed to use the WordPress test suite.

Continue reading to learn how to configure the WordPress test suite or only load it when it's needed.

## Conditionally Load the WordPress Test Suite

If you have unit tests that don't depend on WordPress and want to ensure they load and run quickly, split your tests into different suites in `phpunit.xml`:

```xml
<testsuites>
	<testsuite name="Unit">
		<directory>./tests/phpunit/Unit</directory>
	</testsuite>
	<testsuite name="Integration">
		<directory>./tests/phpunit/Integration</directory>
	</testsuite>
</testsuites>
```

Then in the bootstrap file, check to see which suite is being run before loading the WordPress test suite:

```php
<?php
use Cedaro\WP\Tests\TestSuite;

use function Cedaro\WP\Tests\get_current_suite;

require dirname( __DIR__, 2 ) . '/vendor/autoload.php';

if ( 'Unit' === get_current_suite() ) {
	return;
}

$suite = new TestSuite();
// Test suite configuration...
$suite->bootstrap();
```

## Configuring the WordPress Test Suite

The WordPress test suite typically requires a `wp-tests-config.php` file to be created before running the tests. [A basic default](src/wp-tests-config.php) is included in this package to keep configuration to a minimum.

If you need to use a custom configuration file, define the `WP_TESTS_CONFIG_FILE_PATH` in `phpunit.xml`:

```xml
<php>
	<const name="WP_TESTS_CONFIG_FILE_PATH" value="tests/phpunit/wp-tests-config.php" />
</php>
```

Or it can be defined in your bootstrap file:

```php
<?php
define( 'WP_TESTS_CONFIG_FILE_PATH', __DIR__ . '/wp-tests-config.php' );
```

## License

Copyright (c) 2019 Cedaro, LLC

This library is licensed under MIT.

Attribution is appreciated, but not required.
