<?php

/**
 * @package ThemePlate
 */

declare(strict_types=1);

namespace Tests;

use Monolog\Logger as BaseLogger;
use PHPUnit\Framework\Attributes\DataProvider;
use ThemePlate\Logger;
use PHPUnit\Framework\TestCase;

final class LoggerTest extends TestCase {
	public function test_default_path_is_at_wp_content_dir_named_logs(): void {
		$logger = new Logger();

		$this->assertSame( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'logs', $logger->path );
	}

	/** @return array<string, array<int, int|bool>> */
	public static function for_path_is_correctly_slashed_even_if_wrongly_supplied(): array {
		return array(
			'with 1 slash prefixed on folder name'     => array( -1 ),
			'with 1 slash suffixed on folder name'     => array( 1 ),
			'with 1 slash on both ends of folder name' => array( 0 ),
			'with 1 slash prefixed on folder name and trailing slash in base_path' => array( -1, true ),
			'with 1 slash suffixed on folder name and trailing slash in base_path' => array( 1, true ),
			'with 1 slash on both ends of folder name and trailing slash in base_path' => array( 0, true ),
		);
	}

	#[DataProvider( 'for_path_is_correctly_slashed_even_if_wrongly_supplied' )]
	public function test_path_is_correctly_slashed_even_if_wrongly_supplied( int $slashed_folder_name, bool $trailing_slashed_base_path = false ): void {
		$folder_name = 'mylogs';
		$base_path   = 'path/to/save';
		$expect      = $base_path . '/' . $folder_name;

		if ( 0 <= $slashed_folder_name ) {
			$folder_name .= '/';
		}

		if ( 0 >= $slashed_folder_name ) {
			$folder_name = '/' . $folder_name;
		}

		if ( $trailing_slashed_base_path ) {
			$base_path .= '/';
		}

		$logger = new Logger( $folder_name, $base_path );

		$this->assertSame( $expect, $logger->path );
	}

	/** @return array<string, array<int, ?string>> */
	public static function for_path_empty_strings(): array {
		return array(
			'with empty folder name' => array( '', null, WP_CONTENT_DIR . '/' ),
			'with empty base path'   => array( 'test', '', '/test' ),
			'with empty both params' => array( '', '', '/' ),
		);
	}

	#[DataProvider( 'for_path_empty_strings' )]
	public function test_path_empty_strings( string $folder_name, ?string $base_path, string $expected ): void {
		$logger = null === $base_path ? new Logger( $folder_name ) : new Logger( $folder_name, $base_path );

		$this->assertSame( $expected, $logger->path );
	}

	/** @return array<int, array<int, string>> */
	public static function for_every_channel_instances_are_cached(): array {
		return array(
			array( '' ),
			array( 'api' ),
			array( 'app' ),
		);
	}

	#[DataProvider( 'for_every_channel_instances_are_cached' )]
	public function test_every_channel_instances_are_cached( string $name ): void {
		$logger = new Logger();

		$channel = $logger->channel( $name );

		$this->assertInstanceOf( BaseLogger::class, $channel );
		$this->assertSame( $channel, $logger->channel( $name ) );
		$this->assertSame( $channel, $logger->channel( $name ) );
	}
}
