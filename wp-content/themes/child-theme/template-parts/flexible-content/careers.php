<section class="careers">
	<div class="inner">
		<div class="careers__wrapper">
			<div class="careers__left">
				<?php wld_the( 'title', 'careers__title' ); ?>
				<?php while ( wld_loop( 'jobs' ) ) : ?>
					<div class="careers__item">
						<div class="careers__text">
							<?php if ( wld_get( 'status' ) ) : ?>
								<span
									class="careers__status careers__status_open"><?php esc_html_e( 'Currently open', 'theme' ); ?></span>
							<?php else : ?>
								<span
									class="careers__status careers__status_closed"><?php esc_html_e( 'Currently closed', 'theme' ); ?></span>
							<?php endif; ?>
							<?php wld_the( 'title', 'careers__sub-title' ); ?>
							<?php wld_the( 'subtitle', '<p class="careers__p">' ); ?>
						</div>
						<?php wld_the( 'button', 'btn', '<div class="careers__btn">' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="careers__right">
				<?php wld_the( 'text', '<div class="careers__content">' ); ?>
			</div>
		</div>
	</div>
</section>
