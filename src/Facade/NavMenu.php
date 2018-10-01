<?php

namespace NWP\Facade;

class NavMenu
{
	const FUNCTION_WP_NAV_MENU = 'wp_nav_menu';

	const FUNCTION_REGISTER_NAV_MENU = 'register_nav_menu';

	const FUNCTION_REGISTER_NAV_MENUS = 'register_nav_menus';

	const FUNCTION_UNREGISTER_NAV_MENU = 'unregister_nav_menu';

	const FUNCTION_HAS_NAV_MENU = 'has_nav_menu';

	const EVENT_INIT = 'init';

	private static $locations = [];

	private $info = [
/*		'menu' => '',
		'menu_class' => '',
		'menu_id' => '',
		'container' => '',
		'container_class' => '',
		'container_id' => '',
		'fallback_cb' => false,
		'before' => '',
		'after' => '',
		'link_before' => '',
		'link_after' => '',
		'echo' => true,
		'depth' => 0,
		'walker' => '',
		'theme_location' => '',
		'items_wrap' => '',
		'item_spacing' => ''
*/
	];

	public function __construct() {}

	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public static function addLocation($location, string $description = null)
	{
		$locations = is_array($location) 
			? $location 
			: [$location => $description];

		self::$locations = array_merge(self::$locations, $locations);
	}

	/**
	 * @see https://codex.wordpress.org/Function_Reference/register_nav_menus
	 * @see https://codex.wordpress.org/Function_Reference/register_nav_menu
	 */
	public static function registerLocation()
	{
		$utils = Utils::getInstance();

		$utils->addAction(
			self::EVENT_INIT,
			function() { call_user_func(self::FUNCTION_REGISTER_NAV_MENUS, self::$locations); }
		);
	}

	/**
	 * @see https://codex.wordpress.org/Function_Reference/unregister_nav_menu
	 */
	public static function unregisterLocation(...$locations)
	{
		$utils = Utils::getInstance();

		$utils->addAction(
			self::EVENT_INIT,
			function() use ($locations) {
				foreach ($locations as $location) {
					call_user_func(self::FUNCTION_UNREGISTER_NAV_MENU, $location);
				}
			}
		);
	}

	/**
	 * ===== Need some testing! =====
	 *
	 * @see https://codex.wordpress.org/Function_Reference/has_nav_menu
	 */
	public static function locationHasMenu(string $name)
	{
		return has_nav_menu($name);
		return call_user_func(self::FUNCTION_HAS_NAV_MENU, $name);
	}

	public static function prepare()
	{
		return new static();
	}

	public function render()
	{
		return call_user_func(self::FUNCTION_WP_NAV_MENU, $this->info);
	}

	public function className(string $value)
	{
		$this->info['menu_class'] = $value;

		return $this;
	}

	public function id(string $value)
	{
		$this->info['menu_id'] = $value;

		return $this;
	}

	public function of(string $value)
	{
		$this->info['theme_location'] = $value;

		return $this;
	}

	public function depth($value)
	{
		$this->info['depth'] = $value;

		return $this;
	}

	public function onNotFound(callable $callback)
	{
		$this->info['fallback_cb'] = $callback;

		return $this;
	}

	public function beforeLinkTag(string $value)
	{
		$this->info['before'] = $value;

		return $this;
	}

	public function afterLinkTag(string $value)
	{
		$this->info['after'] = $value;

		return $this;
	}

	public function beforeLinkText(string $value)
	{
		$this->info['link_before'] = $value;

		return $this;
	}

	public function afterLinkText(string $value)
	{
		$this->info['link_after'] = $value;

		return $this;
	}
}
