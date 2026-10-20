<?php

class WLD_CSP_Base {
	public const REST_NAMESPACE = 'theme/v1';
	public const REST_ROUTE     = 'csp-report/';
	public const DEFAULT        = array(
		'default-src' => array(
			"'self'",
		),
		'connect-src' => array(
			"'self'",
			'*.doubleclick.net',
			'*.google-analytics.com',
			'*.googletagmanager.com',
			'maps.googleapis.com',
			'GOOGLE_TLD',
		),
		'font-src'    => array(
			"'self'",
			'data:',
			'fonts.gstatic.com',
		),
		'frame-src'   => array(
			"'self'",
			'*.doubleclick.net',
			'*.googletagmanager.com',
			'*.vimeo.com',
			'*.youtube.com',
			'www.google.com',
		),
		'img-src'     => array(
			"'self'",
			'data:',
			'*.analytics.google.com',
			'*.doubleclick.net',
			'*.google-analytics.com',
			'*.googlesyndication.com',
			'*.googletagmanager.com',
			'*.gravatar.com',
			'*.gstatic.com',
			'*.ytimg.com',
			'maps.googleapis.com',
			'pixel.wp.com',
			'GOOGLE_TLD',
		),
		'script-src'  => array(
			"'self'",
			"'unsafe-inline'",
			'data:',
			'blob:',
			'*.doubleclick.net',
			'*.google-analytics.com',
			'*.google.com',
			'*.googleadservices.com',
			'*.googletagmanager.com',
			'maps.googleapis.com',
			'stats.wp.com',
			'www.gstatic.com',
		),
		'style-src'   => array(
			"'self'",
			"'unsafe-inline'",
			'fonts.googleapis.com',
			'tagmanager.google.com',
		),
		'media-src'   => array(
			"'self'",
		),
	);
	public const GOOGLE_TLD     = array(
		'*.google.com',
		'*.google.ad',
		'*.google.ae',
		'*.google.com.af',
		'*.google.com.ag',
		'*.google.al',
		'*.google.am',
		'*.google.co.ao',
		'*.google.com.ar',
		'*.google.as',
		'*.google.at',
		'*.google.com.au',
		'*.google.az',
		'*.google.ba',
		'*.google.com.bd',
		'*.google.be',
		'*.google.bf',
		'*.google.bg',
		'*.google.com.bh',
		'*.google.bi',
		'*.google.bj',
		'*.google.com.bn',
		'*.google.com.bo',
		'*.google.com.br',
		'*.google.bs',
		'*.google.bt',
		'*.google.co.bw',
		'*.google.by',
		'*.google.com.bz',
		'*.google.ca',
		'*.google.cd',
		'*.google.cf',
		'*.google.cg',
		'*.google.ch',
		'*.google.ci',
		'*.google.co.ck',
		'*.google.cl',
		'*.google.cm',
		'*.google.cn',
		'*.google.com.co',
		'*.google.co.cr',
		'*.google.com.cu',
		'*.google.cv',
		'*.google.com.cy',
		'*.google.cz',
		'*.google.de',
		'*.google.dj',
		'*.google.dk',
		'*.google.dm',
		'*.google.com.do',
		'*.google.dz',
		'*.google.com.ec',
		'*.google.ee',
		'*.google.com.eg',
		'*.google.es',
		'*.google.com.et',
		'*.google.fi',
		'*.google.com.fj',
		'*.google.fm',
		'*.google.fr',
		'*.google.ga',
		'*.google.ge',
		'*.google.gg',
		'*.google.com.gh',
		'*.google.com.gi',
		'*.google.gl',
		'*.google.gm',
		'*.google.gr',
		'*.google.com.gt',
		'*.google.gy',
		'*.google.com.hk',
		'*.google.hn',
		'*.google.hr',
		'*.google.ht',
		'*.google.hu',
		'*.google.co.id',
		'*.google.ie',
		'*.google.co.il',
		'*.google.im',
		'*.google.co.in',
		'*.google.iq',
		'*.google.is',
		'*.google.it',
		'*.google.je',
		'*.google.com.jm',
		'*.google.jo',
		'*.google.co.jp',
		'*.google.co.ke',
		'*.google.com.kh',
		'*.google.ki',
		'*.google.kg',
		'*.google.co.kr',
		'*.google.com.kw',
		'*.google.kz',
		'*.google.la',
		'*.google.com.lb',
		'*.google.li',
		'*.google.lk',
		'*.google.co.ls',
		'*.google.lt',
		'*.google.lu',
		'*.google.lv',
		'*.google.com.ly',
		'*.google.co.ma',
		'*.google.md',
		'*.google.me',
		'*.google.mg',
		'*.google.mk',
		'*.google.ml',
		'*.google.com.mm',
		'*.google.mn',
		'*.google.com.mt',
		'*.google.mu',
		'*.google.mv',
		'*.google.mw',
		'*.google.com.mx',
		'*.google.com.my',
		'*.google.co.mz',
		'*.google.com.na',
		'*.google.com.ng',
		'*.google.com.ni',
		'*.google.ne',
		'*.google.nl',
		'*.google.no',
		'*.google.com.np',
		'*.google.nr',
		'*.google.nu',
		'*.google.co.nz',
		'*.google.com.om',
		'*.google.com.pa',
		'*.google.com.pe',
		'*.google.com.pg',
		'*.google.com.ph',
		'*.google.com.pk',
		'*.google.pl',
		'*.google.pn',
		'*.google.com.pr',
		'*.google.ps',
		'*.google.pt',
		'*.google.com.py',
		'*.google.com.qa',
		'*.google.ro',
		'*.google.ru',
		'*.google.rw',
		'*.google.com.sa',
		'*.google.com.sb',
		'*.google.sc',
		'*.google.se',
		'*.google.com.sg',
		'*.google.sh',
		'*.google.si',
		'*.google.sk',
		'*.google.com.sl',
		'*.google.sn',
		'*.google.so',
		'*.google.sm',
		'*.google.sr',
		'*.google.st',
		'*.google.com.sv',
		'*.google.td',
		'*.google.tg',
		'*.google.co.th',
		'*.google.com.tj',
		'*.google.tl',
		'*.google.tm',
		'*.google.tn',
		'*.google.to',
		'*.google.com.tr',
		'*.google.tt',
		'*.google.com.tw',
		'*.google.co.tz',
		'*.google.com.ua',
		'*.google.co.ug',
		'*.google.co.uk',
		'*.google.com.uy',
		'*.google.co.uz',
		'*.google.com.vc',
		'*.google.co.ve',
		'*.google.co.vi',
		'*.google.com.vn',
		'*.google.vu',
		'*.google.ws',
		'*.google.rs',
		'*.google.co.za',
		'*.google.co.zm',
		'*.google.co.zw',
		'*.google.cat',
	);

