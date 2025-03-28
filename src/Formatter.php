<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

declare(strict_types=1);

namespace ThemePlate\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class Formatter extends LineFormatter {

	public function __construct() {
		$format = '[%datetime%] %level_name% > %message% %context% %extra%';
		parent::__construct( $format, 'Y-m-d H:i:s', true, true );
	}

	public function format( LogRecord $record ): string {
		return trim( parent::format( $record ) ) . "\n";
	}


	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	protected function toJson( $data, bool $ignoreErrors = false ): string {

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		return print_r( $data, true );

	}

}
