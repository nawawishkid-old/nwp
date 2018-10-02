<?php

namespace NWP\Facade;

use NWP\Facade\AbstractAdminMenu;
use NWP\Facade\AdminMenu;
use \InvalidArgumentException;

/**
 * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
 * @see https://codex.wordpress.org/Roles_and_Capabilities
 */
class AdminSubMenu extends AbstractAdminMenu
{
	const FUNCTION_ADD_SUBMENU_PAGE = 'add_submenu_page';

	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $id, string $menuTitle)
	{
		parent::__construct($id, $menuTitle);
	}

	/**
	 * Add parent menu slug
	 *
	 * @param string|AdminMenu Parent' slug string or an instance of AdminMenu
	 * 
	 * @return $this
	 */
	public function parent($parentMenu)
	{
		// Could be slug string or instance of AdminMenu
		$isAdminMenu = $parentMenu instanceof AdminMenu;

		if (!$isAdminMenu && !is_string($parentMenu)) {
			throw new InvalidArgumentException(
				sprintf(
					'Expected a parent menu to be an instance of %s or %s, %s given.',
					self::class,
					'string',
					gettype($parentMenu)
				)
			);
		}

		$parentSlug = $isAdminMenu 
			? $parentMenu->id
			: $parentMenu;

		$this->info['parentSlug'] = $parentSlug;

		return $this;
	}

	/**
	 * Being used inside add_action callback
	 */
	protected function add()
	{
		call_user_func_array(
			self::FUNCTION_ADD_SUBMENU_PAGE,
			[
				$this->parentSlug,
				$this->pageTitle,
				$this->menuTitle,
				$this->capability,
				$this->id,
				$this->pageContentRenderer
			]
		);
	}

	public function __toString()
	{
		return json_encode($this->info);
	}
}
