<section class="contact-us">
	<h1 class="screen-reader-text"><?php the_title(); ?></h1>
	<div class="inner">
		<?php while ( wld_loop( 'team', '<div class="contact-us__wrapper">' ) ) : ?>
			<div class="contact-us__person">
				<?php wld_the( 'image', '233x0', 'contact-us__img', '<div class="contact-us__image">' ); ?>
				<div class="contact-us__content">
					<?php wld_the( 'name', 'contact-us__name' ); ?>
					<div
						class="contact-us__text"><?php esc_html_e( 'Click to reveal email and phone', 'theme' ); ?></div>
					<?php while ( wld_loop( 'contacts', '<div class="contact-us__contacts">' ) ) : ?>
						<p class="contact-us__p"><?php echo esc_html__( 'Cell:', 'theme' ) . ' ' . wld_get( 'phone', 'contact-us__a' ); ?></p>
						<?php wld_the( 'email', 'contact-us__a', '<p class="contact-us__p">' ); ?>
					<?php endwhile; ?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</section>
