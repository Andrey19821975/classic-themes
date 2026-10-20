<?php
/**
 * ACF helpers
 */
if ( ! function_exists( 'wld_get' ) ) {
	function wld_get( string $selector, ...$args ) : string {
		return WLD_Fields::get( $selector, $args );
	}
}
if ( ! function_exists( 'wld_the' ) ) {
	function wld_the( string $selector, ...$args ) : void {
		echo wld_get( $selector, ...$args );
	}
}
if ( ! function_exists( 'wld_get_value' ) ) {
	function wld_get_value( string $key = '' ) : string {
		return WLD_Fields::get_value( $key );
	}
}
if ( ! function_exists( 'wld_the_value' ) ) {
	function wld_the_value( string $key = '' ) : void {
		echo wld_get_value( $key );
	}
}
if ( ! function_exists( 'wld_get_loop_index' ) ) {
	function wld_get_loop_index() : int {
		return WLD_Fields::get_loop_index();
	}
}
if ( ! function_exists( 'wld_get_index_attr' ) ) {
	function wld_get_index_attr( string $before = '', string $after = '' ) : string {
		return implode(
			'-',
			array_filter(
				array(
					$before,
					WLD_ACF_Flex_Content::get_index(),
					WLD_Fields::get_loop_index(),
					$after,
				)
			)
		);
	}
}
if ( ! function_exists( 'wld_the_index_attr' ) ) {
	function wld_the_index_attr( string $before = '', string $after = '' ) : void {
		echo esc_attr( wld_get_index_attr( $before, $after ) );
	}
}
if ( ! function_exists( 'wld_loop' ) ) {
	function wld_loop( $selector_or_posts_array, string $wrapper = '', $acf_post_id = null ) : bool {
		return WLD_Fields::loop( $selector_or_posts_array, $wrapper, $acf_post_id );
	}
}
if ( ! function_exists( 'wld_wrap' ) ) {
	function wld_wrap( string $selector_or_url, string $class_name = '', bool $is_force = true, array $attrs = array(), $acf_post_id = null ) : bool {
		return WLD_Fields::wrap( $selector_or_url, $class_name, $is_force, $attrs, $acf_post_id );
	}
}
if ( ! function_exists( 'wld_has' ) ) {
	function wld_has( ...$selectors_and_maybe_conditional ) : bool {
		return WLD_Fields::has( $selectors_and_maybe_conditional );
	}
}
if ( ! function_exists( 'wld_get_as' ) ) {
	function wld_get_as( string $as_type, string $selector, ...$args ) : string {
		WLD_Fields::next_field_process_as_type( $as_type );

		return wld_get( $selector, ...$args );
	}
}
if ( ! function_exists( 'wld_the_as' ) ) {
	function wld_the_as( string $as_type, string $selector, ...$args ) : void {
		echo wld_get_as( $as_type, $selector, ...$args );
	}
}

/**
 * Templates helpers
 */
if ( ! function_exists( 'wld_get_template_part' ) ) {
	function wld_get_template_part( string $path, array $args = array(), WP_Post|WP_Term|WP_User|WP_Comment|false|null $post_id = null ) : string {
		return WLD_Fields::get_template_part( $path, $args, $post_id );
	}
}
if ( ! function_exists( 'wld_the_template_part' ) ) {
	function wld_the_template_part( string $path, array $args = array(), WP_Post|WP_Term|WP_User|WP_Comment|false|null $post_id = null ) : void {
		echo wld_get_template_part( $path, $args, $post_id );
	}
}
if ( ! function_exists( 'wld_get_the_excerpt' ) ) {
	function wld_get_the_excerpt( int $length, bool $filter = false, bool $trim = false, string $after = '' ) : string {
		return WLD_Excerpt::get_the_excerpt( $length, $filter, $trim, $after );
	}
}
if ( ! function_exists( 'wld_the_excerpt' ) ) {
	function wld_the_excerpt( int $length, bool $filter = false, bool $trim = false, string $after = '' ) : void {
		echo wp_kses( wld_get_the_excerpt( $length, $filter, $trim, $after ), 'strip' );
	}
}
if ( ! function_exists( 'wld_get_the_nav' ) ) {
	function wld_get_the_nav( string $location_label, bool $is_mobile = false, array $args = array() ) : string {
		return WLD_Nav::get_the_nav( $location_label, $is_mobile, $args );
	}
}
if ( ! function_exists( 'wld_the_nav' ) ) {
	function wld_the_nav( string $location_label, bool $is_mobile = false, array $args = array() ) : void {
		echo wp_kses(
			wld_get_the_nav( $location_label, $is_mobile, $args ),
			WLD_KSES::get_by_nav()
		);
	}
}
if ( ! function_exists( 'wld_get_svg' ) ) {
	function wld_get_svg( string|int $file_name_or_attachment_id, string $class_name = '' ) : string {
		return WLD_SVG::get_svg( $file_name_or_attachment_id, $class_name );
	}
}
if ( ! function_exists( 'wld_the_svg' ) ) {
	function wld_the_svg( string|int $file_name_or_attachment_id, string $class_name = '' ) : void {
		echo wp_kses(
			wld_get_svg( $file_name_or_attachment_id, $class_name ),
			WLD_KSES::get_by_svg()
		);
	}
}
