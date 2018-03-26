<?php

namespace NWP;

/**
 *
 *
 * @see https://codex.wordpress.org/Adding_Administration_Menus
 */

class Menu extends SubMenu {
	private $iconURL = '';
	private $position = null;
	private $submenus = [];

	public function __construct( $menuTitle, $slug, $capability = '', $callback = '' ) {
		parent::__construct( $menuTitle, $slug, $capability, $callback );
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
		$iconURL = self::isString( $iconURL );

		$this->iconURL = $iconURL;

		return $this;
	}

	public function addPosition( $position ) {
		$position = self::isInt( $position );

		$this->position = $position;

		return $this;
	}

	public function addSub( SubMenu $menu ) {
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
	 * Menu hiding
	 * @see https://codex.wordpress.org/Function_Reference/remove_menu_page
	 * @see https://codex.wordpress.org/Function_Reference/remove_submenu_page
	 *********************
	 */
	/**
	 * Decides whether to hide single or multiple menus based on given arguments.
	 * 
	 * When the decision is made, it will specify the name of 
	 * another related method for being used as a callback in 
	 * WordPress `add_action` function.
	 *
	 * @param string|Menu|array $firstArg :
	 *					string 		Slug of either menu which will be hidden 
	 *								or menu which its submenu will be hidden.
	 *					Menu 		Instance of NWP\Menu which will be hidden
	 * 								or menu which its submenu will be hidden.
	 * 					array 		Array of either slug name or Menu instance.
	 * @param string|SubMenu|NULL $subMenuSlug :
	 * 					string 		Slug of submenu to be hidden.
	 *  				SubMenu 	Instance of NWP\SubMenu to be hidden.
	 *  				NULL 		This $subMenuSlug can be null 
	 * 								if the $firstArg is an array which means
	 * 								you want to hide multiple menus or submenus.
	 * @return $this
	 */
	public function hide( $firstArg, $subMenuSlug = null ) {
		if ( is_string( $firstArg ) && is_string( $subMenuSlug ) ) {
			$func = [self, '_hideSingle'];
		} elseif ( is_array( $firstArg ) ) {
			$func = [self, '_hideMultiple'];
		}

		\add_action( 'admin_menu', function() use ( $func, $firstArg, $subMenuSlug ) {
			\call_user_func_array( $func, [$firstArg, $subMenuSlug] );
		});

		return $this;
	}

	/**
	 * Call `Menu::_hideSingle` for each of given-arguement array.
	 *
	 * After specifying whether given argument is just an array of slug string
	 * or a nested array, this method will call `Menu::_hideSingle` to do the hiding job.
	 * This method may be used as a callback in WordPress `add_action` function.
	 *
	 * @param array $slugList 	Array of either top-level menu slug or
	 *							menu-submenu array to be hidden.
	 * @return void
	 */
	public static function _hideMultiple( array $slugList ) {
		foreach ( $slugList as $slug ) {
			if ( is_array( $slug ) ) {
				self::_hideSingle( $slug[0], $slug[1] );
				continue;
			}

			self::_hideSingle( $slug );
		}
	}

	/**
	 * Validate user-given arguments and decides to call 
	 * one ofWordPress menu hiding function based on validated arguments.
	 *
	 * This method may be used as a callback in WordPress `add_action` function.
	 *
	 * @param string|Menu $menuSlug :
	 * 				string 		Slug of top-level menu either itself to be hidden
	 * 							or its submenu to be hidden.
	 * 				Menu 		Instance of NWP\Menu either itself to be hidden
	 * 							or its submenu to be hidden.
	 * @param string|SubMenu|NULL $subMenuSlug :
	 *  			string 		Slug of submenu to be hidden.
	 * 				SubMenu 	Instance of NWP\SubMenu to be hidden.
	 *  			NULL 		This $subMenuSlug can be null 
	 * 							if the $firstArg is an array which means
	 * 							you want to hide multiple menus or submenus.	
	 * @return void
	 */
	public static function _hideSingle( $menuSlug, $subMenuSlug = null ) {
		// Menu slug validation
		$menuSlug = is_string( $menuSlug ) 
				 	? $menuSlug 
				 	: ( $menuSlug instanceof Menu ? $menuSlug->getSlug() : null );

		if ( is_null( $menuSlug ) )
			throw new \InvalidArgumentException( "Menu slug must be either type of string or instance of NWP\Menu" );

		// Check whether this is hiding of menu or submenu
		if ( is_null( $subMenuSlug ) ) {
			\remove_menu_page( $menuSlug );
		} else {
			// Submenu slug validation
			$subMenuSlug = is_string( $subMenuSlug ) 
						 	? $subMenuSlug 
						 	: ( $subMenuSlug instanceof SubMenu ? $subMenuSlug->getSlug() : null );

			if ( is_null( $subMenuSlug ) )
				throw new InvalidArgumentException( "Submenu slug must be either type of string or instance of NWP\SubMenu" );

			\remove_submenu_page( $menuSlug, $subMenuSlug );
		}
	}
	/**
	 *********************
	 * End of Menu hiding
	 *********************
	 */
}