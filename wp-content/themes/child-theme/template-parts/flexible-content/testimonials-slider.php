<?php
if ( wld_get( 'background' ) ) {
	$image = wld_get( 'image', '331x0', 'testimonials-slider__img', '<div class="testimonials-slider__image">' );
} else {
	$image = wld_get( 'image', '167x0', 'testimonials-slider__img', '<div class="testimonials-slider__image">' );
}
?>
<?php $button = wld_get( 'button', 'btn', '<div class="testimonials-slider__btn">' ); ?>
<section class="testimonials-slider<?php echo wld_get( 'background' ) ? ' testimonials-slider_bg-white' : ''; ?>">
	<div class="inner">
		<?php if ( 1 === (int) get_row_index() ) : ?>
			<h1 class="screen-reader-text"><?php esc_html_e( 'Reviews', 'theme' ); ?></h1>
		<?php endif; ?>
		<?php while ( wld_loop( 'testimonials', '<div class="testimonials-slider__slider" data-slider="testimonials">' ) ) : ?>
			<div class="testimonials-slider__item">
				<div class="testimonials-slider__wrapper">
					<?php echo wp_kses( $image, 'post' ); ?>
					<?php wld_the( 'title', 'testimonials-slider__title' ); ?>
					<p class="testimonials-slider__summary">
						<?php echo wp_kses( get_the_content(), 'strip' ); ?>
					</p>
					<?php wld_the( 'author', '<h3 class="testimonials-slider__h3">' ); ?>
					<?php wld_the( 'position', '<p class="testimonials-slider__p">' ); ?>
					<?php echo wp_kses( $button, 'post' ); ?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</section>
