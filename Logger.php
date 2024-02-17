<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use Monolog\Logger as BaseLogger;
use ThemePlate\Logger\Handler;
use ThemePlate\Logger\Processor;

class Logger {

	public readonly string $path;
	/**
	 * @var array<string, BaseLogger>
	 */
	private static array $instances = array();


	public function __construct( string $folder_name = 'logs', string $base_path = WP_CONTENT_DIR ) {

		$path_parts = array(
			$this->prepare_pathname( $base_path, false ),
			$this->prepare_pathname( $folder_name ),
		);

		$this->path = implode( DIRECTORY_SEPARATOR, $path_parts );

	}


	protected function prepare_pathname( string $value, bool $both_sides = true ): string {

		static $characters = '/\\ ';

		$value = rtrim( $value, $characters );

		if ( $both_sides ) {
			$value = ltrim( $value, $characters );
		}

		return $value;

	}


	public function channel( string $name, bool $context = false ): BaseLogger {

		if ( ! isset( self::$instances[ $name ] ) ) {
			$channel = $this->path . DIRECTORY_SEPARATOR . $name . '.log';

			self::$instances[ $name ] = new BaseLogger(
				$name,
				array( new Handler( $channel ) ),
				array( new Processor( $context ) )
			);
		}

		return self::$instances[ $name ];

	}

}
