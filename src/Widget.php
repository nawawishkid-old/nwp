<?php

namespace NWP;

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
		\call_user_func_array( $this->user_widget_callback, [$args, $instance] );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		\call_user_func_array( $this->user_form_callback, [$args, $instance] );
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