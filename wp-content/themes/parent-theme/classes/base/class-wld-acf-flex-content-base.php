<?php

class WLD_ACF_Flex_Content_Base {
	public static string|false $id = false;

	public static array $blocks = array();

	protected static int $index = 0;

	public static function init() : void {
		add_filter( 'acf/load_field/type=flexible_content', array( static::class, 'set_layouts' ) );
		add_filter( 'body_class', array( static::class, 'body_class' ) );
		add_filter( 'acf/get_field_group', array( static::class, 'change_title' ) );
	}

	public static function set_layouts( array $field ) : array {
		$acf_tools_page = 'acf-tools' === sanitize_text_field( $_GET['page'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
		$acf_group_page = 'acf-field-group' === get_post_type();
		$display        = apply_filters( 'wld_flex_display', 'row' );

		if ( $acf_tools_page || $acf_group_page ) {
			return $field;
		}
		if ( isset( $field['wrapper']['id'] ) && 'tpl_flexible_content_content' === $field['wrapper']['id'] ) {
			$groups  = acf_get_field_groups();
			$layouts = array();
			if ( $groups ) {
				remove_filter( 'acf/load_field/type=flexible_content', array( static::class, __METHOD__ ) );
				foreach ( $groups as $group ) {
					if ( 0 === strncmp( $group['title'], 'FC:', 3 ) ) {
						if ( 'FC: Faq' === $group['title'] && ! post_type_exists( 'faq' ) ) {
							continue;
						}
						$fields = acf_get_fields( $group );
						if ( $fields ) {
							$key   = (string) str_replace( 'group_', '', $group['key'] );
							$title = str_replace( 'FC: ', '', $group['title'] );

							$layouts[ $key ] = array(
								'key'                 => $key,
								'name'                => sanitize_title( $title ),
								'label'               => $title,
								'display'             => $display,
								'sub_fields'          => $fields,
								'min'                 => '',
								'max'                 => '',
								'wpml_cf_preferences' => 2,
							);
						}
					}
				}
				add_filter( 'acf/load_field/type=flexible_content', array( static::class, __METHOD__ ) );
			}
			$field['layouts'] = static::set_inner_layouts( $layouts );
		}

		return $field;
	}

	public static function set_inner_layouts( array $layouts ) : array {
		if ( empty( $layouts ) ) {
			return $layouts;
		}
		$inner_layouts = $layouts;
		foreach ( $layouts as $l => $layout ) {
			foreach ( (array) $layout['sub_fields'] as $k => $_field ) {
				$type = $_field['type'];
				$name = $_field['name'];
				if ( 'flexible_content' === $type && 'inner_content' === $name ) {
					unset( $inner_layouts[ $l ] );
				} elseif ( 'repeater' === $type ) {
					foreach ( (array) $_field['sub_fields'] as $k2 => $_field2 ) {
						$type = $_field2['type'];
						$name = $_field2['name'];
						if ( 'flexible_content' === $type && 'inner_content' === $name ) {
							unset( $inner_layouts[ $l ]['sub_fields'][ $k ]['sub_fields'][ $k2 ] );
						}
					}
				}
			}
		}
		foreach ( $layouts as $l => $layout ) {
			foreach ( (array) $layout['sub_fields'] as $k => $_field ) {
				$type = $_field['type'];
				$name = $_field['name'];
				if ( 'flexible_content' === $type && 'inner_content' === $name ) {
					$layouts[ $l ]['sub_fields'][ $k ]['layouts'] = $inner_layouts;
				} elseif ( 'repeater' === $type ) {
					foreach ( (array) $_field['sub_fields'] as $k2 => $_field2 ) {
						$type = $_field2['type'];
						$name = $_field2['name'];
						if ( 'flexible_content' === $type && 'inner_content' === $name ) {
							$layouts[ $l ]['sub_fields'][ $k ]['sub_fields'][ $k2 ]['layouts'] = $inner_layouts;
						}
					}
				}
			}
		}

		return $layouts;
	}

	public static function body_class( array $classes ) : array {
		$special_classes = get_field( 'css_special_classes' );
		if ( $special_classes ) {
			foreach ( (array) $special_classes as $class ) {
				$classes[] = sanitize_html_class( $class );
			}
		}

		return apply_filters( 'wld_flex_body_class', $classes );
	}

	public static function the_loop( string $selector, $post_id = false, string $template_part_path = '' ) : void {
		if ( have_rows( $selector, $post_id ) ) {
			while ( have_rows( $selector, $post_id ) ) {
				the_row();
				WLD_ACF_Flex_Content::the_content( $template_part_path );
			}
		}
	}

	public static function the_content( string $template_part_path = '', string $layout = '' ) : void {
		if ( empty( $template_part_path ) ) {
			$template_part_path = 'template-parts/flexible-content/';
		}

		if ( empty( $layout ) ) {
			$layout = (string) get_row_layout();
		}

		$loop     = acf_get_loop();
		$loop_key = $loop['key'];
		if ( ! isset( static::$blocks[ $loop_key ] ) ) {
			static::$blocks[ $loop_key ] = $loop['value'];
		}

		++ static::$index;

		if ( 1 === static::$index ) {
			add_filter(
				'wp_get_attachment_image_attributes',
				array( static::class, 'set_loading_eager' )
			);
		}

		WLD_BAM_Content::set_block_name( $layout );
		$content = wld_get_template_part( $template_part_path . $layout );
		WLD_BAM_Content::remove_block_name();

		if ( 1 === static::$index ) {
			remove_filter(
				'wp_get_attachment_image_attributes',
				array( static::class, 'set_loading_eager' )
			);
		}

		// Set id attr
		if ( static::$id ) {
			$id         = ' id="' . static::$id . '"';
			static::$id = '';
		} elseif ( false !== static::$id ) {
			$id = ' id="section-' . static::get_index() . '"';
		} else {
			$id = false;
		}

		if ( $id && 1 !== preg_match( '/^<.+ id=".+".?>/', $content ) ) {
			$content = implode( $id . '>', explode( '>', $content, 2 ) );
		}

		$content = implode( ' data-visibility="' . $layout . '">', explode( '>', $content, 2 ) );

		$next    = static::$blocks[ $loop_key ][ static::get_index() ] ?? array();
		$classes = apply_filters( 'wld_flex_block_class', array(), $next['acf_fc_layout'] ?? '' );
		if ( $classes ) {
			$content = preg_replace(
				'|^(<[^>]+class=")|',
				'$1' . implode( ' ', $classes ) . ' ',
				$content
			);
		}

		// todo: think about how we can escape output... although it's probably not possible :(
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
	}

	public static function get_index() : int {
		return static::$index;
	}

	public static function change_title( array $field_group ) : array {
		$post_type       = sanitize_text_field( $_GET['post_type'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
		$page            = sanitize_text_field( $_GET['page'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
		$action          = sanitize_text_field( $_POST['action'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
		$posts_files     = array( '/wp-admin/post-new.php', '/wp-admin/edit.php' );
		$if_post         = in_array( $_SERVER['PHP_SELF'], $posts_files, true );
		$if_get_tpl      = 'acf/ajax/check_screen' === $action;
		$if_settings     = 'theme-settings' === $page;
		$if_post_not_acf = 'acf-field-group' !== $post_type && $if_post;

		if ( $if_get_tpl || $if_settings || $if_post_not_acf ) {
			$field_group['title'] = str_replace(
				array(
					'TPL: ',
					'CPT: ',
					'OPT: ',
				),
				'',
				$field_group['title']
			);
		}

		return $field_group;
	}

	public static function set_loading_eager( array $attr ) : array {
		if ( empty( $attr['loading'] ) ) {
			$attr['loading'] = 'eager';
		}
		if ( empty( $attr['fetchpriority'] ) ) {
			$attr['fetchpriority'] = 'high';
		}

		return $attr;
	}
}
