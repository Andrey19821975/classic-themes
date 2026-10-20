<?php
if ( is_home() ) {
	$archive_title = get_the_title( get_option( 'page_for_posts' ) );
} elseif ( is_category() ) {
	$archive_title = single_cat_title( '', false );
} elseif ( is_post_type_archive() ) {
	$archive_title = post_type_archive_title( '', false );
} else {
	$archive_title = get_the_archive_title();
}
?>
<h1 class="blog-title"><?php echo esc_html( $archive_title ); ?></h1>
