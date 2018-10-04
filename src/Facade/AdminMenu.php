<?php

namespace NWP\Facade;

use NWP\Facade\AbstractAdminMenu;

/**
 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
 * @see https://codex.wordpress.org/Roles_and_Capabilities
 */
class AdminMenu extends AbstractAdminMenu 
{
	const FUNCTION_ADD_MENU_PAGE = 'add_menu_page';

	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $menuTitle)
	{
		parent::__construct($menuTitle);
	}

	/**
	 * Set menu icon url.
	 *
	 * @param string $url Icon's url.
	 *
	 * @return $this
	 */
	public function iconUrl(string $url)
	{
		$this->info['iconUrl'] = $url;

		return $this;
	}

	/**
	 * Set menu position.
	 *
	 * @param int $position Menu position.
	 *
	 * @return $this
	 */
	public function position(int $position = null)
	{
		$this->info['position'] = $position;

		return $this;
	}

	/**
	 * Call WP's admin_menu_page(), used inside parent::register()
	 */
	protected function action() : void
	{
		call_user_func_array(
			self::FUNCTION_ADD_MENU_PAGE,
			[
				$this->pageTitle,
				$this->menuTitle,
				$this->capability,
				$this->id,
				$this->pageRenderer,
				$this->iconUrl,
				$this->position
			]
		);
	}
}
