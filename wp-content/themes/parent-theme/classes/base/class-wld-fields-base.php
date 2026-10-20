<?php

class WLD_Fields_Base {
	protected static array $loop = array();
	protected static array $wrap = array();

	protected static string $as_type               = '';
	protected static string $current_loop_selector = '';

	public static bool $force_sub_loop = false;

	public static function get( string $selector, array $raw_args ) : string {
		$value = '';
		$wrap  = '';
		$attrs = array();
		$args  = array(
			'size'      => '',
			'class'     => '',
			'file_type' => '',
		);

		$acf_post_id = null;
		foreach ( $raw_args as $arg ) {
			if ( is_string( $arg ) ) {
				if ( str_contains( $arg, '<' ) ) {
					$wrap = $arg;
				} elseif ( 'full' === $arg || preg_match( '/^\d+x\d+$/', $arg ) || has_image_size( $arg ) ) {
					$args['size'] = $arg;
				} elseif ( 'svg' === $arg ) {
					$args['file_type'] = $arg;
				} else {
					$args['class'] = $arg;
				}
			} elseif ( is_array( $arg ) ) {
				$attrs = $arg;
			} elseif ( static::is_acf_post_id( $arg ) ) {
				$acf_post_id = $arg;
			}
		}

		$field  = static::get_field( $selector, $acf_post_id );
		$values = static::get_values( $selector, $field['type'] ?? '', $raw_args, $acf_post_id );

		if ( $field && $values['format'] ) {
			if ( static::$as_type ) {
				$field['type']   = static::$as_type;
				static::$as_type = '';
			}

			switch ( $field['type'] ) {
				case 'title':
					$value = apply_shortcodes( $values['format'] );
					if ( $args['class'] ) {
						$value = preg_replace(
							'/(<h\d)/',
							'$1 class="' . esc_attr( $args['class'] ) . '"',
							$value
						);
					}

					$value = wp_kses_post( $value );
					break;
				case 'text':
				case 'textarea':
				case 'wysiwyg':
				case 'replace_text':
					$value = wp_kses_post( $values['format'] );
					break;
				case 'image':
					if ( ! empty( $args['class'] ) ) {
						$attrs['class'] = $args['class'];
					}

					if ( 'svg' === $args['file_type'] ) {
						$value = wp_kses(
							WLD_SVG::get_svg( $values['raw'], $attrs['class'] ?? '' ),
							WLD_KSES::get_by_svg()
						);
					} else {
						$value = wp_kses(
							WLD_Images::get_img( (int) $values['raw'], $args['size'], $attrs ),
							WLD_KSES::get_by_tag( 'img' )
						);
					}
					break;
				case 'background':
					$fit = 'cover';
					if ( 'cover' === $args['class'] || 'contain' === $args['class'] ) {
						$fit = $args['class'];
					}

					if ( empty( $attrs['class'] ) ) {
						$attrs['class'] = 'object-fit object-fit-' . $fit;
					} else {
						$classes = explode( ' ', $attrs['class'] );
						if ( ! in_array( 'object-fit', $classes, true ) ) {
							$classes[] = 'object-fit';
						}
						if (
							! in_array( 'object-fit-cover', $classes, true ) &&
							! in_array( 'object-fit-contain', $classes, true )
						) {
							$classes[] = 'object-fit-' . $fit;
						}

						$attrs['class'] = implode( ' ', $classes );
					}

					$value = wp_kses(
						WLD_Images::get_img(
							(int) $values['raw'],
							$args['size'] ? : '1920x0',
							$attrs
						),
						WLD_KSES::get_by_tag( 'img' )
					);
					break;
				case 'link':
					$class = $args['class'];
					if ( isset( $raw_args[1] ) && is_bool( $raw_args[1] ) ) {
						$empty = $raw_args[1];
					} else {
						$empty = false;
					}

					if ( is_array( $values['raw'] ) ) {
						$value = wp_kses(
							static::wld_get_link_html_from_array( $values['raw'], $class, $empty ),
							WLD_KSES::get_by_tag( 'a' )
						);
					}
					break;
				case 'wld_contact_link':
					$class = $args['class'];
					$title = $values['raw']['title'];

					switch ( $values['raw']['link_type'] ) {
						case 'phone':
							$attrs['href'] = 'tel:' . $values['raw']['number'];
							break;
						case 'fax':
							$attrs['href'] = 'fax:' . $values['raw']['number'];
							break;
						case 'email':
							$attrs['href'] = 'mailto:' . antispambot( $values['raw']['number'] );
							$title         = antispambot( $title );
							break;
					}

					$attrs['class'] = trim( $values['raw']['class'] . ' ' . $class );

					$value = wp_kses(
						'<a ' . WLD_KSES::get_attrs( $attrs ) . '>' . $title . '</a>',
						WLD_KSES::get_by_tag( 'a' )
					);
					break;
				case 'wld_social_links':
					$items = $values['raw']['items'] ?? $values['raw'];

					if ( $items && is_array( $items ) ) {
						$links = '';
						foreach ( $items as $item ) {
							/** @noinspection HtmlUnknownTarget */
							$links .= sprintf(
								'
									<a href="%s" class="social-links__link" target="_blank" rel="noopener">
										%s
										<span class="social-links__text">%s</span>
									</a>
								',
								esc_url( trim( $item['url'] ) ),
								WLD_Images::get_img(
									$item['icon']['ID'] ?? 0,
									$args['size'] ? : 'thumbnail',
									array( 'class' => 'social-links__image' )
								),
								$item['text'] ?? ''
							);
						}

						$value = sprintf(
							'<div class="%s">%s</div>',
							esc_attr( trim( 'social-links ' . $field['class'] ) ),
							$links
						);
					}
					break;
				case 'forms':
					if ( $values['raw'] ) {
						$title = $attrs['title'] ?? false;
						$desc  = $attrs['desc'] ?? false;
						$ajax  = $attrs['ajax'] ?? true;
						$value = sprintf(
							'[gravityform id="%d" title="%s" description="%s" ajax="%s" block-name="%s"]',
							$values['raw'],
							$title ? 'true' : 'false',
							$desc ? 'true' : 'false',
							$ajax ? 'true' : 'false',
							$args['class']
						);
					}
					break;
				case 'menu':
					$attrs         = array_merge(
						array( 'fallback_cb' => '__return_empty_string' ),
						$attrs
					);
					$attrs['menu'] = (int) $values['raw'];
					$attrs['echo'] = false;

					// todo: Escape output.
					$value = wp_nav_menu( $attrs );
					break;
				case 'google_map':
					$icon                    = $raw_args[0] ?? '';
					$attrs                   = array_merge(
						array(
							'class'    => 'map',
							'data-map' => 'true',
						),
						$attrs
					);
					$attrs['data-latitude']  = $values['format']['lat'];
					$attrs['data-longitude'] = $values['format']['lng'];
					$attrs['data-zoom']      = $values['format']['zoom'];
					$attrs['data-address']   = $values['format'];
					$attrs['data-icon']      = $icon ? WLD_File::get_assets_url( 'images/' . $icon ) : '';

					unset(
						$attrs['data-address']['lat'],
						$attrs['data-address']['lng'],
						$attrs['data-address']['zoom']
					);

					$value = '<div ' . WLD_KSES::get_attrs( $attrs ) . '"></div>';
					break;
				case 'radio':
				case 'checkbox':
					$value = $values['raw'];
					if ( is_array( $value ) ) {
						$value = implode( ' ', array_map( 'sanitize_html_class', $value ) );
					} else {
						$value = sanitize_html_class( $value );
					}
					break;
				case 'group':
					if ( $attrs ) {
						$is_picture = false;
						foreach ( $attrs as $maybe_picture_data ) {
							if ( is_array( $maybe_picture_data ) ) {
								foreach ( $maybe_picture_data as $maybe_picture_data_key => $maybe_picture_data_value ) {
									if ( 'media' === $maybe_picture_data_key ) {
										$is_picture = true;
										break 2;
									}
								}
							} else {
								break;
							}
						}

						if ( $is_picture ) {
							$data = array();
							while ( static::loop( $selector ) ) {
								foreach ( $attrs as $picture_selector => $picture_data ) {
									$image_id = static::get_values( $picture_selector, 'picture', $raw_args )['raw'];
									$data[]   = array_merge( array( 'id' => (int) $image_id ), $picture_data );
								}
							}

							$value = wp_kses(
								WLD_Images::get_picture( $data ),
								WLD_KSES::get_by_picture()
							);
						}
					}
					break;
				default:
					$value = wp_kses_post( $values['format'] );
			}
		}

		$value = (string) $value;
		if ( $value ) {
			$value = static::get_start_wrapper( $wrap ) . apply_shortcodes( $value ) . static::get_end_wrapper( $wrap );
		}

		return $value;
	}

