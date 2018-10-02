<?php

namespace NWP\Facade;

use NWP\Facade\AbstractAdminMenu;

/**
 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
 * @see https://codex.wordpress.org/Roles_and_Capabilities
 */
class AdminMenu extends AbstractAdminMenu
{
	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $id, string $menuTitle)
	{
		parent::__construct($id, $menuTitle);
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
	protected function add()
	{
		call_user_func_array(
			self::FUNCTION_ADD_MENU_PAGE,
			[
				$this->pageTitle,
				$this->menuTitle,
				$this->capability,
				$this->id,
				$this->pageContentRenderer,
				$this->iconUrl,
				$this->position
			]
		);
	}
}
