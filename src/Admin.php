<?php

namespace NWP;

use NWP\Interfaces\Styleable;
use NWP\Interfaces\Scriptable;
use NWP\Traits\ArgumentValidation;

/**
 * 
 */
class Admin implements Styleable, Scriptable {

	use ArgumentValidation;

	private $scripts = [];
	private $styles = [];
	private $dashboard_widgets = [];
	private $menus = [];
	private $hiddenMenuSlugs = [];

	/**
	 * @var array
	 */
	private $widgets = [];
	
	/**
	 * @var array
	 */
	private $hiddenWidgets = [];

	/**
	 * @var bool
	 */
	private $noWidget = false;

	/**
	 *
	 * @return void
	 */
	public function create() {
		if ( !empty( $this->widgets ) )
			\add_action( 'widgets_init', [$this, '_addWidgets'], 10 );

		if ( !empty( $this->hiddenWidgets ) || $this->noWidget )
			\add_action( 'widgets_init', [$this, '_hideWidgets'], 20 );

		\add_action( 'admin_enqueue_scripts', [$this, '_handleScripts'] );

		if ( ! empty( $this->hiddenMenuSlugs ) )
			\add_action( 'admin_menu', [$this, '_hideMenu']);
	}
	/**
	 *********************
	 * Menu Section
	 *********************
	 */
	public function hideMenu( $slug ) {
		if ( ! is_string( $slug ) && ! is_array( $slug ) )
			throw new \InvalidArgumentException("Type of given parameter is invalid.");

		if ( is_string( $slug ) )
			$this->hiddenMenuSlugs[] = $slug;

		if ( is_array( $slug ) )
			foreach ( $slug as $n ) {
				$this->hiddenMenuSlugs[] = $n;
			}

		return $this;
	}

	public function _hideMenu() {
		foreach ( $this->hiddenMenuSlugs as $slug ) {
			\remove_menu_page( $slug );
		}
	}
	/**
	 *********************
	 * End of Menu Section
	 *********************
	 */

	/**
	 *********************
	 * Scripts Section
	 *********************
	 */
	public function _handleScripts() {
		foreach ( $this->styles as $style ) {
			wp_enqueue_style( $style[0], $style[1] );
		}

		foreach ( $this->scripts as $script ) {
			wp_enqueue_script( $script[0], $script[1] );
		}
	}

	public function addStyle( $name, $path = null ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $n ) {
				$this->_addStyle( $n[0], $n[1] );
			}

			return $this;
		}

		$this->_addStyle( $name, $path );
		
		return $this;
	}

	private function _addStyle( $name, $path ) {
		if ( ! is_string( $name ) || ! is_string( $path ) )
			throw new \InvalidArgumentException("Type of given parameter is invalid.");

		$this->styles[] = [$name, \get_stylesheet_directory_uri() . '/' . $path];
	}

	public function addScript( $name, $path = null ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $n ) {
				$this->_addScript( $n[0], $n[1] );
			}

			return $this;
		}

		$this->_addScript( $name, $path );
		
		return $this;
	}

	private function _addScript( $name, $path ) {
		if ( ! is_string( $name ) || ! is_string( $path ) )
			throw new \InvalidArgumentException("Type of given parameter is invalid.");

		$this->scripts[] = [$name, \get_stylesheet_directory_uri() . '/' . $path];
	}
	/**
	 *********************
	 * End of Scripts Section
	 *********************
	 */

	/**
	 *********************
	 * Widgets Section
	 *********************
	 */
	/**
	 *
	 *
	 * @return $this
	 */
	public function _addWidgets() {
		foreach ( $this->widgets as $widget ) {
			\register_widget( $widget );
		}

		return $this;
	}

	public function _hideWidgets() {
		// Hide all widgets
		if ( $this->noWidget ) {
			global $wp_widget_factory;

			if ( empty( $wp_widget_factory->widgets ) )
				return;

			$wp_widget_factory->widgets = [];

			return;
		}

		// Hide specific widget
		foreach ( $this->hiddenWidgets as $widget ) {
			\unregister_widget( $widget );
		}

	}

	public function addWidgets( array $widgets ) {
		foreach ( $widgets as $widget ) {
			$this->widgets[] = $widget;
		}

		return $this;
	}

	/**
	 * Add given widget to $this->widgets
	 *
	 * @param NPW\Widget $widget Widget to be added
	 * @return $this
	 */
	public function addWidget( Widget $widget ) {
		$this->widgets[] = $widget;
		return $this;
	}

	/**
	 * Hide specific registered widget.
	 *
	 * @see https://core.trac.wordpress.org/browser/tags/4.9.4/src/wp-includes/widgets.php#L134
	 *
	 * @param string | NWP\Widget $widget Name of WP_Widget subclass or NWP|Widget instance
	 * @return $this
	 */
	public function hideWidget( $widget ) {
		if ( ! is_string( $widget ) && ! $widget instanceof Widget )
			throw new \InvalidArgumentException("Given widget is invalid.");

		$this->hiddenWidgets[] = $widget;

		return $this;	
	}

	/**
	 * Add specific widget to $this->hiddenWidgets to be used in $this->_hideWidgets()
	 * or set $this->noWidget to true if null given.
	 *
	 * @param NULL | array $widgets Array of widgets or null if user wants to hide all registered widgets
	 * @return $this
	 */
	public function hideWidgets( $widgets = null ) {
		// Hide all widgets
		if ( is_null( $widgets ) ) {
			$this->noWidget = true;

	    	return $this;
		}

		if ( ! is_array( $widgets ) )
			throw new \InvalidArgumentException("Given parameter must be of type array.");

		foreach ( $widgets as $widget ) {
			$this->hideWidget( $widget );
		}

		return $this;
	}
	/**
	 *********************
	 * End of Widgets Section
	 *********************
	 */

	/**
	 *
	 * @param string|array|callable $callback_name Name of callable
	 * @return string|array|callable Callable string or array of content callback
	 */
	public static function getContentCallback( $callback_name ) {
		if ( is_callable( $callback_name ) )
			return $callback_name;

		$content_callback = ['ContentCallback', $callback_name];

		if ( is_callable( $content_callback ) )
			return $content_callback;
	}
}