<?php

namespace NWP;

use NWP\Interfaces\Styleable;
use NWP\Interfaces\Scriptable;
use NWP\Traits\ArgumentValidation;

class SubMenu implements Styleable, Scriptable {
	use ArgumentValidation;

	const DEFAULT_CAPABILITY = 'manage_options';

	protected $menuTitle;
	protected $pageTitle;
	protected $slug;
	protected $capability;
	protected $callback = '';
	protected $styles = [];
	protected $scripts = [];

	public function __construct( $menuTitle, $slug, $capability = '', $callback = '' ) {
		$this->addMenuTitle( $menuTitle );
		$this->addSlug( $slug );
		$this->addCapability( $capability );

		if ( ! empty( $callback ) )
			$this->addCallback( $callback );
	}

	/**
	 *********************
	 * Setter Methods
	 *********************
	 */
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

	public function addCallback( $callback ) {
		$callback = $this->isCallable( $callback );

		$this->callback = $callback;

		return $this;
	}

	public function addStyle( $name, $path ) {
		\add_action( 'admin_enqueue_scripts', function( $hookSuffix ) use ( $name, $path ) {
			var_dump($hookSuffix);
			if ( preg_match( "/$this->slug$/", $hookSuffix ) )
				\wp_enqueue_style( $name, $path );
		});

		return $this;
	}

	public function addScript( $name, $path ) {
		\add_action( 'admin_enqueue_scripts', function( $hookSuffix ) use ( $name, $path ) {
			if ( preg_match( "/$this->slug$/", $hookSuffix ) )
				\wp_enqueue_script( $name, $path );
		});

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
	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function getMenuTitle() {
		return $this->menuTitle;
	}
	
	public function getCapability() {
		return $this->capability;
	}
	
	public function getSlug() {
		return $this->slug;
	}
	
	public function getCallback() {
		return $this->callback;
	}
	/**
	 *********************
	 * End of Getter Methods
	 *********************
	 */

	public function defaultContentCallback() {
		echo <<<TXT
<h3>This is default content.</h3>
<p>You're seeing this because you have not given content callback in either `\NWP\Menu` class constructor or its `addCallback` method.</p>
TXT;
	}
}