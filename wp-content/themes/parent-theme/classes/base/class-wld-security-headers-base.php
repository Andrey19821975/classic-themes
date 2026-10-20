<?php

class WLD_Security_Headers_Base {
	protected static array $headers = array(
		'X-Content-Type-Options'       => 'nosniff',
		'X-Frame-Options'              => 'SAMEORIGIN',
		'Cross-Origin-Resource-Policy' => 'same-origin',
		'Referrer-Policy'              => 'strict-origin-when-cross-origin',
		'Permissions-Policy'           => 'camera=(), geolocation=(), microphone=(), payment=(), usb=()',
		'Strict-Transport-Security'    => 'max-age=63072000; includeSubDomains; preload',
	);

	public static function init() : void {
		add_filter(
			'wp_headers',
			array( static::class, 'send_headers' )
		);
		add_action(
			'wp',
			array( static::class, 'clear_headers' )
		);
	}

	public static function send_headers( array $headers ) : array {
		$security_headers = apply_filters( 'theme_security_headers', static::$headers );
		foreach ( $security_headers as $key => $value ) {
			if ( 'Strict-Transport-Security' === $key && ! is_ssl() ) {
				continue;
			}

			$headers[ $key ] = $value;
		}

		return $headers;
	}

	public static function clear_headers() : void {
		header_remove( 'X-Powered-By' );
	}
}
