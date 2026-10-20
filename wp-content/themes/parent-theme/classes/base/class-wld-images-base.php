<?php

class WLD_Images_Base {
	public static array $sizes     = array();
	public static array $endpoints = array(
		320,
		360,
		375,
		414,
		540,
		562,
		585,
		585,
		600,
		621,
		640,
		720,
		768,
		900,
		1024,
		1280,
		1334,
		1440,
		1536,
		1600,
		1920,
		2048,
	);

	protected static string $current_size = '';

	public static function init() : void {
		add_action(
			'after_setup_theme',
			array( static::class, 'register' ),
			11
		);
		add_filter(
			'image_size_names_choose',
			array( static::class, 'add_sizes_in_choose' )
		);
		add_filter(
			'image_resize_dimensions',
			array( static::class, 'add_crop_dimensions' ),
			10,
			6
		);
		add_filter(
			'the_content',
			array( static::class, 'remove_image_wrap_p' ),
			99
		);
		add_filter(
			'intermediate_image_sizes_advanced',
			array( static::class, 'recovery_crop' )
		);
		add_filter(
			'wp_get_attachment_image',
			array( static::class, 'replace_trailing_slash' )
		);
		add_filter(
			'jpeg_quality',
			static function () {
				return 100;
			}
		);
		if ( WLD_Is::frontend() && current_user_can( 'edit_posts' ) ) {
			add_filter(
				'image_downsize',
				array( static::class, 'create_sub_sizes' ),
				10,
				3
			);
		}
		add_filter(
			'wp_calculate_image_srcset',
			array( static::class, 'set_one_sizes_srcset' )
		);
		add_filter(
			'image_downsize',
			static function ( $out ) {
				add_filter(
					'image_get_intermediate_size',
					array( static::class, 'set_one_sizes' ),
					10,
					3
				);

				return $out;
			}
		);
		add_filter(
			'intermediate_image_sizes',
			array( static::class, 'remove_defaults' ),
		);
		add_filter(
			'wp_get_attachment_image_attributes',
			array( static::class, 'remove_home_url_from_src_and_srcset' ),
		);
		add_filter(
			'max_srcset_image_width',
			array( static::class, 'set_max_image_width' ),
		);
		add_filter(
			'big_image_size_threshold',
			array( static::class, 'set_max_image_width' ),
		);
	}

	public static function add_size( string $name, string $crop = '', int $width = 0, int $height = 0 ) : void {
		if ( 0 === $width && 0 === $height ) {
			$sizes  = static::get_sizes( $name );
			$width  = (int) ( $sizes['width'] * 2 );
			$height = (int) ( $sizes['height'] * 2 );
			if ( 0 === $width && 0 === $height ) {
				return;
			}
		}
		static::$sizes[ $name ] = array(
			'width'  => $width,
			'height' => $height,
			'crop'   => 'cover' === $crop ? 'cover' : (bool) $crop,
		);
	}

	public static function get_sizes( string $name ) : array {
		$sizes = explode( 'x', $name );

		return array(
			'width'  => (int) ( $sizes[0] ?? '0' ) * 2,
			'height' => (int) ( $sizes[1] ?? '0' ) * 2,
		);
	}

	public static function register() : void {
		ksort( static::$sizes, SORT_NUMERIC );
		foreach ( static::$sizes as $name => $args ) {
			add_image_size(
				$name,
				$args['width'],
				$args['height'],
				$args['crop']
			);
		}
	}

	public static function add_sizes_in_choose( array $size_names ) : array {
		foreach ( static::$sizes as $name => $args ) {
			$size_names[ $name ] = str_replace(
				array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '~' ),
				array( '𝟶', '𝟷', '𝟸', '𝟹', '𝟺', '𝟻', '𝟼', '𝟽', '𝟾', '𝟿', '  ' ),
				str_pad( $name, 8, '~' )
			);
		}

