<?php
/*
Plugin Name: WLD Plugins
*/

if ( ! defined( 'ACF_PRO_LICENSE' ) ) {
	define( 'ACF_PRO_LICENSE', 'b3JkZXJfaWQ9MTA1NDY3fHR5cGU9ZGV2ZWxvcGVyfGRhdGU9MjAxNy0wNS0wMyAwNDoyMToxOQ==' );
}
if ( ! defined( 'GF_LICENSE_KEY' ) ) {
	define( 'GF_LICENSE_KEY', 'e164f00eb6ff015840eaee9ffb099c52' );
}

$is_local = str_ends_with( $_SERVER['SERVER_NAME'], '.local' );
$is_wpe   = str_ends_with( $_SERVER['SERVER_NAME'], '.wpengine.com' ) || str_ends_with( $_SERVER['SERVER_NAME'], '.wpenginepowered.com' );

if ( $is_local || $is_wpe ) {
	if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_error_reporting, WordPress.PHP.DevelopmentFunctions.prevent_path_disclosure_error_reporting
		error_reporting( E_ALL & ~E_DEPRECATED );

		if ( ! defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) {
			// phpcs:ignore WordPress.PHP.IniSet.display_errors_Disallowed
			ini_set( 'display_errors', 1 );
		}

		add_filter(
			'qm/collect/php_error_levels',
			static function ( array $levels ) : array {
				$min_errors     = E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_USER_DEPRECATED;
				$active_plugins = (array) get_option( 'active_plugins', array() );

				$levels['theme']['stylesheet']                         = E_ALL;
				$levels['theme']['template']                           = E_ALL;
				$levels['core']['core']                                = $min_errors;
				$levels['mu-plugin']['wpe-cache-plugin']               = $min_errors;
				$levels['mu-plugin']['wpengine-common']                = $min_errors;
				$levels['mu-plugin']['wpengine-security-auditor.php']  = $min_errors;
				$levels['mu-plugin']['wpe-wp-sign-on-plugin']          = $min_errors;
				$levels['mu-plugin']['slt-force-strong-passwords.php'] = $min_errors;

				foreach ( $active_plugins as $active_plugin ) {
					$levels['plugin'][ explode( '/', $active_plugin )[0] ] = $min_errors;
				}

				return $levels;
			}
		);
	}

	require __DIR__ . '/wld-plugins/wld-helpers/wld-helpers.php';
	require __DIR__ . '/wld-plugins/wld-auto-login/wld-auto-login.php';
}

require __DIR__ . '/wld-plugins/wld-optimisations/wld-optimisations.php';
