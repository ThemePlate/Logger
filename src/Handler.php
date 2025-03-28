<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

declare(strict_types=1);

namespace ThemePlate\Logger;

use Monolog\Level;
use Monolog\Handler\RotatingFileHandler;

class Handler extends RotatingFileHandler {

	public function __construct(
		string $channel,
		int|string|Level $level = Level::Debug,
		bool $bubble = true,
	) {
		parent::__construct( $channel, 0, $level, $bubble, 0664, true, 'Y/m/d', '{date}/{filename}' );
		$this->setFormatter( new Formatter() );
	}

}
