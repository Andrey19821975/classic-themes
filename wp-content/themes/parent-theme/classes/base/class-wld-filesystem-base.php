<?php

class WLD_Filesystem_Base {
	/**
	 * @return WP_Filesystem_Direct In fact, it will not always be this particular class, but in most cases.
	 * @noinspection PhpMissingReturnTypeInspection, ReturnTypeCanBeDeclaredInspection
	 */
	public static function get_filesystem() {
		global $wp_filesystem;

		if ( null === $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				/** @noinspection PhpIncludeInspection, RedundantSuppression */
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	public static function get_file_json_array( string $path ) : array {
		/** @noinspection JsonEncodingApiUsageInspection */
		$json = json_decode( static::get_file_contents( $path ), true );
		if ( is_array( $json ) ) {
			return $json;
		}

		return array();
	}

	public static function get_file_contents( string $path ) : string {
		$filesystem = static::get_filesystem();
		if ( $filesystem->exists( $path ) ) {
			return $filesystem->get_contents( $path );
		}

		return '';
	}

	public static function put_file_contents( string $path, $contents ) : bool {
		$filesystem = static::get_filesystem();
		$dir        = pathinfo( $path, PATHINFO_DIRNAME );
		if ( ! $filesystem->exists( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		return $filesystem->put_contents( $path, $contents );
	}

	public static function delete_file_or_folder( string $path ) : bool {
		return static::get_filesystem()->delete( $path, true );
	}

	public static function exists( string $path ) : bool {
		return static::get_filesystem()->exists( $path );
	}
}
