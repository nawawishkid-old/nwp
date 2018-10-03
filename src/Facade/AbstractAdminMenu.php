<?php

namespace NWP\Facade;

use NWP\EventHandlerInterface;
use NWP\AbstractEventCollector;
use NWP\Facade\AdminMenu;
use NWP\Facade\AdminPage;
use \InvalidArgumentException;

abstract class AbstractAdminMenu extends AbstractEventCollector implements EventHandlerInterface
{
	const EVENT_ADMIN_MENU = 'admin_menu';
	
	protected $info = [
		'pageTitle' => '',
		'menuTitle' => '',
		'capability' => '',
		'id' => '',
		'pageContentRenderer' => ''
	];

	abstract protected function action() : void;

	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $id, string $menuTitle)
	{
		$this->info['id'] = $id;
		$this->info['menuTitle'] = $menuTitle;
	}

	/**
	 * Getter
	 */
	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public function register() : void
	{
		$this->eventCollector->on(self::EVENT_ADMIN_MENU, [$this, 'eventHandler']);
		$this->eventCollector->register();	
	}

	public function eventHandler() : void
	{
		$this->action();
	}

	public function linkTo(AdminPage $page)
	{
		$this->info['capability'] = $page->capability;
		$this->info['pageTitle'] = $page->title;
		$this->info['pageContentRenderer'] = $page->contentRenderer;

		return $this;
	}
}
