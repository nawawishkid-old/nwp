<?php

namespace NWP;

/**
 *
 *
 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
 */

class Menu implements HTMLPage {

	use ArgumentValidation;

	const DEFAULT_CAPABILITY = 'manage_options';

	private $menuTitle;
	private $pageTitle;
	private $slug;
	private $capability;
	private $callback = '';
	private $iconURL = '';
	private $submenus = [];

	public function __construct( $menuTitle, $capability = DEFAULT_CAPABILITY, $callback = '' ) {
		$this->addMenuTitle( $menuTitle );
		$this->addCapability( $capability );

		if ( ! empty( $callback ) )
			$this->addContentCallback( $callback );
	}

	public function create() {
		\add_menu_page(
			$this->pageTitle | $this->menuTitle,
			$this->menuTitle,
			$this->capability,
			$this->callback | [$this, 'defaultContentCallback'],
			$this->iconURL
		);
	}

	public function forceCreate() {
		\add_action( 'admin_menu', [$this, 'create'] );
	}

	public function addMenuTitle( $title ) {
		$title = $this->isString( $title );

		$this->menuTitle = $title;

		return $this;
	}

	public function addPageTitle( $title ) {
		$title = $this->isString( $title );

		$this->pageTitle = $title;

		return $this;
	}

	public function addSlug( $slug ) {
		$slug = $this->isString( $slug );

		$this->slug = $slug;

		return $this;
	}

	public function addCapability( $capability ) {
		$capability = $this->isString( $capability );

		$this->capability = $capability;

		return $this;
	}

	public function addContentCallback( $capability ) {
		$capability = $this->isCallable( $capability );

		$this->capability = $capability;

		return $this;
	}

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

	public function addSubmenu( self $menu ) {
		$this->submenus[] = $menu;

		return $this;
	}

	private function defaultContentCallback() {
		echo <<<TXT
<h3>This is default content.</h3>
<p>You're seeing this because you have not given content callback in either \NWP\Menu class constructor or its `addCallback` method.</p>
TXT;
	}
}