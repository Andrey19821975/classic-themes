<?php
/**
 * @var string $selector
 * @var string $acf_post_id
 * @var string $size
 * @var string $sizes
 */
$selector    = $selector ?? 'wld_header_logo';
$is_head     = 'wld_header_logo' === $selector;
$attr        = array(
	'loading'       => $is_head ? 'eager' : 'lazy',
	'fetchpriority' => $is_head ? 'high' : 'low',
	'alt'           => get_bloginfo( 'name' ),
);
$acf_post_id = str_starts_with( $selector, 'wld_' ) ? 'options' : null;

if ( isset( $sizes ) ) {
	$attr['sizes'] = $sizes;
}
?>
<div class="logo">
	<?php
	while ( wld_wrap( is_front_page() ? '' : home_url( '/' ), 'logo__link', false ) ) {
		foreach ( array( '', '_2' ) as $selector_postfix ) {
			$full_selector = $selector . $selector_postfix;
			$field_object  = (array) get_field_object( $full_selector, $acf_post_id );
			if ( ! empty( $field_object['value'] ) ) {
				if ( str_ends_with( $field_object['value'], '.svg' ) ) {
					wld_the( $full_selector, 'svg', 'logo__image', $attr );
					echo '<span class="screen-reader-text">' . esc_html( $attr['alt'] ) . '</span>';
				} else {
					wld_the( $full_selector, $size ?? 'full', 'logo__image', $attr );
				}
			}
		}
	}
	?>
</div>
