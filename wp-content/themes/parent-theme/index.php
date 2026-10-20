<?php
get_header();

if ( post_password_required() ) {
	// todo: Escape output.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo get_the_password_form();
} elseif ( is_singular() ) {
	if ( is_single() ) {
		while ( wld_loop( 'wld_blog_menu' ) ) {
			get_template_part( 'template-parts/blog/blog-menu' );
		}
		get_template_part( 'template-parts/blog/blog-title' );
		get_template_part( 'template-parts/blog/blog-post' );
		while ( wld_loop( 'wld_blog_form' ) ) {
			get_template_part( 'template-parts/blog/blog-form' );
		}
	} else {
		get_template_part( 'template-parts/default-page' );
	}
} elseif ( is_home() || is_archive() ) {
	get_template_part( 'template-parts/blog/blog-archive-title' );
	get_template_part( 'template-parts/blog/blog-preview' );
	get_template_part(
		'template-parts/blog/blog-newsletter',
		null,
		array(
			'selector' => 'wld_blog_newsletter_top',
			'class'    => 'blog-newsletter_top',
		)
	);
	get_template_part( 'template-parts/blog/blog-posts' );
} elseif ( is_search() ) {
	$search_title = sprintf( '<h1 class="title">%s</h1>', esc_html__( 'Search', 'theme' ) );
	get_template_part( 'template-parts/search-form', null, array( 'title' => $search_title ) );
	get_template_part( 'template-parts/search-results' );
} else {
	get_template_part( 'template-parts/error-404' );
}

get_footer();
