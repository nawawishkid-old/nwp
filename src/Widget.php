<?php

namespace NWP;

/**
 * !BUG!: Still cannot update form.
 * Data from HTML form is not store in database, 
 * then the get_settings method of WP_Widget return null.
 * which is the $instance parameter of Widget::form()
 *
 * I haven't fount a way to fix it yet.
 *
 * Related code: 
 * @see ajax-actions.php > wp_ajax_save_widget()
 * @see class-wp-widget.php
 */

class Widget extends \WP_Widget {
	private $user_widget_callback;
	private $user_form_callback;
	private $user_update_callback;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct( $id, $name, $desc, $widget_callback = null, $form_callback = null, $update_callback = null ) {

		$this->user_widget_callback = $widget_callback;
		$this->user_form_callback = $form_callback;
		$this->user_update_callback = $update_callback;

		$widget_ops = array( 
			'classname' => $id,
			'description' => $desc,
		);

		parent::__construct( $id, $name, $widget_ops );

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		\call_user_func_array( $this->user_widget_callback, [$args, $instance, $this] );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		echo 'NWP\Widget::form()<br>';
		var_dump( $instance );
		// outputs the options form on admin
		\call_user_func_array( $this->user_form_callback, [$instance, $this] );
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		echo 'update!';
		echo '<pre>';
		var_dump( $new_instance );
		var_dump( $old_instance );
		echo '</pre>';
		
		\call_user_func_array( $this->user_update_callback, [$new_instance, $old_instance] );
	}

	public function addWidgetCallback( $callback ) {
		$this->user_widget_callback = $callback;
		return $this;
	}

	public function addFormCallback( $callback ) {
		$this->user_form_callback = $callback;
		return $this;
	}

	public function addUpdateCallback( $callback ) {
		$this->user_update_callback = $callback;
		return $this;
	}
}