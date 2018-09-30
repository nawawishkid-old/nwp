<?php

namespace NWP;

interface WordPressEvents 
{
	const WP_EVENTS_WP_ENQUEUE_SCRIPTS = 'wp_enqueue_scripts';

	const WP_EVENTS_AFTER_SETUP_THEME = 'after_setup_theme';
}
