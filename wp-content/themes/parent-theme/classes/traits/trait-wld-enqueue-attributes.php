<?php

trait WLD_Enqueue_Attributes {
	protected static array $attributes      = array();

	public static function add_attribute( string $handle, string $name, $value, string $type = '' ) : void {
		if ( $type ) {
			if ( 'inline' === $type || 'after' === $type ) {
				$handle .= '-js-after';
			} elseif ( 'before' === $type ) {
				$handle .= '-js-before';
			}
		}

		if ( ! isset( static::$attributes[ $handle ] ) ) {
			static::$attributes[ $handle ] = array();
		}

		if ( is_array( $value ) || is_object( $value ) ) {
			static::$attributes[ $handle ][ $name ] = wp_json_encode( $value );
		} else {
			static::$attributes[ $handle ][ $name ] = $value;
		}
	}

	public static function insert_attributes( string $tag, string $handle ) : string {
		if ( isset( static::$attributes[ $handle ] ) ) {
			foreach ( static::$attributes[ $handle ] as $attribute => $value ) {
				$tag = str_replace(
					' href',
					' ' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '" href',
					$tag
				);
			}
		}

		return $tag;
	}

	public static function insert_inline_attributes( array $attributes ) : array {
		if ( isset( $attributes['id'], static::$attributes[ $attributes['id'] ] ) ) {
			return array_merge( $attributes, static::$attributes[ $attributes['id'] ] );
		}

		return $attributes;
	}
}
