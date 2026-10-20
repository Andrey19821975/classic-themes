<?php
if ( ! is_front_page() ) {
	return;
}

$theme = wp_get_theme();
$name  = $theme->get( 'Author' );
$href  = $theme->get( 'AuthorURI' );

if ( empty( $href ) || empty( $name ) ) {
	return;
}
?>
<a href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener" class="page-footer__by-link">
	<?php esc_html_e( 'Dallas and Plano Web Design Agency:', 'theme' ); ?>
	<span class="page-footer__by-name"><?php echo esc_html( $name ); ?></span>
</a>