	public static function get_value( $key = '' ) : string {
		$value = '';
		if ( static::$current_loop_selector ) {
			$value = static::$loop[ static::$current_loop_selector ]['value'] ?? array();
			if ( $key && isset( $value[ $key ] ) ) {
				$value = $value[ $key ];
			}
		}

		return esc_html( is_array( $value ) ? wp_json_encode( $value ) : $value );
	}

	public static function loop( &$selector_or_posts_array, string $wrapper = '', WP_Post|WP_Term|WP_User|WP_Comment|false|null $acf_post_id = null ) : bool {
		$selector = 'array';
		if ( is_string( $selector_or_posts_array ) ) {
			$selector = $selector_or_posts_array;
		}

		$start     = ! isset( static::$loop[ $selector ] );
		$end       = true;
		$has_value = true;

		static::$current_loop_selector = $selector;

		if ( $start ) {
			if ( is_string( $selector_or_posts_array ) ) {
				static::$loop[ $selector ] = static::get_field( $selector, $acf_post_id );
			} else {
				static::$loop[ $selector ] = array(
					'type'  => 'relationship',
					'value' => &$selector_or_posts_array,
				);
			}

			static::$loop[ $selector ]['index'] = 0;

			if ( 'array' === $selector ) {
				$has_value = ! empty( array_filter( static::$loop[ $selector ]['value'] ) );
			} else {
				$has_value = static::has( array( $selector, $acf_post_id ) );
			}

			if ( $has_value ) {
				static::$loop[ $selector ]['need_end_wrapper'] = (bool) $wrapper;

				echo wp_kses_post( static::get_start_wrapper( $wrapper ) );
			}
		}

		if ( $has_value ) {
			++ static::$loop[ $selector ]['index'];
			$post_id = static::get_post_id( $selector );
			if ( 'relationship' === static::$loop[ $selector ]['type'] ) {
				if ( ! empty( static::$loop[ $selector ]['value'] ) ) {
					// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$GLOBALS['post'] = get_post( array_shift( static::$loop[ $selector ]['value'] ) );
					setup_postdata( $GLOBALS['post'] );
					$end = false;
				} elseif ( isset( static::$loop[ $selector ] ) ) {
					wp_reset_postdata();
				}
			} elseif ( have_rows( $selector, $post_id ) ) {
				the_row();
				$end = false;
			}
		}

		if ( $end ) {
			if ( ! empty( static::$loop[ $selector ]['need_end_wrapper'] ) ) {
				echo wp_kses_post( static::get_end_wrapper( $wrapper ) );
			}
			unset( static::$loop[ $selector ] );
			static::$current_loop_selector = '';
		}

		return ! $end;
	}

