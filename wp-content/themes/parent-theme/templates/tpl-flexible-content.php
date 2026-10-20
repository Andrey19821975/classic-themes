<?php
/**
 * Template Name: Flexible Content
 */

get_header();

if ( post_password_required() ) {
	// todo: Escape output.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo get_the_password_form();
} else {
	$need_run = apply_filters( 'wld_need_run_content', true );
	if ( $need_run ) {
		do_action( 'wld_run_content' );
		WLD_ACF_Flex_Content::the_loop( 'content' );
		do_action( 'wld_end_content' );
	} else {
		do_action( 'wld_not_need_run_content' );
	}
}

get_footer();
