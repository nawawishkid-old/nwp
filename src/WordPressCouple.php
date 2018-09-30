<?php

namespace NWP;

class WordPressCouple implements WordPressEvents 
{
	const WP_FUNCTIONS_ADD_ACTION = 'add_action';

	protected static $wpFunctions = [];

	/**
	 * Must call this method before instantiating any instance
	 */
	public static function addWPFunction(string $name, callable $callback)
	{
		self::$wpFunctions[$name] = $callback;
	}

	/**
	 * WP's add_action wrapper
	 */
	protected function wpAddAction(...$args)
	{
		$wpFunction = self::$wpFunctions[self::WP_FUNCTIONS_ADD_ACTION];

		if (empty($wpFunction)) {
			throw new Exception("WordPress function for Script::wpAddAction() has not been assigned.");
		}

		return call_user_func_array($wpFunction, $args);
	}
}