	public static function get_loop_index() : int {
		return static::$loop[ static::$current_loop_selector ]['index'];
	}

	public static function wrap(
		string $selector_or_url,
		string $class_name = '',
		bool $force = true,
		array $attrs = array(),
		WP_Post|WP_Term|WP_User|WP_Comment|false|null $acf_post_id = null
	) : bool {
		if ( ! isset( static::$wrap[ $selector_or_url ] ) ) {
			if ( static::is_url( $selector_or_url ) ) {
				$values = array();
				$field  = array();
				$value  = $selector_or_url;
			} else {
				$values = static::get_values( $selector_or_url, acf_post_id: $acf_post_id );
				$field  = static::get_field( $selector_or_url, acf_post_id: $acf_post_id );
				$value  = $values['format'];
			}

			static::$wrap[ $selector_or_url ] = '';

			if ( is_string( $value ) && static::is_url( $value ) ) {
				static::$wrap[ $selector_or_url ] = $value;

				$attrs['href']  = esc_url( $value );
				$attrs['class'] = $class_name;

				echo '<a ' . WLD_KSES::get_attrs( $attrs ) . '>';
			} elseif ( $value && ( 'link' === $field['type'] || 'wld_contact_link' === $field['type'] ) ) {
				static::$wrap[ $selector_or_url ] = $value;

				if ( 'link' === $field['type'] ) {
					$url = $values['raw']['url'];
				} else {
					$url = '';
					switch ( $values['raw']['link_type'] ) {
						case 'phone':
							$url = 'tel:' . $values['raw']['number'];
							break;
						case 'fax':
							$url = 'fax:' . $values['raw']['number'];
							break;
						case 'email':
							$url = 'mailto:' . antispambot( $values['raw']['number'] );
							break;
					}
					$values['raw']['title'] = false;
				}

				$attrs['href']   = esc_url( $url );
				$attrs['target'] = $values['raw']['target'] ?? false;
				$attrs['class']  = trim( $values['raw']['class'] . ' ' . $class_name );

				if ( isset( $values['raw']['target'] ) && '_blank' === $values['raw']['target'] ) {
					$attrs['rel'] = 'noopener';
				}

				echo '<a ' . WLD_KSES::get_attrs( $attrs ) . '>';
			} elseif ( $value || $force ) {
				$attrs['class'] = $class_name;

				echo '<div ' . WLD_KSES::get_attrs( $attrs ) . '>';
			}

			return true;
		}

		if ( ! empty( static::$wrap[ $selector_or_url ] ) ) {
			echo '</a>';
		} elseif ( $force ) {
			echo '</div>';
		}

		unset( static::$wrap[ $selector_or_url ] );

		return false;
	}

