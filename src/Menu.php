<?php

namespace NWP;

/**
 *
 *
 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
 */

class Menu extends SubMenu {
	private $iconURL = '';
	private $position = null;
	private $submenus = [];

	public function __construct( $menuTitle, $slug, $capability = '', $callback = '' ) {
		parent::__construct( $menuTitle, $slug, $capability, $callback );
		/*
		$this->addMenuTitle( $menuTitle );
		$this->addSlug( $slug );
		$this->addCapability( $capability );

		if ( ! empty( $callback ) )
			$this->addCallback( $callback );
		*/
	}

	public function create() {

		\add_action( 'admin_menu', function() {
			\add_menu_page(
				! is_null( $this->pageTitle ) ? $this->pageTitle : $this->menuTitle,
				$this->menuTitle,
				! empty( $this->capability ) ? $this->capability : DEFAULT_CAPABILITY,
				$this->slug,
				! empty( $this->callback ) ? $this->callback : [$this, 'defaultContentCallback'],
				$this->iconURL,
				$this->position
			);
		});

		// Add submenu if submenu exist
		if ( empty( $this->submenus ) )
			return $this;

		\add_action( 'admin_menu', function() {
			foreach ( $this->submenus as $submenu ) {
				\add_submenu_page(
					$this->slug,
					! empty( $submenu->getPageTitle() ) ? $submenu->getPageTitle() : $submenu->getMenuTitle(),
					$submenu->getMenuTitle(),
					! empty( $submenu->getCapability() ) ? $submenu->getCapability() : DEFAULT_CAPABILITY,
					$submenu->getSlug(),
					$submenu->getCallback()
				);
			}
		});
	}

	/**
	 *********************
	 * Setter Methods
	 *********************
	 */
	public function addIcon( $iconURL ) {
		$iconURL = $this->isString( $iconURL );

		$this->iconURL = $iconURL;

		return $this;
	}

	public function addPosition( $position ) {
		$position = $this->isInt( $position );

		$this->position = $position;

		return $this;
	}

	public function addSubmenu( SubMenu $menu ) {
		$this->submenus[] = $menu;

		return $this;
	}
	/**
	 *********************
	 * End of Setter Methods
	 *********************
	 */

	/**
	 *********************
	 * Getter Methods
	 *********************
	 */
	/**
	 *********************
	 * End of Getter Methods
	 *********************
	 */
}