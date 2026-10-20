<?php

class WLD_Preload_Base {
	protected static array $responsive_images = array();

	public static function init() : void {
		add_action(
			'send_headers',
			array( static::class, 'send_header_links' )
		);
		add_action(
			'wp_head',
			array( static::class, 'the_response_image_links' )
		);
	}

	public static function send_header_links() : void {
		if ( WLD_Is::post_request() || WLD_Is::prefetch_request() || ! WLD_Is::frontend() ) {
			return;
		}

		$images = apply_filters( 'wld_preload_images', array() );
		foreach ( $images as $image ) {
			/**
			 * If I understood the documentation correctly, then there is no point in sending responsive images in the headers.
			 * And it is better to display <link> in head without href.
			 *
			 * @link  https://html.spec.whatwg.org/multipage/semantics.html#early-hints
			 * Only the as, crossorigin, integrity, and type attributes are handled as part of early hint processing.
			 * The other ones, in particular blocking, imagesrcset, imagesizes, and media are only applicable once a Document is created.
			 */
			if ( isset( $image['imagesrcset'], $image['imagesizes'] ) || ( isset( $image['media'] ) && 'all' !== $image['media'] ) ) {
				static::$responsive_images[] = $image;
			} elseif ( isset( $image['src'] ) ) {
				header(
					sprintf(
						'Link: <%s>; rel=preload; as=image; fetchpriority=high;',
						sanitize_url( $image['src'] ),
					),
					false
				);
			}
		}

		$fonts = apply_filters( 'wld_preload_fonts', array() );
		foreach ( $fonts as $font ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=font; type=%s; crossorigin;',
					sanitize_url( $font['src'] ),
					esc_attr( 'font/' . $font['type'] )
				),
				false
			);
		}

		$styles = apply_filters( 'wld_preload_styles', array() );
		foreach ( $styles as $style ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=style;',
					sanitize_url( static::get_src_with_version( $style ) )
				),
				false
			);
		}

		$scripts = apply_filters( 'wld_preload_scripts', array() );
		foreach ( $scripts as $script ) {
			header(
				sprintf(
					'Link: <%s>; rel=preload; as=script; crossorigin=anonymous',
					sanitize_url( static::get_src_with_version( $script ) )
				),
				false
			);
		}
	}

	/** @noinspection HtmlUnknownAttribute, HtmlUnknownTarget */
	public static function the_response_image_links() : void {
		foreach ( static::$responsive_images as $image ) {
			if ( isset( $image['imagesrcset'], $image['imagesizes'] ) ) {
				printf(
					'<link rel="preload" as="image" fetchpriority="high" imagesrcset="%s" imagesizes="%s">',
					esc_attr( $image['imagesrcset'] ),
					esc_attr( $image['imagesizes'] )
				);
			} elseif ( isset( $image['src'], $image['media'] ) ) {
				printf(
					'<link rel="preload" as="image" fetchpriority="high" href="%s" media="%s">',
					esc_url( $image['src'] ),
					esc_attr( $image['media'] )
				);
			}
		}
	}

	public static function set_images( callable|string|array $callback_or_data ) : void {
		static::set_asset( 'images', $callback_or_data );
	}

	public static function set_fonts( callable|string|array $callback_or_data ) : void {
		static::set_asset( 'fonts', $callback_or_data );
	}

	public static function set_styles( callable|string|array $callback_or_data ) : void {
		static::set_asset( 'styles', $callback_or_data );
	}

	public static function set_scripts( callable|string|array $callback_or_data ) : void {
		static::set_asset( 'scripts', $callback_or_data );
	}

	protected static function set_asset( $type, callable|string|array $callback_or_data ) : void {
		$hook_name = 'wld_preload_' . $type;
		if ( is_callable( $callback_or_data ) ) {
			add_filter( $hook_name, $callback_or_data );
		} else {
			add_filter(
				$hook_name,
				static function ( $type_data ) use ( $callback_or_data ) {
					if ( is_array( $callback_or_data ) ) {
						$type_data[] = $callback_or_data;
					} else {
						$type_data[] = array( 'src' => $callback_or_data );
					}

					return $type_data;
				}
			);
		}
	}

	protected static function get_src_with_version( array $data ) : string {
		if ( isset( $data['version'] ) ) {
			return add_query_arg( 'ver', $data['version'], $data['src'] );
		}

		if ( ! str_contains( $data['src'], 'ver=' ) && wp_is_internal_link( $data['src'] ) ) {
			$path = wp_normalize_path( ABSPATH . wp_parse_url( $data['src'], PHP_URL_PATH ) );
			if ( file_exists( $path ) ) {
				$data['src'] = add_query_arg( 'ver', WLD_Enqueue::get_version( $path ), $data['src'] );
			}
		}

		return $data['src'];
	}
}
