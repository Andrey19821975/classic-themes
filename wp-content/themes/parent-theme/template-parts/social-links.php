<?php
$size     ??= '50x50';
$selector ??= 'wld_social_links';
$class    ??= '';

$field_object = (array) get_field_object( $selector, str_starts_with( $selector, 'wld_' ) ? 'options' : null );

while ( wld_loop( $selector, '<div class="social-links ' . esc_attr( $class ) . '">' ) ) {
	$value  = $field_object['value'][ (int) get_row_index() - 1 ];
	$is_svg = isset( $value['icon_color']['subtype'] ) && 'svg+xml' === $value['icon_color']['subtype'] && empty( $value['icon_outline'] );
	while ( wld_wrap( 'link', 'social-links__link' ) ) {
		if ( $is_svg ) {
			wld_the( 'icon_color', $size, 'svg', 'social-links__image' );
		} else {
			wld_the( 'icon_color', $size, 'social-links__image social-links__image_color', array( 'aria-hidden' => 'true' ) );
			wld_the( 'icon_outline', $size, 'social-links__image social-links__image_outline', array( 'aria-hidden' => 'true' ) );
		}
		if ( wld_has( 'title' ) ) {
			printf(
				'<span class="social-links__text">%s</span>',
				wld_get_value( 'title' )
			);
		}
	}
}