	/** @noinspection MultipleReturnStatementsInspection */
	public static function has( array $selectors_and_maybe_conditional ) : bool {
		$conditional = 'OR';
		$acf_post_id = null;
		if ( 'AND' === $selectors_and_maybe_conditional[0] ) {
			$conditional = 'AND';
			array_shift( $selectors_and_maybe_conditional );
		}

		$selectors_and_maybe_conditional = array_filter( $selectors_and_maybe_conditional );
		if ( count( $selectors_and_maybe_conditional ) > 1 ) {
			$acf_post_id = array_pop( $selectors_and_maybe_conditional );
			if ( ! static::is_acf_post_id( $acf_post_id ) ) {
				$selectors_and_maybe_conditional[] = $acf_post_id;
				$acf_post_id                       = null;
			}
		}

		if ( 'OR' === $conditional ) {
			foreach ( $selectors_and_maybe_conditional as $selector ) {
				$values = static::get_values( $selector, acf_post_id: $acf_post_id );

				if (
					( ! is_array( $values['format'] ) && ! empty( $values['format'] ) ) ||
					( is_array( $values['format'] ) && ! empty( array_filter( $values['format'] ) ) )
				) {
					return true;
				}
			}

			return false;
		}

		foreach ( $selectors_and_maybe_conditional as $selector ) {
			$values = static::get_values( $selector, acf_post_id: $acf_post_id );
			if ( empty( $values['format'] ) ) {
				return false;
			}
		}

		return true;
	}

	public static function get_field( string $selector, WP_Post|WP_Term|WP_User|WP_Comment|false|null $acf_post_id = null ) : array {
		$post_id = $acf_post_id;
		if ( ! $post_id ) {
			$post_id = static::get_post_id( $selector );
		}
		if ( false === $post_id && static::is_sub_loop() ) {
			$field = get_sub_field_object( $selector, false );
		} else {
			$field = get_field_object( $selector, $post_id, false );
		}

		return $field ? : array();
	}

	public static function next_field_process_as_type( string $as_type ) : void {
		static::$as_type = $as_type;
	}

	protected static function is_sub_loop() : bool {
		$loop    = acf_get_loop();
		$is_loop = false;
		if ( ! empty( $loop ) ) {
			$type    = $loop['field']['type'] ?? '';
			$post_id = $loop['post_id'] ?? '';
			$types   = array( 'group', 'repeater', 'clone', 'flexible_content' );

			if ( str_starts_with( $post_id, 'options' ) && in_array( $type, $types, true ) ) {
				$is_loop = true;
			} else {
				$is_loop = WLD_Theme::get_post_id_taking_into_preview() === $post_id;
			}
		}

		return $is_loop;
	}

