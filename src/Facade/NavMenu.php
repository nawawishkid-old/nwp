<?php

namespace NWP\Facade;

class NavMenu
{
	const FUNCTION_WP_NAV_MENU = 'wp_nav_menu';

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
