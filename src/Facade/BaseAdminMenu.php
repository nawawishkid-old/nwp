<?php

namespace NWP\Facade;

use NWP\Facade\AdminMenu;
use \InvalidArgumentException;

abstract class BaseAdminMenu
{
	const EVENT_ADMIN_MENU = 'admin_menu';
	
	protected $info = [
		'pageTitle' => '',
		'menuTitle' => '',
		'capability' => '',
		'menuSlug' => '',
		'pageContentHandler' => ''
	];

	/**
	 * Being used inside add_action callback
	 */
	abstract protected function add();

	/**
	 * @param string $menuSlug Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $menuSlug, string $menuTitle)
	{
		$this->info['menuSlug'] = $menuSlug;
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

	/**
	 * Set page title.
	 *
	 * @param string $title Page title.
	 *
	 * @return $this
	 */
	public function pageTitle(string $title)
	{
		$this->info['pageTitle'] = $title;

		return $this;
	}

	/**
	 * Authorization, set capability
	 *
	 * @param string $capability User's capability to access this page.
	 * 
	 * @return $this
	 */
	public function auth(string $capability)
	{
		$this->info['capability'] = $capability;

		return $this;
	}

	/**
	 * Set callback for echoing page content
	 *
	 * @param callable $callback Callback function for echoing page content.
	 *
	 * @return $this
	 */
	public function addPageContentHandler(callable $callback)
	{
		$this->info['pageContentHandler'] = $callback;

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
