<section class="thank-you">
	<div class="inner">
		<?php if ( wld_has( 'title', 'text' ) ) : ?>
			<div class="thank-you__wrapper">
				<?php wld_the( 'title', 'thank-you__title' ); ?>
				<?php wld_the( 'text' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( wld_has( 'social_links_enabled', 'wld_social_links' ) ) : ?>
			<div class="thank-you__wrapper-social-links">
				<?php wld_the( 'social_links_title', 'thank-you__subtitle' ); ?>
				<?php get_template_part( 'template-parts/social-links', null, array( 'class' => 'social-links_color' ) ); ?>
			</div>
		<?php endif; ?>
		<?php while ( wld_loop( 'buttons', '<div class="thank-you__wrapper-buttons">' ) ) : ?>
			<?php wld_the( 'button', 'thank-you__button btn' ); ?>
		<?php endwhile; ?>
	</div>
</section>
