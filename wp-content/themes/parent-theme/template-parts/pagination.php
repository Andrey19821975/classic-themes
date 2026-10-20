<?php
/**
 * @var array $args
 * @noinspection HtmlUnknownTarget
 */
global $wp_query;

$old_query = $wp_query;
if ( isset( $args['query'] ) ) {
	// We deliberately change the global variable here to change the output later we reset it.
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$wp_query = $args['query'];
}

$format = '';
if ( isset( $args['format'] ) ) {
	$format = trim( str_replace( '=%#%', '', $args['format'] ), '?' );
}

$defaults = array(
	'type'      => 'list',
	'prev_text' => wld_get_svg( 'pagination_previous_arrow.svg' ) . esc_html__( 'Previous', 'theme' ),
	'next_text' => esc_html__( 'Next', 'theme' ) . wld_get_svg( 'pagination_next_arrow.svg' ),
	'mid_size'  => 1,
);

$pagination = paginate_links( wp_parse_args( apply_filters( 'wld_pagination_args', $args ), $defaults ) );

if ( ! empty( $args['first_last'] ) ) {
	$total   = $wp_query->max_num_pages;
	$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

	$first_url = get_pagenum_link();
	$last_url  = get_pagenum_link( $total );
	if ( $format ) {
		$last_url  = str_replace(
			array( '%_%', '%#%' ),
			array( $args['format'], $total ),
			trailingslashit( explode( '?', $first_url )[0] ) . '%_%'
		);
		$first_url = remove_query_arg( $format, $first_url );
	}

	$first_text  = esc_html__( 'First Page', 'theme' );
	$first_link  = sprintf( '<a href="%s" class="first-page">%s</a>', $first_url, $first_text );
	$last_text   = esc_html__( 'Last Page', 'theme' );
	$last_link   = sprintf( '<a href="%s" class="last-page">%s</a>', $last_url, $last_text );
	$pattern     = array();
	$replacement = array();

	if ( $current > 1 ) {
		$pattern[]     = '/(<ul[^>]*>)/';
		$replacement[] = "$1<li>$first_link</li>";
	}

	if ( $current < $total ) {
		$pattern[]     = '/(<\/ul>)/';
		$replacement[] = "<li>$last_link</li>$1";
	}

	$pagination = preg_replace( $pattern, $replacement, $pagination );
}

if ( is_home() || is_date() || is_category() || is_tag() ) {
	$tags = new WP_HTML_Tag_Processor( $pagination );
	while ( $tags->next_tag() ) {
		$tag_name = strtolower( $tags->get_tag() );
		if ( 'a' === $tag_name ) {
			$tags->get_attribute( 'href' );
			$tags->set_attribute( 'href', $tags->get_attribute( 'href' ) . '#blog-articles' );
		}
	}
	$pagination = $tags->get_updated_html();
}
$pagination = str_replace(
	array( "<ul class='page-numbers" ),
	array( "<ul class='page-numbers pagination" ),
	$pagination
);

// We reset the variable to its initial state, changed above.
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$wp_query = $old_query;

echo wp_kses(
	$pagination,
	array_merge(
		WLD_KSES::get_by_tag( 'ul', false ),
		WLD_KSES::get_by_tag( 'li', false ),
		WLD_KSES::get_by_tag( 'a', false ),
		WLD_KSES::get_by_tag( 'span', false ),
	)
);
