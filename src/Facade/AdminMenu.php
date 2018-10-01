<?php

namespace NWP\Facade;

use NWP\Facade\BaseAdminMenu;

/**
 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
 * @see https://codex.wordpress.org/Roles_and_Capabilities
 */
class AdminMenu extends BaseAdminMenu
{
	const FUNCTION_ADD_MENU_PAGE = 'add_menu_page';

	const DASHBOARD_PAGE = 'dashboard';

	const POSTS_PAGE = 'posts';

	const MEDIA_PAGE = 'media';

	const PAGES_PAGE = 'pages';

	const COMMENTS_PAGE = 'comments';

	const THEME_PAGE = 'theme';

	const PLUGINS_PAGE = 'plugin';

	const USERS_PAGE = 'users';

	const MANAGEMENT_PAGE = 'management';

	const OPTIONS_PAGE = 'options';

	/**
	 * @param string $id Menu slug
	 * @param string $menuTitle Menu title
	 */
	public function __construct(string $id, string $menuTitle)
	{
		parent::__construct($id, $menuTitle);
	}

	public function iconUrl(string $url)
	{
		$this->info['iconUrl'] = $url;

		return $this;
	}

	public function position(int $position = null)
	{
		$this->info['position'] = $position;

		return $this;
	}

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
