<?php

namespace NWP\Facade;

use NWP\EventHandlerInterface;
use \NWP\RenderableInterface;
use NWP\AbstractEventCollector;

use \NWP\RendererTrait;

class AdminSettingsField extends AbstractEventCollector implements EventHandlerInterface, RenderableInterface
{
	use RendererTrait;

	const FUNCTION_ADD_SETTINGS_SECTION = 'add_settings_section';

	const FUNCTION_ADD_SETTINGS_FIELD = 'add_settings_field';

	const EVENT_ADMIN_INIT = 'admin_init';

	private $info = [
		'id' => '',
		'title' => '',
		'pageId' => ''
	];

	public function __construct(string $id = null, string $title = null, callable $renderer = null)
	{
		$this->info['id'] = $id;
		$this->info['title'] = $title;

		if (!is_null($renderer)) {
			$this->addRenderer($renderer);
		}
	}

	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public function register() : void
	{
		$this->eventCollector->on(self::EVENT_ADMIN_INIT, [$this, 'eventHandler']);
		$this->eventCollector->register();	
	}

	public function eventHandler() : void
	{
		call_user_func_array(self::FUNCTION_ADD_SETTINGS_FIELD, [
			$this->id,
			$this->title,
			[$this, 'render'], 
			$this->pageId,
			$this->groupId
		]);
	}

	/**
	 * Specify settings group this settings field belongs to.
	 *
	 * @param AdminSettingsGroup $settingsGroup Instance of AdminSettingsGroup.
	 *
	 * @return $this
	 */
	public function belongsTo(AdminSettingsGroup $settingsGroup)
	{
		$this->info['pageId'] = $settingsGroup->pageId;
		$this->info['groupId'] = $settingsGroup->id;

		return $this;
	}

	public function title(string $title)
	{
		$this->info['title'] = $title;

		return $this;
	}

	public function id(string $id)
	{
		$this->info['id'] = $id;

		return $this;
	}
}
