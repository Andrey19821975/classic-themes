<?php
$sidebar_items_selector = is_singular() ? 'blog_post_sidebar_items' : 'blog_sidebar_items';
$sidebar_items_post_id  = false;
if ( ! have_rows( $sidebar_items_selector ) ) {
	$sidebar_items_selector = is_singular() ? 'wld_blog_post_sidebar_items' : 'wld_blog_sidebar_items';
	$sidebar_items_post_id  = 'options';
}
if ( have_rows( $sidebar_items_selector, $sidebar_items_post_id ) ) {
	echo '<aside class="blog-sidebar">';
	while ( have_rows( $sidebar_items_selector, $sidebar_items_post_id ) ) {
		the_row();
		get_template_part( 'template-parts/blog/sidebar/' . get_row_layout() );
	}
	echo '</aside>';
}
