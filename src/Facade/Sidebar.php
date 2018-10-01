<?php

namespace NWP\Facade;

class Sidebar
{
	const FUNCTION_IS_DYNAMIC_SIDEBAR = 'is_dynamic_sidebar';

	const FUNCTION_IS_ACTIVE_SIDEBAR = 'is_active_sidebar';

	const FUNCTION_DYNAMIC_SIDEBAR = 'dynamic_sidebar';

	const FUNCTION_REGISTER_SIDEBAR = 'register_sidebar';

	const FUNCTION_UNREGISTER_SIDEBAR = 'unregister_sidebar';	

	const EVENT_WIDGETS_INIT = 'widgets_init';

	/**
	 * Sidebar arguments
	 *
	 * @property
	 */
	private $info = [
		'name' => '',
		'id' => '',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	];

	/**
	 * Constructor.
	 *
	 * @see https://codex.wordpress.org/Sidebars
	 *
	 * @param string $id Sidebar ID.
	 * @param string $name Sidebar name.
	 * @param array $options Sidebar additional arguments.
	 */
	public function __construct(string $id, string $name, array $options = [])
	{
		$this->utils = Utils::getInstance();
		$this->info = array_merge($this->info, $options);
		$this->info['id'] = $id;
		$this->info['name'] = $name;
	}

	/**
	 * Check if the theme has any active sidebar.
	 *
	 * @uses \is_dynamic_sidebar()
	 * @see https://codex.wordpress.org/Function_Reference/is_dynamic_sidebar
	 *
	 * @return bool
	 */
	public static function exists()
	{
		return call_user_func(self::FUNCTION_IS_DYNAMIC_SIDEBAR);
	}

	/**
	 * Get Sidebar information.
	 *
	 * @return string Sidebar information. 
	 */
	public function __get(string $name)
	{
		return $this->info[$name];
	}

	/**
	 * Render Sidebar markup (Widget(s))
	 *
	 * @uses /dynamic_sidebar()
	 * @see https://codex.wordpress.org/Function_Reference/dynamic_sidebar
	 */
	public function render()
	{
		return call_user_func(self::FUNCTION_DYNAMIC_SIDEBAR, $this->name);
	}

	/**
	 * Check if the Sidebar is active i.e. has at least one widget
	 *
	 * @uses /is_active_sidebar()
	 * @see https://codex.wordpress.org/Function_Reference/is_active_sidebar
	 *
	 * @return bool
	 */
	public function isActive()
	{
		return call_user_func(self::FUNCTION_IS_ACTIVE_SIDEBAR, $this->name);
	}

	/**
	 * Register the Sidebar to WP
	 *
	 * @uses \$this->utils->addAction()
	 * @uses \register_sidebar()
	 * @see https://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	public function register()
	{
		$this->utils->addAction(self::EVENT_WIDGETS_INIT, function() {
			call_user_func(self::FUNCTION_REGISTER_SIDEBAR, $this->info);
		});	
	}

	/**
	 * Unregister the Sidebar from WP, usually useful for child theme.
	 *
	 * @uses \$this->utils->addAction()
	 * @uses \unregister_sidebar()
	 * @see https://codex.wordpress.org/Function_Reference/unregister_sidebar
	 */
	public function unregister()
	{
		$this->utils->addAction(self::EVENT_WIDGETS_INIT, function() {
			call_user_func(self::FUNCTION_UNREGISTER_SIDEBAR, $this->info);
		});	
	}

	/**
	 * Additional HTML Node before Widget's title.
	 *
	 * @return $this
	 */
	public function beforeTitle(string $value)
	{
		$this->info['before_title'] = $value;

		return $this;
	}
	
	/**
	 * Additional HTML Node after Widget's title.
	 *
	 * @return $this
	 */
	public function afterTitle(string $value)
	{
		$this->info['after_title'] = $value;

		return $this;
	}

	/**
	 * Additional HTML Node before Widget.
	 *
	 * @return $this
	 */
	public function beforeWidget(string $value)
	{
		$this->info['before_widget'] = $value;

		return $this;
	}

	/**
	 * Additional HTML Node after Widget.
	 *
	 * @return $this
	 */
	public function afterWidget(string $value)
	{
		$this->info['after_widget'] = $value;

		return $this;
	}	

	public function description(string $value)
	{
		$this->info['description'] = $value;

		return $this;
	}

	public function className(string $value)
	{
		$this->info['class'] = $value;

		return $this;
	}
}
