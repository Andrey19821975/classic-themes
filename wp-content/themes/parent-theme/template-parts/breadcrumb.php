<?php
if ( WLD_Is::breadcrumb_enabled() ) {
	yoast_breadcrumb(
		sprintf( // translators: Breadcrumb aria label.
			'<nav class="breadcrumb" aria-label="%s">',
			esc_html__( 'Breadcrumb', 'theme' )
		),
		'</nav>'
	);
}
