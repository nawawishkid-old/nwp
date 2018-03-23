<?php

namespace NWP;

class DashboardAPI {
	private static $widget_context = [ 'normal', 'side', 'advanced' ];

	public static function addWidgets( array $widgets ) {

		array_walk( $widgets, function( $widget_info ) {

			if ( ! is_array( $widget_info ) || count( $widget_info ) !== 3 )
				throw new InvalidArgumentException("Given widget information in $widgets array must by of type array with length of 3.");

			self::addWidget( $widget_info[0], $widget_info[1], $widget_info[2] );

		});

	}

	public static function addWidget( $title, $slug = '', $content_callback ) {

		if ( empty( $title ) || ! is_string( $title) )
			throw new InvalidArgumentException("Given widget title is either empty or not type of string.");

		// If slug is not given, automatically create it by
		// making $title becomes lower case and replace space with underscore.
		// Make it legit for CSS Class name.
		$_slug = empty( $slug ) ? str_replace( ' ', '_', strtolower( $title ) ) : $slug;

		\wp_add_dashboard_widget(
			$_slug, // Slug use as CSS class name
			$title, // Title of the widget
			$content_callback // Name of function that display widget content
		);

	}

	public static function removeWidgets( array $widgets ) {

		array_walk( $widgets, function( $widget_info ) {

			if ( ! is_array( $widget_info ) || count( $widget_info ) !== 2 )
				throw new InvalidArgumentException("Given widget information in $widgets array must by of type array with length of 2.");

			self::removeWidget( $widget_info[0], $widget_info[1] );

		});

	}

	public static function removeWidget( $widget_id, $context ) {
		
		if ( empty( $widget_id ) || ! is_string( $widget_id ) )
			throw new InvalidArgumentException("Given widget ID is either empty or not type of string.");

		if ( ! in_array( $context, self::$widget_context ) )
			throw new InvalidArgumentException("Given widget context is unknown.");

		\remove_meta_box( $value, 'dashboard', $context );

	}

}