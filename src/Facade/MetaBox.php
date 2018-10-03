<?php

namespace NWP\Facade;

use NWP\AbstractEventCollector;

/**
 * @see https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
 * @see https://developer.wordpress.org/reference/functions/add_meta_box/
 * @see https://codex.wordpress.org/Plugin_API/Admin_Screen_Reference
 */
class MetaBox extends AbstractEventCollector 
{
	const EVENT_WP_DASHBOARD_SETUP = 'wp_dashboard_setup';

	const EVENT_ADD_META_BOXES = 'add_meta_boxes';

	const FUNCTION_ADD_META_BOX = 'add_meta_box';

	const SCREEN_DASHBOARD = 'dashboard';

	const SCREEN_CONTEXT_NORMAL = 'normal';

	const SCREEN_CONTEXT_ADVANCED = 'advanced';

	const SCREEN_CONTEXT_SIDE = 'side';

	const PRIORITY_HIGH = 'high';

	const PRIORIY_LOW = 'low';

	const PRIORITY_DEFAULT = 'default';

	private $info = [
		'id' => '',
		'title' => '',
		'contentRenderer' => ''
	];

	private $contextScreensMap = [];

	public function __construct(string $id, string $title, callable $contentRenderer = null)
	{
		$this->info['id'] = $id;
		$this->info['title'] = $title;
		$this->info['contentRenderer'] = $contentRenderer;
	}

	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public function register() : void
	{
		// Iterate through screen context
		foreach ($this->contextScreensMap as $context => $detail) {

			// Iterate through meta box priority	
			foreach ($detail as $priority => $screens) {
				$eventName = in_array(self::SCREEN_DASHBOARD, $screens)
					? self::EVENT_WP_DASHBOARD_SETUP
					: self::EVENT_ADD_META_BOXES;
				
				$this->eventCollector->on($eventName, function () use ($context, $screens, $priority) {
					call_user_func_array(self::FUNCTION_ADD_META_BOX, [
						$this->id,
						$this->title,
						$this->contentRenderer,
						$screens,
						$context,
						$priority
					]);
				});
			}
		}

		$this->eventCollector->register();
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
	public function displayOn($screen, string $context, string $priority = null)
	{
		// Group screens by common context
		if (!isset($this->contextScreensMap[$context])) {
			$this->contextScreensMap[$context] = [];
		}

		$screenKey = is_null($priority) ? 0 : $priority;

		// Group screens by common priority
		if (!isset($this->contextScreensMap[$context][$screenKey])) {
			$this->contextScreensMap[$context][$screenKey] = [];
		}
	
		$this->contextScreensMap[$context][$screenKey][] = $screen;

		return $this;
	}
}