	public const IGNORE_REPORT = array(
		'www.pagespeed-mod.com' => true,
	);

	public const DEV = array(
		'script-src'  => array(
			'ausi.github.io', // For image sizes bookmark.
		),
		'frame-src'   => array(
			'ausi.github.io',
		),
		'connect-src' => array(
			'ausi.github.io',
		),
		'img-src'     => array(
			'ausi.github.io',
		),
	);

	protected static array $rules = array();

	public static function init( array $rules, bool $set_default = true ) : void {
		if ( $set_default ) {
			static::add( static::DEFAULT );
		}

		if ( 'production' !== wp_get_environment_type() ) {
			static::add( static::DEV );
		}

		static::add( $rules );

		add_action(
			'send_headers',
			array( static::class, 'send_headers' )
		);
		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
		add_action(
			'admin_init',
			array( static::class, 'widget_actions' )
		);
		add_action(
			'wp_dashboard_setup',
			array( static::class, 'add_dashboard_widget' )
		);
	}

	public static function add( $rules ) : void {
		foreach ( $rules as $key => $values ) {
			if ( isset( static::$rules[ $key ] ) ) {
				static::$rules[ $key ] = array_merge( static::$rules[ $key ], $values );
			} else {
				static::$rules[ $key ] = $values;
			}
		}
	}

	public static function send_headers() : void {
		if ( ! empty( static::$rules ) ) {
			$rules = '';
			foreach ( static::$rules as $key => $raw_values ) {
				$values = array();
				sort( $raw_values );
				foreach ( $raw_values as $value ) {
					$values[] = $value;
					if ( str_starts_with( $value, '*.' ) ) {
						$values[] = substr( $value, 2 );
					}
				}

				$values = implode( ' ', array_filter( array_unique( $values ) ) );
				if ( $values ) {
					$rules .= sprintf(
						'%s %s;',
						$key,
						$values
					);
				}
			}

			if ( $rules ) {
				$rules = str_replace( 'GOOGLE_TLD', implode( ' ', static::GOOGLE_TLD ), $rules );

				header(
					sprintf(
						'Reporting-Endpoints:csp-endpoint="%s"',
						esc_url_raw( get_rest_url( null, static::REST_NAMESPACE . '/' . static::REST_ROUTE ) )
					),
					false
				);
				header(
					'Content-Security-Policy:' . $rules . ' report-to csp-endpoint',
					false
				);
			}
		}
	}

