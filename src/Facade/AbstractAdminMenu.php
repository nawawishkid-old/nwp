<?php

namespace NWP\Facade;

use NWP\Facade\AdminMenu;
use NWP\Facade\AdminPage;
use \InvalidArgumentException;

abstract class AbstractAdminMenu
{
	const EVENT_ADMIN_MENU = 'admin_menu';
	
	protected $info = [
		'pageTitle' => '',
		'menuTitle' => '',
		'capability' => '',
		'id' => '',
		'pageContentRenderer' => ''
	];

	/**
	 * Being used inside add_action callback
	 */
	abstract protected function add();

	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $id, string $menuTitle)
	{
		$this->info['id'] = $id;
		$this->info['menuTitle'] = $menuTitle;

		$this->utils = Utils::getInstance();
	}

	/**
	 * Getter
	 */
	public function __get(string $name)
	{
		return $this->info[$name];
	}

	public function linkTo(AdminPage $page)
	{
		$this->info['capability'] = $page->capability;
		$this->info['pageTitle'] = $page->title;
		$this->info['pageContentRenderer'] = $page->contentRenderer;

		return $this;
	}

	/**
	 * Register submenu
	 */
	public function register()
	{
		$this->utils->addAction(
			self::EVENT_ADMIN_MENU,
			function() { $this->add(); }
		);
	}
}
