<?php

// https://codex.wordpress.org/Dashboard_Widgets_API

// เพิ่มฟังก์ชั่นเข้าไปใน Hook
add_action( 'wp_dashboard_setup', 'add_dashboard_widget' );

function add_dashboard_widget() {
	// WP API function
	wp_add_dashboard_widget(
		'nwp-dashboard-widget', // Slug use as CSS class name
		'NWP Dashboard Widget', // Title of the widget
		'display_dashboard_widget'// Function that display widget content
	);

	add_meta_box(
		'nwp-dashboard-widget-side', // ID
		'NWP Dashboard Widget Side', // Title of the widget
		'display_dashboard_widget', // Function that display widget content
		'dashboard', // Page to add widget
		'side', // Widget context
		'high' // Priority
	);

	make_widget_first_order();
	remove_dashboard_meta();
}

// For display widget content
function display_dashboard_widget() {
	echo 'This is NWP Dashboard Widget!';
	print_r( get_option( 'dashboard_widget_options' ) );
}

/**
 * [OPTIONAL] Make our widget stay on top of dashboard (above other widgets)
 *
 * There is no API to do this, we have to do it manually by
 * resorting $wp_meta_boxes array so that our widget becomes the first element of array
 * which is the first to be seen on dashboard.
 */
function make_widget_first_order() {
	// Get global meta boxes array which contains all of dashboard widget
	// including ours.
	global $wp_meta_boxes;

	// Get normal widgets. (There are other widget in the array. Try var_dump($wp_meta_boxes);)
	$normal_widgets = $wp_meta_boxes['dashboard']['normal']['core'];

	// Backup our widget, and remove it from the $normal_widgets array
	$nwp_widget = [
		'nwp-dashboard-widget' => $normal_widgets['nwp-dashboard-widget']
	];
	unset( $normal_widgets['nwp-dashboard-widget'] );

	// Merge normal widget with our widget so that our widget is a first element of array
	$sorted_widgets = array_merge( $nwp_widget, $normal_widgets );

	// Put sorted widgets back to global $wp_meta_boxes
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_widgets;
}

function remove_dashboard_meta() {
	//https://codex.wordpress.org/Function_Reference/remove_meta_box
    remove_meta_box( 
    	'dashboard_incoming_links', // ID
    	'dashboard', // Page
    	'normal' // Context: 'norma', 'side', 'advanced' (depends on page)
    );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}