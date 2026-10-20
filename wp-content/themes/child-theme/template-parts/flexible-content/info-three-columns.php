<section class="info-three-columns" id="our-process-<?php echo esc_attr( get_row_index() ); ?>">
	<div class="inner">
		<?php wld_the( 'title', 'title' ); ?>
		<?php while ( wld_loop( 'columns', '<div class="info-three-columns__wrapper">' ) ) : ?>
			<div class="info-three-columns__item">
				<div class="info-three-columns__heading">
					<?php wld_the( 'icon', '60x60', 'info-three-columns__img' ); ?>
					<?php wld_the( 'title', 'info-three-columns__h3' ); ?>
				</div>
				<div class="info-three-columns__content" data-controls="read-more">
					<?php the_sub_field( 'text' ); ?>
					<?php if ( has_shortcode( get_sub_field( 'text', false, false ), 'learn-more' ) ) : ?>
						<div class="read-more read-more_hide">
							<button class="read-more__btn" type="button">
								<?php esc_html_e( 'Show Less', 'theme' ); ?>
								<svg
									aria-hidden="true"
									width="16" height="16" viewBox="0 0 16 16" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M5.72668 4.94L8.78002 8L5.72668 11.06L6.66668 12L10.6667 8L6.66668 4L5.72668 4.94Z"
										fill="currentColor"/>
								</svg>
							</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</section>
