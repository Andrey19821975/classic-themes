<?php

class WLD_Dequeue_Scripts_Base {
	protected static array $handles = array();

	public static function init() : void {
		add_action(
			'wp_print_scripts',
			array( static::class, 'dequeue_scripts' ),
			PHP_INT_MIN
		);
		add_action(
			'wp_print_footer_scripts',
			array( static::class, 'dequeue_scripts' ),
			PHP_INT_MIN
		);
	}

	public static function add( string $handle ) : void {
		static::$handles[] = $handle;
	}

	public static function dequeue_scripts() : void {
		foreach ( static::$handles as $handle ) {
			wp_dequeue_script( $handle );
		}
	}
}
