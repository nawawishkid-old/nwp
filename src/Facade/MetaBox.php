<?php

namespace NWP\Facade;

use NWP\Facade\Utils;
use NWP\AbstractSelfRegisterController;

/**
 * @see https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
 * @see https://developer.wordpress.org/reference/functions/add_meta_box/
 * @see https://codex.wordpress.org/Plugin_API/Admin_Screen_Reference
 */
class MetaBox extends AbstractSelfRegisterController
{
	const EVENT_WP_DASHBOARD_SETUP = 'wp_dashboard_setup';

	const EVENT_ADD_META_BOXES = 'add_meta_boxes';

	const FUNCTION_ADD_META_BOX = 'add_meta_box';

	private $info = [
		'id' => '',
		'title' => '',
		'contentRenderer' => '',
		'screens' => [],
		'context' => null,
		'priority' => null
	];

	/**
	 * @property bool Is one of the screens for this meta box dashboard.
	 */
	private $oneOfScreensIsDashboard = false;

	public function __construct(string $id, string $title)
	{
		$this->utils = Utils::getInstance();
		$this->info['id'] = $id;
		$this->info['title'] = $title;
	}

	public function __get(string $name)
	{
		return $this->info[$name];
	}

	protected function getEventName() : string
	{
		return self::EVENT_ADD_META_BOXES;
	}

	protected function action() : void
	{
		call_user_func_array(
			self::FUNCTION_ADD_META_BOX,
			[
				$this->id,
				$this->title,
				$this->contentRenderer,
				$this->screens,
				$this->context,
				$this->priority
			]
		);
	}

	private function oneOfScreensIsDashboard()
	{
		return in_array('dashboard', $this->screens);
	}

	private function onDashboardScreen()
	{
		$this->eventCollector->on(self::EVENT_WP_DASHBOARD_SETUP, [$this, 'eventHandler']);
	}

	/**
	 * Custom register
	 */
	public function register() : void
	{
		$oneOfScreensIsDashboard = $this->oneOfScreensIsDashboard();
		$numScreens = count($this->screens);
		
		if ($oneOfScreensIsDashboard && $numScreens > 1) {
			parent::register();
			$this->onDashboardScreen();
		} elseif ($oneOfScreensIsDashboard) {
			$this->onDashboardScreen();
		} else {
			parent::register();
		}
	}

	public function addContentRenderer($renderer)
	{
		$this->info['contentRenderer'] = $renderer;

		return $this;
	}

	/**
	 * Which screen to display
	 *
	 * @param string|WP_Screen|AdminPage $screen Screen which the meta box will be displayed.
	 *
	 * @return $this
	 */
	public function displayOn($screen)
	{
		if (is_array($screen)) {
			$this->info['screens'] = array_merge($this->screens, $screen);
		} else {
			$this->info['screens'][] = $screen;
		}

		return $this;
	}

	public function context(string $ctx)
	{
		$this->info['context'] = $ctx;

		return $this;
	}

	/**
	 *
	 *
	 * @param string $value Enumeration of 'high', 'low', 'default'
	 */
	public function priority(string $value)
	{
		$this->info['priority'] = $value;

		return $this;
	}
}
