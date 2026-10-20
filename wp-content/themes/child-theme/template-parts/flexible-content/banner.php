<section class="banner<?php echo wld_get( 'show_links' ) ? '' : ' banner_hide-links'; ?>">
	<div class="inner">
		<div class="banner__wrapper">
			<?php if ( wld_get( 'show_links' ) ) : ?>
				<?php while ( wld_loop( 'links', '<div class="banner__links">' ) ) : ?>
					<div class="banner__item">
						<?php while ( wld_wrap( 'link', 'banner__link' ) ) : ?>
							<?php wld_the( 'image', '205x0', 'banner__img' ); ?>
						<?php endwhile; ?>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
			<div class="banner__content">
				<?php if ( get_sub_field( 'video' ) ) : ?>
					<video
						class="object-fit object-fit-cover"
						crossorigin="anonymous"
						playsinline=""
						preload="auto"
						muted=""
						loop=""
						tabindex="-1"
						autoplay=""
						src="<?php echo esc_url( get_sub_field( 'video' ) ); ?>">
					</video>
				<?php endif; ?>
				<?php wld_the( 'top_title', '<p class="banner__p">' ); ?>
				<?php wld_the( 'title', 'banner__title' ); ?>
				<?php wld_the( 'button', 'btn', '<p class="banner__p">' ); ?>
			</div>
		</div>
	</div>
</section>
