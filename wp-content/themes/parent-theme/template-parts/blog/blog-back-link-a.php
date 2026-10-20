<?php
/**
 * @var string $archive_url
 * @var string $archive_title
 */
?>
<a href="<?php echo esc_url( $archive_url ); ?>" class="blog-post__back-link">
	<?php get_template_part( 'template-parts/blog/back-link-icon' ); ?>
	<?php echo esc_html( $archive_title ); ?>
</a>
