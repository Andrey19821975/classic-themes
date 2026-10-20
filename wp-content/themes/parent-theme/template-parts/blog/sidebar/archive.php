<?php
$allowed_html = array(
	'ul' => array(
		'class' => true,
	),
	'li' => array(
		'class' => true,
	),
	'a'  => array(
		'class'        => true,
		'href'         => true,
		'rel'          => true,
		'aria-current' => true,
	),
);
$archive_tags = new WP_HTML_Tag_Processor( wp_get_archives( array( 'echo' => false ) ) );
while ( $archive_tags->next_tag() ) {
	if ( 'A' === $archive_tags->get_tag() ) {
		$archive_tags->add_class( 'archive__link' );
		if ( 'page' === $archive_tags->get_attribute( 'aria-current' ) ) {
			$archive_tags->add_class( 'archive__link_current' );
		}
	} elseif ( 'LI' === $archive_tags->get_tag() ) {
		$archive_tags->add_class( 'archive__item' );
	}
}
?>
<nav class="blog-sidebar__archive">
	<h2 class="blog-sidebar__title"><?php esc_html_e( 'Archive', 'theme' ); ?></h2>
	<ul class="archive">
		<?php echo wp_kses( $archive_tags->get_updated_html(), $allowed_html ); ?>
	</ul>
</nav>
