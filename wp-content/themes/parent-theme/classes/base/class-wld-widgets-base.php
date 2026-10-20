<?php

class WLD_Widgets_Base {
	public static function init() : void {
		remove_action( 'after_setup_theme', 'wp_setup_widgets_block_editor', 1 );
		remove_action( 'init', 'wp_widgets_init', 1 );
		remove_action( 'change_locale', array( 'WP_Widget_Media', 'reset_default_labels' ) );
		remove_action( 'widgets_init', '_wp_block_theme_register_classic_sidebars', 1 );
	}
}
