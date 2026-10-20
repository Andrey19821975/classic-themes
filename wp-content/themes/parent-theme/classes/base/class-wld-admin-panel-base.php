<?php

class WLD_Admin_Panel_Base {
	public static function init() : void {
		add_action(
			'admin_menu',
			array( static::class, 'hide_admin_pages' ),
			999,
			0
		);
		add_action(
			'admin_head',
			array( static::class, 'hide_customize' )
		);
	}

	public static function hide_admin_pages() : void {
		if ( apply_filters( 'wld_hide_comments', true ) ) {
			remove_menu_page( 'edit-comments.php' );
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
			add_filter(
				'pre_comment_approved',
				static function () {
					return new WP_Error( 'comment_create_error', 'You can"t create comments' );
				}
			);
		}
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
		remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
		remove_submenu_page( 'tools.php', 'tools.php' );
		remove_submenu_page( 'options-general.php', 'options-media.php' );
	}

	public static function hide_customize() : void {
		echo '<style>.hide-if-no-customize { display: none !important; }</style>';
	}
}
