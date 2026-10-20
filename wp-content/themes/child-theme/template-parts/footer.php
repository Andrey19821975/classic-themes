<footer class="page-footer" data-visibility="page-footer">
	<div class="inner">
		<div class="page-footer__left">
			<div class="page-footer__content">
				<?php while ( wld_loop( 'wld_footer_contacts', '<div class="page-footer__contacts">' ) ) : ?>
					<?php while ( wld_loop( 'location' ) ) : ?>
						<?php wld_the( 'title', 'page-footer__h3' ); ?>
						<?php wld_the( 'address', '<p class="page-footer__p">' ); ?>
					<?php endwhile; ?>
					<?php while ( wld_loop( 'phone' ) ) : ?>
						<?php wld_the( 'title', 'page-footer__h3' ); ?>
						<?php wld_the( 'number', 'page-footer__a', '<p class="page-footer__p">' ); ?>
					<?php endwhile; ?>
				<?php endwhile; ?>
				<?php wld_the_template_part( 'template-parts/social-links', array( 'class' => 'social-links_toggle social-links_outline social-links_size_30' ) ); ?>
				<?php wld_the( 'wld_footer_mobile_text', '<p class="page-footer__p hide-on-desktop">' ); ?>
				<?php while ( wld_loop( 'wld_footer_left_buttons', '<div class="page-footer__links">' ) ) : ?>
					<?php wld_the( 'button', 'btn' ); ?>
				<?php endwhile; ?>
			</div>
			<?php wld_the( 'wld_footer_left_image', '257x0', 'page-footer__img', '<div class="page-footer__image">' ); ?>
		</div>
		<?php while ( wld_loop( 'wld_footer_mobile_buttons', '<div class="page-footer__links hide-on-desktop">' ) ) : ?>
			<?php wld_the( 'button', 'btn' ); ?>
		<?php endwhile; ?>
		<div class="page-footer__wrapper">
			<?php while ( wld_loop( 'wld_footer_licenses_block' ) ) : ?>
				<?php wld_the( 'title', 'page-footer__h3' ); ?>
				<?php while ( wld_loop( 'licenses', '<ul class="page-footer__ul">' ) ) : ?>
					<li class="page-footer__li">
						<span><?php wld_the( 'text' ); ?></span>
						<?php wld_the( 'link', 'page-footer__a' ); ?>
					</li>
				<?php endwhile; ?>
			<?php endwhile; ?>
			<?php get_template_part( 'template-parts/logo' ); ?>
		</div>
		<div class="page-footer__right">
			<?php wld_the( 'wld_footer_right_image', '257x0', 'page-footer__img', '<div class="page-footer__image">' ); ?>

			<div class="page-footer__content">
				<?php wld_the( 'wld_right_desktop_text', '<p class="page-footer__p">' ); ?>
				<?php while ( wld_loop( 'wld_footer_right_buttons', '<div class="page-footer__links">' ) ) : ?>
					<?php wld_the( 'button', 'btn' ); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</footer>
