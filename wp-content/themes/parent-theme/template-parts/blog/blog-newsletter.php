<?php
/**
 * @var array{
 *     selector: string,
 *     class: string
 * } $args
 */
?>
<?php while ( wld_loop( $args['selector'] ) ) : ?>
	<?php if ( wld_has( 'images', 'title', 'text', 'form' ) ) : ?>
		<section class="blog-newsletter <?php echo esc_attr( $args['class'] ); ?>">
			<div class="blog-newsletter__wrapper">
				<div class="blog-newsletter__content">
					<?php while ( wld_loop( 'images', '<div class="blog-newsletter__images">' ) ) : ?>
						<?php wld_the( 'image', '66x66', 'blog-newsletter__image' ); ?>
					<?php endwhile; ?>
					<?php wld_the( 'title', 'blog-newsletter__title' ); ?>
					<?php wld_the( 'text', '<p class="blog-newsletter__description">' ); ?>
				</div>
				<?php wld_the( 'form' ); ?>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>
