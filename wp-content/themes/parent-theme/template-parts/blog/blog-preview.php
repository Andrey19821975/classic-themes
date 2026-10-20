<?php
$index      = 0;
$sticky_ids = get_option( 'sticky_posts', array() );
if ( ! is_array( $sticky_ids ) || empty( $sticky_ids ) ) {
	return;
}

rsort( $sticky_ids );
$sticky_posts = get_posts( array( 'include' => $sticky_ids ) );
?>
<section class="blog-preview">
	<div class="inner">
		<?php while ( wld_loop( $sticky_posts, '<div class="blog-preview__wrapper">' ) ) : ?>
			<?php
			get_template_part(
				'template-parts/blog/blog-preview-item',
				null,
				array(
					'is_first' => 0 === $index++,
				)
			);
			?>
		<?php endwhile; ?>
	</div>
</section>
