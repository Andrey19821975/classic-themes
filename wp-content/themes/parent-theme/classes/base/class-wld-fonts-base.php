<?php
/**
 * @noinspection HtmlUnknownTarget
 * phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
 */

class WLD_Fonts_Base {
	protected const META_KEY       = '_theme_preload_fonts';
	protected const REST_NAMESPACE = 'theme/v1';
	protected const REST_ROUTE     = 'fonts-preloader/';

	public static function init() : void {
		$type = apply_filters( 'wld_fonts_type', 'js' );
		if ( 'css' === $type ) {
			WLD_Preload::set_scripts(
				array(
					'src'     => static::get_url( 'fonts-classes.js' ),
					'version' => false,
				)
			);
			add_action(
				'wp_head',
				array( static::class, 'add_fonts_css' ),
				2
			);
		} else {
			WLD_Preload::set_scripts(
				array(
					'src'     => static::get_url( 'fonts-all.js' ),
					'version' => false,
				)
			);
			add_action(
				'wp_head',
				array( static::class, 'add_fonts_js' ),
				2
			);
		}

		WLD_Preload::set_fonts( array( static::class, 'set_preload_fonts' ) );

		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
	}

	public static function add_fonts_js() : void {
		$font_dir   = WLD_File::get_assets_path( 'fonts/' );
		$js_content = WLD_Filesystem::get_file_contents( $font_dir . 'fonts.js' );
		if ( $js_content ) {
			printf(
				'<script src="%s" id="theme-fonts-all-js" type="module" async></script>',
				esc_url( static::get_url( 'fonts-all.js' ) )
			);
			static::the_save_preload_script();
		}
	}

	public static function add_fonts_css() : void {
		$font_dir    = WLD_File::get_assets_path( 'fonts/' );
		$css_content = WLD_Filesystem::get_file_contents( $font_dir . 'fonts.css' );
		if ( $css_content ) {
			$font_url = static::get_url();
			static::the_classes_script();
			printf(
				'<style id="theme-fonts-css">%s</style>',
				// todo: We need to figure out how to escape the CSS output. It seems to be sufficient to make sure it doesn't include a closing tag, and perhaps all external URLs should be replaced.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				str_replace(
					'url(',
					'url(' . esc_url( $font_url ),
					$css_content
				)
			);
			static::the_save_preload_script();
		}
	}

	public static function set_preload_fonts( array $fonts ) : array {
		// todo: It’s not entirely clear why we process the URL every time, instead of saving the URL right away.
		$preload_fonts = get_post_meta( get_the_ID(), static::META_KEY, true );
		if ( $preload_fonts ) {
			$fonts = array_merge(
				$fonts,
				array_map(
					static fn( $font ) => array(
						'src'  => static::get_url( $font['src'] ),
						'type' => $font['type'],
					),
					$preload_fonts
				)
			);
		}

		return $fonts;
	}

	public static function save_preload_fonts( WP_REST_Request $request ) : WP_REST_Response {
		$fonts      = $request->get_param( 'fonts' );
		$font_names = array();
		foreach ( $request['fonts'] as $index => $font ) {
			if ( isset( $font_names[ $font['src'] ] ) ) {
				unset( $fonts[ $index ] );
			}

			$font_names[ $font['src'] ] = true;
		}

		update_post_meta( $request['post_id'], static::META_KEY, $fonts );

		return new WP_REST_Response( 'Saved' );
	}

	public static function registration_rest_routes() : void {
		register_rest_route(
			static::REST_NAMESPACE,
			static::REST_ROUTE . '(?P<post_id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => array( static::class, 'save_preload_fonts' ),
				'permission_callback' => array( static::class, 'permission_callback' ),
				'args'                => array(
					'fonts' => array(
						'type'     => 'array',
						'items'    => array(
							'type'       => 'object',
							'properties' => array(
								'src'  => array(
									'type'    => 'string',
									'pattern' => '^[a-zA-Z0-9-_]+.(eot|svg|ttf|woff2?)$',
								),
								'type' => array(
									'type' => 'string',
									'enum' => array(
										'eot',
										'svg',
										'opentype',
										'truetype',
										'woff',
										'woff2',
									),
								),
							),
						),
						'required' => true,
					),
				),
			)
		);
	}

	protected static function get_url( string $file_name = '' ) : string {
		static $font_url;
		if ( null === $font_url ) {
			$font_url = WLD_File::get_assets_url( 'fonts/' );
		}

		return $font_url . $file_name;
	}

	protected static function the_classes_script() : void {
		printf(
			'<script src="%s" id="theme-fonts-classes-js" type="module" async></script>',
			esc_url( static::get_url( 'fonts-classes.js' ) )
		);
	}

	protected static function the_save_preload_script() : void {
		if ( current_user_can( 'edit_post', get_the_ID() ) ) {
			printf(
				'<script src="%s" data-fonts-preloader-fetch-url="%s" id="theme-fonts-preloader-main-js" type="module" async></script>',
				esc_url( static::get_url( 'fonts-preloader-main.js' ) ),
				esc_url(
					wp_nonce_url(
						get_rest_url(
							null,
							static::REST_NAMESPACE . '/' . static::REST_ROUTE . get_the_ID()
						),
						'wp_rest'
					)
				)
			);
		}
	}

	public static function permission_callback( WP_REST_Request $request ) : bool {
		return current_user_can( 'edit_post', $request->get_param( 'post_id' ) );
	}
}