	protected static function get_values( string $selector, string $type = '', array $args = array(), WP_Post|WP_Term|WP_User|WP_Comment|false|null $acf_post_id = null ) : array {
		$post_id = $acf_post_id;
		if ( ! $post_id ) {
			$post_id = static::get_post_id( $selector );
		}

		if ( $type ) {
			do_action( 'wld_get_field_value_before_' . $type, $args );
		}
		if ( false === $post_id && static::is_sub_loop() ) {
			$values = array(
				'raw'    => get_sub_field( $selector, false ),
				'format' => get_sub_field( $selector ),
			);
		} else {
			$values = array(
				'raw'    => get_field( $selector, $post_id, false ),
				'format' => get_field( $selector, $post_id ),
			);
		}
		if ( $type ) {
			do_action( 'wld_get_field_value_before_' . $type, $args );
		}

		return $values;
	}

	protected static function get_start_wrapper( string $wrapper ) : string {
		$start_wrap = '';
		if ( $wrapper ) {
			if ( str_contains( $wrapper, '%s' ) ) {
				$start_wrap = explode( '%s', $wrapper )[0];
			} elseif ( preg_match( '/^<([^>\s]+)[^>]*>$/', $wrapper ) ) {
				$start_wrap = $wrapper;
			}
		}

		return $start_wrap;
	}

	protected static function get_end_wrapper( string $wrapper ) : string {
		$end_wrapper = '';
		if ( $wrapper ) {
			if ( str_contains( $wrapper, '%s' ) ) {
				$end_wrapper = explode( '%s', $wrapper )[1];
			} elseif ( preg_match( '/^<([^>\s]+)[^>]*>$/', $wrapper, $matches ) ) {
				$end_wrapper = '</' . $matches[1] . '>';
			}
		}

		return $end_wrapper;
	}

	protected static function is_url( ?string $url ) : bool {
		if ( $url ) {
			return false !== filter_var( $url, FILTER_VALIDATE_URL ) || 0 === strncmp( $url, '#', 1 );
		}

		return false;
	}

	protected static function get_post_id( string $selector ) : bool|string {
		return 0 === strncmp( $selector, 'wld_', 4 ) ? 'options' : false;
	}

	protected static function wld_get_link_html_from_array( array $link_array, string $class_name = '', bool $is_empty = false ) : string {
		$link_array = array_filter( $link_array );
		if ( empty( $link_array ) ) {
			return '';
		}
		if ( ! isset( $link_array['class'] ) ) {
			$link_array['class'] = '';
		}
		if ( ! isset( $link_array['title'] ) ) {
			$link_array['title'] = '';
		}
		if ( $class_name ) {
			$link_array['class'] = trim( $link_array['class'] . ' ' . $class_name );
		}
		$atts = '';
		foreach ( $link_array as $k => $v ) {
			if ( 'title' === $k && ! $is_empty ) {
				continue;
			}
			if ( 'url' === $k ) {
				$k = 'href';
			}
			if ( is_string( $v ) ) {
				$v = trim( $v );
			} elseif ( is_bool( $v ) ) {
				$v = $v ? 1 : 0;
			} else {
				$v = false;
			}
			if ( $v ) {
				$atts .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			}
		}
		if ( $is_empty ) {
			$link_array['title'] = '';
		}

		if ( $link_array['title'] ) {
			$link_array['title'] = do_shortcode( $link_array['title'] );
		}

		if ( '_blank' === ( $link_array['target'] ?? '' ) && empty( $link_array['rel'] ) ) {
			$atts .= ' rel="noopener"';
		}

		return '<a' . $atts . '>' . $link_array['title'] . '</a> ';
	}

	public static function get_template_part( string $path, array $args = array(), WP_Post|WP_Term|WP_User|WP_Comment|false|null $post_id = null ) : string {
		global $wp_query;

		$initial_sub_loop       = static::$force_sub_loop;
		static::$force_sub_loop = false === $post_id;

		$initial_query_vars   = $wp_query->query_vars;
		$wp_query->query_vars = array_merge( $wp_query->query_vars, $args );

		ob_start();
		get_template_part( $path, null, $args );
		$content = ob_get_clean();

		static::$force_sub_loop = $initial_sub_loop;
		$wp_query->query_vars   = $initial_query_vars;

		return $content;
	}

	protected static function is_acf_post_id( $acf_post_id ) : bool {
		return $acf_post_id instanceof WP_Post || $acf_post_id instanceof WP_Term || $acf_post_id instanceof WP_User || $acf_post_id instanceof WP_Comment;
	}
}
