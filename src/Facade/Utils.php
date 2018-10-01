<?php

namespace NWP\Facade;

use \Exception;
use \InvalidArgumentException;

class Utils 
{
	const SCRIPT_TYPE_JS = 'js';

	const SCRIPT_TYPE_CSS = 'css';

	const FUNCTION_ADD_ACTION = 'add_action';

	const FUNCTION_ENQUEUE_SCRIPT ='wp_enqueue_script';

	const FUNCTION_ENQUEUE_STYLE ='wp_enqueue_style';

	private static $instance;

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * WP's add_action wrapper
	 */
	public function addAction(...$args)
	{
		return call_user_func_array(self::FUNCTION_ADD_ACTION, $args);
	}

	public function enqueueJS(...$args)
	{
		$this->enqueueScript(self::SCRIPT_TYPE_JS, ...$args);
	}

	public function enqueueCSS(...$args)
	{
		$this->enqueueScript(self::SCRIPT_TYPE_CSS, ...$args);
	}

	public function enqueueScript(
		string $type, 
		string $id, 
		string $src, 
		array $dependencies = [], 
		$version = null, 
		$lastArgument = null
	)
	{
		if ($type !== self::SCRIPT_TYPE_JS && $type !== self::SCRIPT_TYPE_CSS) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected script type to be %s or %s, %s given',
					self::SCRIPT_TYPE_JS,
					self::SCRIPT_TYPE_CSS,
					$type
				)
			);
		}

		$funcName = $type === self::SCRIPT_TYPE_JS 
			? self::FUNCTION_ENQUEUE_SCRIPT 
			: self::FUNCTION_ENQUEUE_STYLE;
		$args = [$id, $src, $dependencies, $version, $lastArgument];

		return call_user_func_array($funcName, $args);
	}

	public function __construct() {}

	public function __invoke() {}

	public function __wakeup() {}
}
