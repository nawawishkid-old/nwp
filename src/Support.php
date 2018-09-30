<?php

namespace NWP;

use NWP\WordPressCouple;

class Support extends WordPressCouple
{
	private static $supports = [];

	public static function add(string $name, array $options = null)
	{
		self::$supports[] = [ 'name' => $name, 'options' => $options ];
	}

	public static function register()
	{
		self::wpAddAction(
			self::WP_EVENTS_AFTER_SETUP_THEME,
			function() { self::addThemeSupports(); }
		);
	}

	private static function addThemeSupports()
	{
		
	}
}
