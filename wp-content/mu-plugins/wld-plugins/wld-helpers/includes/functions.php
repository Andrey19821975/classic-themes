<?php /** @noinspection DisallowWritingIntoStaticPropertiesInspection, PhpFunctionNamingConventionInspection */

use JetBrains\PhpStorm\NoReturn;
use Kint\Kint;
use Kint\Renderer\RichRenderer;

function d() {
	if ( ! headers_sent() ) {
		header( 'Content-Type: text/html' );
	}

	$args = func_get_args();

	return call_user_func_array( array( 'Kint', 'dump' ), $args );
}

function s() {
	if ( ! Kint::$enabled_mode ) {
		return 0;
	}

	if ( ! headers_sent() ) {
		header( 'Content-Type: text/html' );
	}

	$stash = Kint::$enabled_mode;

	if ( Kint::MODE_TEXT !== Kint::$enabled_mode ) {
		Kint::$enabled_mode = Kint::MODE_PLAIN;
		if ( PHP_SAPI === 'cli' && true === Kint::$cli_detection ) {
			Kint::$enabled_mode = Kint::$mode_default_cli;
		}
	}

	$args = func_get_args();
	$out  = call_user_func_array( array( 'Kint', 'dump' ), $args );

	Kint::$enabled_mode = $stash;

	return $out;
}

#[NoReturn] function sd() : void {
	s( ...func_get_args() );
	exit();
}

function st() : void {
	s( ...func_get_args() );
	Kint::trace();
}

#[NoReturn] function std() : void {
	st( ...func_get_args() );
	exit();
}

#[NoReturn] function dd() : void {
	if ( ! headers_sent() ) {
		header( 'Content-Type: text/html' );
	}

	$args = func_get_args();

	if ( count( $args ) > 1 && end( $args ) === '~' ) {
		array_pop( $args );
		RichRenderer::$folder = true;
		Kint::$expanded       = false;
	}

	d( ...$args );
	exit();
}
