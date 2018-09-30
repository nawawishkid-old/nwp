<?php
namespace NWP;

use \WP_Widget;
use NWP\Sidebar;
use \InvalidArgumentException;

class App
{
	private $textDomain = '';

	private $sidebars = [];

	private $widgets = [];

	private $supports = [];

	public function __construct()
	{

	}

	/**
	 * Boot the app
	 */
	public function boot()
	{
		$this->on('after_setup_theme', [$this, 'bootSupports']);
		$this->on('widgets_init', [$this, 'bootSidebars']);
		$this->on('widgets_init', [$this, 'bootWidgets']);	
	}

	/**
	 * Boot Sidebar
	 */
	public function bootSidebars()
	{
		if (empty($this->sidebars)) {
			return;
		}

		foreach ($this->sidebars as $sidebar) {
			register_sidebar($sidebar->info);
		}
	}

	/**
	 * Boot supports
	 */
	public function bootSupports()
	{
		if (empty($this->supports)) {
			return;
		}

		foreach ($this->supports as $name => $options) {
			$support_name = is_numeric($name) ? $options : $name;
			$support_options = is_array($options) ? $options : null;
			
			add_theme_support($support_name, $support_options);
		}
	}

	/**
	 * Boot Widget
	 */
	public function bootWidgets()
	{
		if (empty($this->widgets)) {
			return;
		}

		foreach ($this->widgets as $widget) {
			register_widget($widget);
		}
	}

	/**
	 * Add widget
	 *
	 * @api
	 * @param string $widgetClassName Widget's class name
	 * @return $this
	 */
	public function addWidget($widgetClassName)
	{
		$this->widgets[] = $widgetClassName;

		return $this;
	}

	/**
	 * Add supports.
	 *
	 * @api
	 * @param array $options
	 * @return $this
	 */
	public function supports($name, $options)
	{
		if (!is_string($name) && !is_int($name)) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected support name supplied to App to be a string or an integer, %s given.',
					gettype($name)
				)
			);
		}

		if (!is_string($options) && !is_array($options)) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected support options supplied to App to be a string or an array, %s given.',
					gettype($options)
				)
			);
		}

		$this->supports[$name] = $options;

		return $this;
	}

	/**
	 * Add Sidebar
	 *
	 * @param Sidebar $sidebar Instance of Sidebar
	 * @return $this
	 */
	public function addSidebar(Sidebar $sidebar)
	{
		$this->sidebars[$sidebar->id] = $sidebar;

		return $this;
	}

	/**
	 * Get specific Sidebar
	 *
	 * @param string $id ID of the Sidebar
	 * @return $this
	 */
	public function getSidebar(string $id)
	{
		return $this->sidebars[$id];
	}

	/**
	 * Map to WP's add_action()
	 */
	public function on(string $eventName, callable $callback, int $priority = null)
	{
		add_action($eventName, $callback, $priority);

		return $this;
	}

	/**
	 * Add text domain
	 */
	public function addTextDomain(string $textDomain)
	{
		$this->textDomain = $textDomain;

		return $this;
	}

	/**
	 * Add JavaScript script
	 */
	public function addScript(array $info)
	{
	}

	public function addStyle(array $info)
	{
	}
}
