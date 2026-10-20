<?php
/**
 * Disable translation if only one language is used.
 * It seems that this makes sense because it removes access to the disk for .mo files.
 * But you need to see if this has a visible effect or only hinders it.
 */
add_filter(
	'override_load_textdomain',
	'__return_true'
);

/**
 * We disable unnecessary plugins.
 * Some plugins are not needed on the front, but they are still plugged in and at best it is a single file.
 * But it happens that they start to connect their entire interior, and even styles with JS...
 */
add_filter(
	'option_active_plugins',
	static function ( array $active_plugins ) : array {
		$is_admin_not_ajax  = is_admin() && ! wp_doing_ajax();
		$is_ajax_from_admin = wp_doing_ajax() && strpos( $_SERVER['HTTP_REFERER'] ?? '', '/wp-admin/' );
		if ( defined( 'WP_CLI' ) || $is_admin_not_ajax || $is_ajax_from_admin ) {
			return $active_plugins;
		}

		$disabled_plugins = array(
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			'classic-editor'     => true,
			'duplicate-post'     => true,
			'wordpress-importer' => true,
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		);

		// We disable cookie banner plugin for test page speed.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['test'] ) && 'speed' === $_GET['test'] ) {
			$disabled_plugins['cookie-law-info'] = true;
		}

		foreach ( $active_plugins as $key => $plugin ) {
			if ( isset( $disabled_plugins[ explode( '/', $plugin )[0] ] ) ) {
				unset( $active_plugins[ $key ] );
			}
		}

		return $active_plugins;
	}
);

// We disable sticky and animation element for visual regression test.
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( isset( $_GET['test'] ) && 'visual_regression' === $_GET['test'] ) {
	add_action(
		'wp_head',
		static function () {
			?>
			<!--suppress CssUnusedSymbol -->
			<style id="for-autoTest-css">
				.header,
				.sticky {
					position: static !important;
				}

				.gfield--type-captcha,
				.cky-btn-revisit-wrapper,
				.cky-consent-container,
				::part(acsb-trigger) {
					display: none !important;
				}
			</style>
			<?php
		}
	);
}
