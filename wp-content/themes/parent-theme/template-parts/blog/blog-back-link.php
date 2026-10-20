<?php
$current_post = get_post( $post );
if ( empty( $current_post ) ) {
	return;
}

$current_post_type = get_post_type( $post );
$archive_url       = get_post_type_archive_link( $current_post_type );
if ( 'post' === $current_post_type ) {
	$archive_title = get_the_title( get_option( 'page_for_posts' ) );
} else {
	$archive_title = get_post_type_object( $current_post_type )->labels->name;
}

if ( empty( $archive_url ) || empty( $archive_title ) ) {
	return;
}

$args = compact( 'archive_url', 'archive_title' );
?>
<div class="blog-post__back"><?php wld_get_template_part( 'template-parts/blog/blog-back-link-a', $args ); ?></div>
