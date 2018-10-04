<?php

namespace NWP\Facade;

use \InvalidArgumentException;
use NWP\EventHandlerInterface;
use NWP\RenderableInterface;
use NWP\AbstractEventCollector;

use \NWP\RendererTrait;

class AdminSettingsGroup extends AbstractEventCollector implements EventHandlerInterface, RenderableInterface
{
	use RendererTrait;
	
	const FUNCTION_ADD_SETTINGS_SECTION = 'add_settings_section';

	const FUNCTION_ADD_SETTINGS_FIELD = 'add_settings_field';

	const FUNCTION_REGISTER_SETTING = 'register_setting';

	const EVENT_ADMIN_INIT = 'admin_init';

	private $info = [
		'id' => '',
		'title' => '',
		'fields' => [],
		'pageIds' => [] 
	];

	private $settingsForm = null;

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
		foreach ($this->pageIds as $pageId) {
			// add_settings_section($id, $title, $callback, $pageId);
			call_user_func_array(self::FUNCTION_ADD_SETTINGS_SECTION, [
				$this->id,
				$this->title,
				[$this, 'render'],
				$pageId
			]);

			foreach ($this->fields as $field) {
				// add_settings_field($id, $title, $callback, $pageId, $sectionId);
				call_user_func_array(self::FUNCTION_ADD_SETTINGS_FIELD, [
					$field->id,
					$field->title,
					[$field, 'render'],
					$pageId,
					$this->id
				]);
				
				// register_settings($pageId, $fieldId);
				call_user_func_array(self::FUNCTION_REGISTER_SETTING, [$pageId, $field->id]);
			}
		}
	}

	/**
	 * Add multiple form fields
	 *
	 * @see AdminSettingsGroup::addField()
	 */
	public function addFields(...$fields)
	{
		foreach ($fields as $field) {
			$this->addField($field);
		}
		
		return $this;
	}

	/**
	 * Add form field.
	 *
	 * @param AdminSettingsField $field Form field.
	 *
	 * @return $this
	 */
	public function addField($field)
	{
		if (!$field instanceof AdminSettingsField) {
			throw new InvalidArgumentException(
				sprintf(
					'Expecting $field parameter to be a valid %s, %s given',
					AdminSettingsField::class,
					gettype($field)
				)
			);
		}


		$this->info['fields'][] = $field;

		return $this;
	}


	/**
	 * Specify multiple pages which this setting group will be display upon.
	 *
	 * @see AdminSettingsGroup::displayOn();
	 */
	public function displayOns(...$pages)
	{
		foreach ($pages as $page) {
			$this->displayOn($page);
		}

		return $this;
	}

	/**
	 * Add the page which this settings group will be display upon.
	 *
	 * @param string|AdminPage $page Page string slug or instance of AdminPage
	 *
	 * @return $this
	 */
	public function displayOn($page)
	{
		$isAdminPageInstance = $page instanceof AdminPage;

		if (empty($page) || !$isAdminPageInstance && !is_string($page)) {
				throw new InvalidArgumentException(
					sprintf(
						"Expecting %s or %s, %s given.",
						'string',
						AdminPage::class,
						gettype($page)
					)
				);
			}

			// What about AdminSettingPage instance?
			$pageId = $isAdminPageInstance 
				? $page->id
				: $page;

			// if ($isAdminPageInstance && !is_null($this->settingsForm)) {
			// 	$page->addRenderer([$this->settingsForm, 'render']);
			// }

			$this->info['pageIds'][] = $pageId;

		return $this;	
	}

	/**
	 * Set settings group title.
	 *
	 * @param string $title Title.
	 *
	 * @return $this
	 */
	public function title(string $title)
	{
		$this->info['title'] = $title;

		return $this;
	}

	/**
	 * Set settings group id
	 *
	 * @param string $id Settings group ID.
	 *
	 * @return $this
	 */
	public function id(string $id)
	{
		$this->info['id'] = $id;

		return $this;
	}
}
