<?php

class WLD_Hooks_Base {
	public static function remove_filter_for_class( string $hook, string $class_name, string $method, int $priority = 10 ) : void {
		global $wp_filter;

		$callbacks = $wp_filter[ $hook ][ $priority ] ?? array();
		if ( $callbacks ) {
			foreach ( $callbacks as $id => $filter ) {
				if (
					is_array( $filter['function'] ) &&
					! empty( $filter['function'][0] ) &&
					! empty( $filter['function'][1] ) &&
					$filter['function'][1] === $method
				) {
					if ( is_object( $filter['function'][0] ) ) {
						$filter_class = get_class( $filter['function'][0] );
					} else {
						$filter_class = $filter['function'][0];
					}
					if ( $class_name === $filter_class ) {
						unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $id ] );
					}
				}
			}
		}
	}

	public static function add_filter_once( string $hook_name, callable $hook_callback, int $priority = 10, int $accepted_args = 1 ) : bool {
		$callback = static function () use ( $hook_name, $hook_callback, $priority, &$callback ) {
			remove_filter( $hook_name, $callback, $priority );

			return $hook_callback( ...func_get_args() );
		};

		return add_filter( $hook_name, $callback, $priority, $accepted_args );
	}

	public static function add_action_once( string $hook_name, callable $hook_callback, int $priority = 10, int $accepted_args = 1 ) : bool {
		return WLD_Hooks::add_filter_once( $hook_name, $hook_callback, $priority, $accepted_args );
	}
}
