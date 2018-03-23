<?php

namespace NWP;

use NWP\ContentCallback;

/**
 * 
 */
class Admin {

	private $scripts = [];
	private $styles = [];
	private $dashboard_widgets = [];
	private $pages = [];

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