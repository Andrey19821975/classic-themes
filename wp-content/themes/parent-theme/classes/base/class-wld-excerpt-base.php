<?php

class WLD_Excerpt_Base {
	public static function get_the_excerpt( int $length, bool $filter = false, bool $trim = false, string $after = '' ) : string {
		global $post;

		if ( has_excerpt() ) {
			$output = wp_strip_all_tags( $post->post_excerpt );
		} else {
			$output = get_the_content();
			if ( empty( $output ) && WLD_Theme::$acf_enabled ) {
				$fields = get_fields( $post->ID );
				static::array_to_excerpt( $fields, $output );
			}
			$output = wp_strip_all_tags( strip_shortcodes( $output ) );
			if ( false === $filter ) {
				$output = str_replace( array( "\r\n", "\r", "\n" ), '', $output );
				$output = trim( str_replace( '&nbsp;', ' ', $output ) );
				$output = preg_replace( '/\s+/', ' ', $output );
			}
			if ( strlen( $output ) > $length ) {
				$output = substr( $output, 0, $length );
				for ( $index = $length - 1; $index >= 0; $index-- ) {
					if ( preg_match( '/(\.|,|!|\?|:|;|\s)/', $output[ $index ] ) ) {
						$output = substr( $output, 0, $index + 1 );
						break;
					}
				}
			}
			if ( $trim ) {
				$output = rtrim( $output, '.,!?:; ' );
			}
		}
		$output = rtrim( $output, "\r\n" ) . $after;
		if ( $filter ) {
			return apply_filters( 'get_the_excerpt', $output );
		}

		return $output;
	}

	public static function array_to_excerpt( $values, string &$excerpt ) : void {
		$keys = array( 'text', 'content', 'title', 'subtitle' );
		if ( is_array( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) && ! isset( $value['filename'] ) ) {
						static::array_to_excerpt( $value, $excerpt );
					} elseif ( is_string( $value ) && in_array( $key, $keys, true ) ) {
						$excerpt .= $value . "\n ";
					}
				}
			}
		}
	}
}
