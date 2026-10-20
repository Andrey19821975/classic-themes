<?php /** @noinspection SqlDialectInspection */

class WLD_Video_Base {
	public static function get_image(
		?string $youtube_url,
		string $wp_size,
		int $attachment_id = 0,
		array $attr = array()
	) : string {
		$youtube_id = static::get_id( $youtube_url );
		if ( 0 === $attachment_id && $youtube_id ) {
			$attachment_id = static::get_attachment_id( $youtube_id );
		}

		return WLD_Images::get_img( $attachment_id, $wp_size, $attr );
	}

	public static function get_id( ?string $youtube_url ) : string {
		$pattern = '/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?vi?=|&vi?=))([^#&?]*).*/';
		if (
			$youtube_url &&
			filter_var( $youtube_url, FILTER_VALIDATE_URL ) &&
			preg_match( $pattern, $youtube_url, $matches )
		) {
			return $matches[1];
		}

		return '';
	}

	protected static function get_attachment_id( string $youtube_id ) : int {
		$url           = 'https://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg';
		$attachment_id = static::get_attachment_id_by_url( $url );

		if ( empty( $attachment_id ) ) {
			if ( ! function_exists( 'download_url' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			$tmp = download_url( $url );
			if ( ! is_wp_error( $tmp ) ) {
				$file_array = array(
					'name'     => 'youtube-' . $youtube_id . '.jpg',
					'tmp_name' => $tmp,
				);

				$post_data = array(
					'post_title'   => 'YouTube ' . $youtube_id,
					'post_content' => $url,
				);

				if ( ! function_exists( 'media_handle_sideload' ) ) {
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';
				}

				$attachment_id = media_handle_sideload( $file_array, 0, '', $post_data );
				if ( is_wp_error( $attachment_id ) ) {
					return 0;
				}
			}
		}

		return (int) $attachment_id;
	}

	/** @noinspection SqlNoDataSourceInspection, SqlResolve */
	protected static function get_attachment_id_by_url( string $url ) : int {
		global $wpdb;

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_content=%s", $url ) );
	}

	public static function get_image_url( ?string $youtube_url, string $wp_size, int $default_attachment_id = 0 ) : string {
		$url        = '';
		$youtube_id = static::get_id( $youtube_url );

		if ( $youtube_id ) {
			$attachment_id = static::get_attachment_id( $youtube_id );
			if ( $attachment_id ) {
				$url = wp_get_attachment_image_url( $attachment_id, $wp_size );
			}
		}

		if ( empty( $url ) ) {
			$url = wp_get_attachment_image_url( $default_attachment_id, $wp_size );
		}

		return (string) $url;
	}

	public static function get_supported_video_url( ?string $url ) : string {
		$video_url = '';

		if ( $url ) {
			$youtube_id = static::get_id( $url );
			if ( $youtube_id ) {
				$video_url = 'https://www.youtube.com/watch?v=' . $youtube_id;
			} elseif ( str_contains( $url, 'vimeo' ) ) {
				preg_match( '~vimeo\.com/(?>video/)?(\d+)~', $url, $matches );
				if ( isset( $matches[1] ) ) {
					$video_url = 'https://vimeo.com/' . $matches[1];
				}
			}
		}

		return $video_url;
	}

	public static function wld_get_supported_video_embed_url( ?string $url ) : string {
		$video_url = '';

		if ( $url ) {
			$youtube_id = static::get_id( $url );
			if ( $youtube_id ) {
				$video_url = 'https://www.youtube.com/embed/' . $youtube_id;
			} elseif ( str_contains( $url, 'vimeo' ) ) {
				preg_match( '~vimeo\.com/(?>video/)?(\d+)(\?.+)?~', $url, $matches );
				if ( isset( $matches[1] ) ) {
					if ( $matches[2] ) {
						$params = $matches[2];
					} else {
						$params = '?title=0&byline=0&portrait=0';
					}

					$video_url = 'https://player.vimeo.com/video/' . $matches[1] . $params;
				}
			}
		}

		return $video_url;
	}
}
