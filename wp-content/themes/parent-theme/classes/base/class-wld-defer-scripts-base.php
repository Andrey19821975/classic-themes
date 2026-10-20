<?php

class WLD_Defer_Scripts_Base {
	public const SRC_SCHEME = 'data:text/javascript;base64,';

	protected static array $handles       = array();
	protected static array $after_scripts = array();

	public static function init() : void {
		if ( is_admin() ) {
			return;
		}

		add_action(
			'wp_head',
			array( static::class, 'remake_scripts' ),
			8
		);
		add_action(
			'wp_print_scripts',
			array( static::class, 'remake_scripts' )
		);
		add_action(
			'wp_print_footer_scripts',
			array( static::class, 'remake_scripts' ),
			9
		);
		add_action(
			'script_loader_tag',
			array( static::class, 'insert' ),
			10,
			2
		);
	}

	public static function add( string $handle, callable $toggle_callback = null ) : void {
		if ( ! isset( static::$handles[ $handle ] ) ) {
			if ( is_callable( $toggle_callback ) ) {
				static::$handles[ $handle ] = $toggle_callback;
			} else {
				static::$handles[ $handle ] = true;
			}
		}
	}

	public static function remake_scripts() : void {
		foreach ( static::$handles as $handle => $noop ) {
			if ( is_callable( static::$handles[ $handle ] ) ) {
				if ( static::$handles[ $handle ]() ) {
					static::$handles[ $handle ] = true;
				} else {
					unset( static::$handles[ $handle ] );
				}
			}
		}

		if ( isset( static::$handles['jquery'] ) && ! isset( static::$handles['jquery-core'] ) ) {
			static::$handles['jquery-core'] = true;
		} elseif ( isset( static::$handles['jquery-core'] ) && ! isset( static::$handles['jquery'] ) ) {
			static::$handles['jquery'] = true;
		}

		static::set_dependents();
		static::set_inline_dependents();
	}

	public static function insert( string $tag, string $handle ) : string {
		if ( isset( static::$handles[ $handle ] ) && static::$handles[ $handle ] ) {
			if ( ! str_contains( $tag, ' defer ' ) && ! str_contains( $tag, ' async ' ) ) {
				$tag = str_replace(
					array( ' src', home_url() . static::SRC_SCHEME ),
					array( ' defer src', static::SRC_SCHEME ),
					$tag
				);
			}

			if ( isset( static::$after_scripts[ $handle ] ) ) {
				$src      = static::$after_scripts[ $handle ][0];
				$original = '';
				if ( WP_DEBUG ) {
					$original = str_replace( '*/', '^/', static::$after_scripts[ $handle ][1] );
					$original = '/*' . "\r\n" . $original . "\r\n" . '*/';
				}
				$tag .= '<script id="' . $handle . '-after" src="' . $src . '">' . $original . '</script>';
			}
		}

		return $tag;
	}

	protected static function set_dependents() : void {
		$dependents_map = array();

		foreach ( wp_scripts()->registered as $registered_handle => $args ) {
			if ( ! isset( $dependents_map[ $registered_handle ] ) ) {
				$dependents_map[ $registered_handle ] = array();
			}

			foreach ( $args->deps as $dependency_handle ) {
				if ( ! isset( $dependents_map[ $dependency_handle ] ) ) {
					$dependents_map[ $dependency_handle ] = array();
				}

				$dependents_map[ $dependency_handle ][] = $registered_handle;
			}
		}

		$old_handles = static::$handles;

		do {
			$new_handles = array();
			foreach ( $old_handles as $handle => $noop ) {
				if ( empty( $dependents_map[ $handle ] ) ) {
					continue;
				}
				foreach ( $dependents_map[ $handle ] as $dependency_handle ) {
					if ( ! isset( static::$handles[ $dependency_handle ] ) ) {
						$new_handles[ $dependency_handle ]     = true;
						static::$handles[ $dependency_handle ] = true;
					}
				}
			}
			$old_handles = $new_handles;
		} while ( $new_handles );
	}

	protected static function set_inline_dependents() : void {
		foreach ( static::$handles as $handle => $noop ) {
			$after = wp_scripts()->get_inline_script_data( $handle );
			if ( $after ) {
				$after_to_src = static::inline_script_to_src( $after );
				$src          = wp_scripts()->registered[ $handle ]->src;
				if ( empty( $src ) ) {
					wp_scripts()->registered[ $handle ]->src = $after_to_src;
					wp_scripts()->registered[ $handle ]->ver = null;
				} elseif ( ! isset( static::$after_scripts[ $handle ] ) ) {
					static::$after_scripts[ $handle ] = array( $after_to_src, $after );

					if ( 'jquery' === $handle ) {
						static::$after_scripts['jquery-core'] = array( $after_to_src, $after );
					} elseif ( 'jquery-core' === $handle ) {
						static::$after_scripts['jquery'] = array( $after_to_src, $after );
					}
				}

				wp_scripts()->add_data( $handle, 'after', array() );
			}
		}
	}

	protected static function inline_script_to_src( string $inline_script ) : string {
		// base64_encode is needed, because we need to get the base64 for the SRC attribute of the script tag.
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return static::SRC_SCHEME . base64_encode( $inline_script );
	}
}
