<?php

class WLD_Enqueue_Base {
	public static function get_enqueue_data( string $file_name, array $args = array() ) : array {
		$data    = explode( '.', $file_name );
		$handle  = sprintf( 'theme-%s', $data[0] );
		$type    = $data[1];
		$file    = sprintf( '%s/%s', $type, $file_name );
		$path    = WLD_File::get_assets_path( $file );
		$url     = WLD_File::get_assets_url( $file );
		$exists  = file_exists( $path );
		$version = $exists ? static::get_version( $path ) : '';

		if ( isset( $args['deps'] ) ) {
			$deps = is_array( $args['deps'] ) ? $args['deps'] : array( $args['deps'] );
		} else {
			$deps = array();
		}

		return array(
			'file_name' => $file_name,
			'handle'    => $handle,
			'type'      => $type,
			'path'      => $path,
			'url'       => $url,
			'exists'    => $exists,
			'version'   => $version,
			'deps'      => $deps,
		);
	}

	public static function get_version( string $path ) : string {
		return (string) filemtime( $path );
	}
}