		return $size_names;
	}

	public static function add_crop_dimensions( $default_dimensions, $orig_w, $orig_h, $new_w, $new_h, $crop ) {
		$name = $new_w . 'x' . $new_h; // unfortunately the wrong size may get here
		if ( isset( static::$sizes[ $name ] ) && 'cover' === static::$sizes[ $name ]['crop'] ) {
			$crop = 'cover';
		}

		if ( 'cover' !== $crop ) {
			return $default_dimensions;
		}

		$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );
		$crop_w     = round( $new_w / $size_ratio );
		$crop_h     = round( $new_h / $size_ratio );
		$s_x        = floor( ( $orig_w - $crop_w ) / 2 );
		$s_y        = floor( ( $orig_h - $crop_h ) / 2 );

		return array(
			0,
			0,
			(int) $s_x,
			(int) $s_y,
			(int) $new_w,
			(int) $new_h,
			(int) $crop_w,
			(int) $crop_h,
		);
	}

	public static function remove_image_wrap_p( ?string $content ) : string|null {
		return preg_replace(
			array(
				'/<p>(\s*<img.*\/?>\s*)<\/p>/iU',
				'/<p>(\s*<img.*\/?>\s*)<\/div>/iU',
				'/<p>(\s*<a.*>\s*(<img.*\/?>)\s*<\/a>\s*)<\/p>/iU',
			),
			array(
				'\1',
				'\1<\/div>',
				'\1',
			),
			$content
		);
	}

	public static function get_img( ?int $attachment_id, string $size = 'full', array $attr = array() ) : string {
		if ( is_numeric( $attachment_id ) ) {
			$attachment_id = (int) $attachment_id;
			$attr          = array_merge( array( 'class' => '' ), $attr );

			return wp_get_attachment_image( $attachment_id, $size, false, $attr );
		}

		return '';
	}

	public static function replace_trailing_slash( string $image_html ) : string {
		return str_replace( '/>', '>', $image_html );
	}

	public static function recovery_crop( array $new_sizes ) : array {
		foreach ( $new_sizes as $name => $size ) {
			if ( isset( static::$sizes[ $name ] ) && 'cover' === static::$sizes[ $name ]['crop'] ) {
				$new_sizes[ $name ]['crop'] = 'cover';
			}
		}

		return $new_sizes;
	}

	public static function create_sub_sizes( $out, int $attachment_id, $size ) {
		if ( is_string( $size ) && str_contains( $size, 'x' ) ) {
			$size_data     = static::get_sizes( $size );
			$missing_sizes = array();
			$image_data    = wp_get_attachment_metadata( $attachment_id );

			if ( empty( $image_data['height'] ) || empty( $image_data['width'] ) ) {
				return $out;
			}

			$image_sizes = $image_data['sizes'] ?? array();

			if ( 0 === $size_data['width'] ) {
				$ratio     = $image_data['height'] / $image_data['width'];
				$height    = $size_data['height'] / 2;
				$height_2x = $size_data['height'];
				$width     = (int) round( $height / $ratio );
				$width_2x  = $width * 2;
				$size_name = $width . 'x' . $height;
				$size_data = array(
					'width'  => $width_2x,
					'height' => $height_2x,
					'crop'   => false,
				);

				static::create_missing_sizes_for_sub_sizes(
					$size_name,
					$size,
					$image_sizes,
					$image_data,
					$size_data,
					$missing_sizes,
					$attachment_id
				);

				return $out;
			}

			if ( 0 === $size_data['height'] ) {
				$ratio = $image_data['width'] / $image_data['height'];
				$crop  = false;
			} else {
				$ratio = $size_data['width'] / $size_data['height'];
				$crop  = 'cover';
			}

			$max_width = $size_data['width'] * 3;

			if ( $size_data['width'] > 320 ) {
				foreach ( self::$endpoints as $endpoint_width ) {
					$endpoint_width_2x = $endpoint_width * 2;
					if ( $endpoint_width_2x >= $image_data['width'] ) {
						break;
					}

					$endpoint_height    = (int) round( $endpoint_width / $ratio );
					$endpoint_height_2x = $endpoint_height * 2;
					$endpoint_size_name = $endpoint_width . 'x' . $endpoint_height;
					$endpoint_size_data = array(
						'width'  => $endpoint_width_2x,
						'height' => $endpoint_height_2x,
						'crop'   => $crop,
					);
					if ( ! isset( $image_sizes[ $endpoint_size_name ] ) ) {
						$missing_sizes[ $endpoint_size_name ] = $endpoint_size_data;
					}

					if ( $endpoint_width_2x >= $max_width ) {
						break;
					}
				}
			}

			if ( 'cover' === $crop ) {
				$current_size_data = array(
					'width'  => $size_data['width'],
					'height' => $size_data['height'],
					'crop'   => $crop,
				);
			} elseif ( $image_data['width'] < $size_data['width'] || $image_data['height'] < $size_data['height'] ) {
				$current_size_data = array(
					'width'  => $image_data['width'],
					'height' => $image_data['height'],
					'crop'   => $crop,
				);
			} else {
				$endpoint_height = $size_data['height'];
				if ( 0 === $endpoint_height ) {
					$endpoint_height = (int) round( $size_data['width'] / $ratio );
				}

				$current_size_data = array(
					'width'  => $size_data['width'],
					'height' => $endpoint_height,
					'crop'   => $crop,
				);
			}

			$current_size_name = $current_size_data['width'] / 2 . 'x' . $current_size_data['height'] / 2;
			static::create_missing_sizes_for_sub_sizes(
				$current_size_name,
				$size,
				$image_sizes,
				$image_data,
				$current_size_data,
				$missing_sizes,
				$attachment_id
			);
		}

		return $out;
	}

	protected static function create_missing_sizes( array $missing_sizes, int $attachment_id, array $image_data ) : void {
		if ( $missing_sizes ) {
			$file = get_attached_file( $attachment_id );
			if ( $file ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$need_update = false;
				foreach ( $missing_sizes as $missing_size_name => $missing_size_data ) {
					$new_size_meta = static::resize(
						$file,
						$missing_size_name,
						$missing_size_data,
						$image_data
					);

					if ( ! is_wp_error( $new_size_meta ) ) {
						$need_update = true;
					}
				}

				if ( $need_update ) {
					wp_update_attachment_metadata( $attachment_id, $image_data );
				}
			}
		}
	}

	protected static function create_missing_sizes_for_sub_sizes( string $size_name, string $size, mixed $image_sizes, array $image_data, array $size_data, array $missing_sizes, int $attachment_id ) : void {
		if (
			$size_name !== $size &&
			! isset( $image_sizes[ $size_name ] ) &&
			$image_data['width'] !== $size_data['width'] &&
			$image_data['height'] !== $size_data['height']
		) {
			$missing_sizes[ $size_name ] = $size_data;
		}

		if ( ! isset( $image_sizes[ $size ] ) ) {
			$missing_sizes[ $size ] = $size_data;
		}

		static::create_missing_sizes( $missing_sizes, $attachment_id, $image_data );
	}

	protected static function copy( string $file, int $width, int $height ) : string {
		$ext      = pathinfo( $file, PATHINFO_EXTENSION );
		$name     = wp_basename( $file, '.' . $ext );
		$new_name = $name . '-' . $width . 'x' . $height . '.' . $ext;
		$new_file = dirname( $file ) . '/' . $new_name;

		if ( ! file_exists( $new_file ) ) {
			copy( $file, $new_file );
		}

		return $new_file;
	}

	protected static function resize( string $file, string $size_name, array $size_data, array &$image_data ) : array|WP_Error {
		if ( $size_data['width'] === $image_data['width'] && $size_data['height'] === $image_data['height'] ) {
			$copy_file = static::copy( $file, $size_data['width'], $size_data['height'] );
			$size_meta = array(
				'file'      => wp_basename( $copy_file ),
				'width'     => $image_data['width'],
				'height'    => $image_data['height'],
				'mime-type' => $image_data['sizes']['thumbnail']['mime-type'],
				'filesize'  => wp_filesize( $copy_file ),
			);

			$image_data['sizes'][ $size_name ] = $size_meta;

			return $size_meta;
		}

		/** @var WP_Image_Editor_GD|WP_Error $editor */
		$editor = wp_get_image_editor( $file );
		if ( is_wp_error( $editor ) ) {
			return $editor;
		}

		$resized = $editor->resize( $size_data['width'], $size_data['height'], $size_data['crop'] );
		if ( ! is_wp_error( $resized ) ) {
			$size_meta = $editor->save();
			if ( ! is_wp_error( $size_meta ) ) {
				if ( isset( $size_meta['path'] ) ) {
					unset( $size_meta['path'] );
				}

				$image_data['sizes'][ $size_name ] = $size_meta;

				return $size_meta;
			}

			return $size_meta;
		}

		return $resized;
	}

	public static function set_one_sizes_srcset( array $sources ) : array {
		if ( ! empty( static::$current_size ) ) {
			$size_data = static::get_sizes( static::$current_size );
			if ( 0 === $size_data['width'] ) {
				return array();
			}

			static::$current_size = '';
		}

		foreach ( $sources as $width => $source ) {
			if ( 'w' === $source['descriptor'] ) {
				$new_width = (int) round( $width / 2 );
				if ( $new_width >= 320 ) {
					$sources[ $new_width ] = array(
						'url'        => $source['url'],
						'descriptor' => 'w',
						'value'      => $new_width,
					);
				}

				unset( $sources[ $width ] );
			}
		}

		if ( 1 < count( $sources ) ) {
			ksort( $sources );
		}

		return $sources;
	}

	public static function set_one_sizes( array|bool $data, int $attachment_id, $size ) : array|bool {
		if ( false !== $data && is_string( $size ) && str_contains( $size, 'x' ) ) {
			static::$current_size = $size;

			$size_data = static::get_sizes( $size );

			if ( 0 === $size_data['height'] ) {
				$image_data = wp_get_attachment_metadata( $attachment_id );
				$ratio      = $image_data['width'] / $image_data['height'];
				$height     = (int) round( $size_data['width'] / $ratio / 2 );
			} else {
				$height = $size_data['height'] / 2;
			}

			if ( 0 === $size_data['width'] ) {
				$image_data = wp_get_attachment_metadata( $attachment_id );
				$ratio      = $image_data['height'] / $image_data['width'];
				$width      = (int) round( $size_data['height'] / $ratio / 2 );
			} else {
				$width = $size_data['width'] / 2;
			}

			$data['width']  = $width;
			$data['height'] = $height;
		}

		return $data;
	}

	public static function remove_defaults( array $sizes ) : array {
		$default_sizes = array( 'medium', 'large', 'medium_large', '1536x1536', '2048x2048' );

		return array_diff( $sizes, $default_sizes );
	}

	public static function remove_home_url_from_src_and_srcset( array $attributes ) : array {
		$home_url = home_url();
		if ( isset( $attributes['src'] ) ) {
			$attributes['src'] = str_replace( $home_url, '', $attributes['src'] );
		}

		if ( isset( $attributes['srcset'] ) ) {
			$attributes['srcset'] = str_replace( $home_url, '', $attributes['srcset'] );
		}

		return $attributes;
	}

	public static function set_max_image_width() : int {
		return end( static::$endpoints ) * 2;
	}

	public static function get_picture( array $data ) : string {
		$sources = array();
		$image   = array();

		foreach ( $data as $args ) {
			if ( isset( $args['media'] ) ) {
				$sources[] = array_merge(
					array(
						'size'  => '',
						'media' => '',
						'args'  => array(),
					),
					$args
				);
			} else {
				$image = array_merge(
					array(
						'size' => '',
						'args' => array(),
					),
					$args
				);

				if ( isset( $args['class'] ) ) {
					$image['args']['class'] = $args['class'];
				}
			}
		}

		$picture       = '<picture>';
		$img_max_width = 0;

		foreach ( $sources as $source ) {
			WLD_Hooks::add_filter_once(
				'wp_calculate_image_srcset',
				static function ( array $srcset_sources ) use ( &$img_max_width, $source ) : array {
					if ( preg_match( '/^\(min-width:(\d+)px\)$/', $source['media'], $matches ) ) {
						$min_width = (int) $matches[1];
						if ( 0 === $img_max_width || $min_width < $img_max_width ) {
							$img_max_width = $min_width;
						}

						$srcset_sources = array_filter(
							$srcset_sources,
							static fn( $width ) => $width > $min_width,
							ARRAY_FILTER_USE_KEY
						);
					}

					return $srcset_sources;
				}
			);

			WLD_Hooks::add_filter_once(
				'wp_get_attachment_image_attributes',
				static function ( array $attributes ) use ( $source ) : array {
					if ( isset( $attributes['src'] ) && empty( $attributes['srcset'] ) ) {
						$attributes['srcset'] = $attributes['src'];
					}

					$allowed = WLD_KSES::get_by_tag( 'picture', false );
					foreach ( $attributes as $attribute ) {
						if ( ! isset( $allowed[ $attribute ] ) ) {
							unset( $attributes[ $attribute ] );
						}
					}

					$attributes['media'] = $source['media'];

					return $attributes;
				}
			);

			$picture .= str_replace(
				'<img ',
				'<source ',
				static::get_img(
					$source['id'] ?: $image['id'],
					$source['size'],
					$source['args']
				)
			);
		}

		WLD_Hooks::add_filter_once(
			'wp_calculate_image_srcset',
			static function ( array $srcset_sources ) use ( $img_max_width ) : array {
				if ( $img_max_width ) {
					$srcset_sources = array_filter(
						$srcset_sources,
						static fn( $width ) => $width < $img_max_width,
						ARRAY_FILTER_USE_KEY
					);
				}

				return $srcset_sources;
			}
		);

		$picture .= static::get_img( $image['id'], $image['size'], $image['args'] );
		$picture .= '</picture>';

		return $picture;
	}
}
