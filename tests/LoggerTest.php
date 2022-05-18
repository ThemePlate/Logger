<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use Monolog\Logger as BaseLogger;
use ThemePlate\Logger;
use WP_UnitTestCase;

class LoggerTest extends WP_UnitTestCase {
	public function test_default_path_is_at_wp_content_dir_named_logs(): void {
		$logger = new Logger();

		$this->assertSame( WP_CONTENT_DIR . '/logs', $logger->get_path() );
	}

	public function for_every_channel_instances_are_cached(): array {
		return array(
			array( 'api' ),
			array( 'app' ),
		);
	}

	/**
	 * @dataProvider for_every_channel_instances_are_cached
	 */
	public function test_every_channel_instances_are_cached( string $name ): void {
		$logger = new Logger();

		$channel = $logger->channel( $name );

		$this->assertInstanceOf( BaseLogger::class, $channel );
		$this->assertSame( $channel, $logger->channel( $name ) );
		$this->assertSame( $channel, $logger->channel( $name ) );
	}
}