	public static function registration_rest_routes() : void {
		register_rest_route(
			static::REST_NAMESPACE,
			static::REST_ROUTE,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( static::class, 'save_report' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'type'     => 'array',
					'required' => true,
					'items'    => array(
						'type'       => 'object',
						'properties' => array(
							'body' => array(
								'type'       => 'object',
								'properties' => array(
									'blockedURL'         => array(
										'type'   => 'string',
										'format' => 'uri',
									),
									'documentURL'        => array(
										'type'   => 'string',
										'format' => 'uri',
									),
									'effectiveDirective' => array(
										'type' => 'string',
									),
								),
							),
							'type' => array(
								'type' => 'string',
							),
						),
					),
				),
			)
		);
	}

	public static function save_report( WP_REST_Request $request ) : WP_REST_Response {
		$path    = wp_upload_dir()['basedir'] . '/wld-reports/csp-report.json';
		$log     = WLD_Filesystem::get_file_json_array( $path );
		$reports = $request->get_params();

		foreach ( $reports as $report ) {
			if ( 'csp-violation' === $report['type'] ) {
				$resource = sanitize_url( $report['body']['blockedURL'] );
				if ( isset( self::IGNORE_REPORT[ wp_parse_url( $resource, PHP_URL_HOST ) ] ) ) {
					continue;
				}

				$url       = sanitize_url( $report['body']['documentURL'] );
				$directive = sanitize_key( $report['body']['effectiveDirective'] );

				if ( 'http://eval' === $resource ) {
					$resource = 'unsafe-eval';
				}

				if ( empty( $log[ $directive ] ) ) {
					$log[ $directive ] = array();
				}

				if ( empty( $log[ $directive ][ $resource ] ) ) {
					$log[ $directive ][ $resource ] = array();
				}

				$log[ $directive ][ $resource ][ $url ] = wp_date( 'Y-m-d' );
			}
		}

		WLD_Filesystem::put_file_contents( $path, wp_json_encode( $log ) );

		return new WP_REST_Response( 'Saved' );
	}

	public static function widget_actions() : void {
		if ( isset( $_GET['_wld_csp_nonce'] ) && wp_verify_nonce( $_GET['_wld_csp_nonce'], 'wld-csp-clear' ) ) {
			$upload = wp_upload_dir();
			$path   = $upload['basedir'] . '/wld-reports/csp-report.json';
			WLD_Filesystem::delete_file_or_folder( $path );
			wp_safe_redirect( get_dashboard_url() );
			exit();
		}
	}

	public static function add_dashboard_widget() : void {
		wp_add_dashboard_widget(
			'wld_csp_issues',
			__( 'Content Security Policy', 'theme' ),
			static function () {
				$upload    = wp_upload_dir();
				$path      = $upload['basedir'] . '/wld-reports/csp-report.json';
				$log       = WLD_Filesystem::get_file_json_array( $path );
				$clear_url = wp_nonce_url( get_dashboard_url(), 'wld-csp-clear', '_wld_csp_nonce' );

				if ( empty( $log ) ) {
					echo '<p>' . esc_html__( 'Issues not found', 'theme' ) . '</p>';
				} else {
					echo '<h3 style="font-weight:bold;color:red">';
					echo esc_html__( 'Issues found', 'theme' );
					echo '</h3>';
					foreach ( $log as $directive => $resources ) {
						echo '<h4 style="margin:0">' . esc_html( $directive ) . ':</h4>';
						echo '<ul style="margin:0;padding-left:26px">';
						foreach ( $resources as $resource => $_url ) {
							echo '<li style="list-style:initial;white-space:nowrap">';
							echo '<span style="overflow:hidden;text-overflow:ellipsis;display:block">';
							echo esc_html( $resource );
							echo '</span>';
							echo '</li>';
						}
						echo '</ul>';
					}
					$url = $upload['baseurl'] . '/wld-reports/csp-report.json';
					echo '<p style="text-align: right">';
					echo '<a href="' . esc_url( $clear_url ) . '" style="color:red">';
					echo esc_html__( 'Clear log', 'theme' );
					echo '</a>';
					echo ' ';
					echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">';
					echo esc_html__( 'Show full log', 'theme' );
					echo '</a>';
					echo '</p>';
				}
			}
		);
	}
}
