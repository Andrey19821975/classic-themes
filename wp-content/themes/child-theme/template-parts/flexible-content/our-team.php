<section class="our-team">
	<div class="inner">
		<?php while ( wld_loop( 'team', '<div class="our-team__wrapper">' ) ) : ?>
			<?php $index = get_row_index(); ?>
			<div class="our-team__item">
				<?php wld_the( 'image', '497x0', 'our-team__img', '<div class="our-team__image">' ); ?>
				<?php wld_the( 'name', 'our-team__title' ); ?>
				<?php wld_the( 'position', '<p class="our-team__position">' ); ?>
				<?php wld_the( 'excerpt', '<p class="our-team__p">' ); ?>
				<p class="our-team__p">
					<button
						class="our-team__read-more"
						type="button"
						data-popup-id="our-team-<?php echo esc_attr( $index ); ?>">
						<?php esc_html_e( 'Learn More', 'theme' ); ?>
						<?php wld_the_svg( 'link-arrow.svg' ); ?>
					</button>
				</p>
				<dialog
					id="our-team-<?php echo esc_attr( $index ); ?>"
					class="popup popup_our-team popup_base popup_inline" autofocus>
					<div class="popup__wrapper">
						<button class="popup__close-btn">
							<?php wld_the_svg( 'popup-close.svg', 'popup__close-icon' ); ?>
							<span class="popup__close-text"><?php esc_html_e( 'Close', 'theme' ); ?></span>
						</button>
						<div class="popup__content">
							<div class="our-team-popup">
								<?php wld_the( 'image', '440x0', 'our-team-popup__img', '<div class="our-team-popup__image">' ); ?>
								<div class="our-team-popup__content">
									<?php wld_the( 'name', 'our-team-popup__title' ); ?>
									<?php wld_the( 'position', '<p class="our-team-popup__position">' ); ?>
									<?php wld_the( 'description', '<p class="our-team-popup__p">' ); ?>
								</div>
							</div>
						</div>
					</div>
				</dialog>
			</div>
		<?php endwhile; ?>
</section>
