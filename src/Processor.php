<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Logger;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class Processor implements ProcessorInterface {

	protected bool $context;

	public function __construct( bool $context ) {

		$this->context = $context;

	}

	public function __invoke( LogRecord $record ): LogRecord {

		$forced = array_key_exists( 'wp', $record['context'] ) ? 'wp' : array_search( 'wp', $record['context'], true );

		if ( $this->context || false !== $forced ) {
			$context = $record['context'];

			$extra = array_merge(
				$record['extra'],
				array(
					'doing_cron' => defined( 'DOING_CRON' ) && DOING_CRON,
					'doing_ajax' => defined( 'DOING_AJAX' ) && DOING_AJAX,
					'is_admin'   => defined( 'WP_ADMIN' ) && WP_ADMIN,
				)
			);

			unset( $context[ $forced ] );

			$record = call_user_func_array( array( $record, 'with' ), compact( 'context', 'extra' ) );
		}

		return $record;

	}
}
