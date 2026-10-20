<dialog id="mobile-menu" class="popup popup_mobile-menu popup_modal" autofocus>
	<div class="popup__wrapper">
		<button class="popup__close-btn">
			<?php wld_the_svg( 'popup-close.svg', 'popup__close-icon' ); ?>
			<span class="popup__close-text"><?php esc_html_e( 'Close Menu', 'theme' ); ?></span>
		</button>
		<div class="popup__content">
			<?php get_template_part( 'template-parts/mobile-menu-content' ); ?>
		</div>
	</div>
</dialog>
