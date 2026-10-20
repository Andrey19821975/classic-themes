<section class="about-process">
	<div class="inner">
		<?php while ( wld_loop( 'items', '<div class="about-process__wrapper">' ) ) : ?>
			<div class="about-process__item">
				<?php wld_the( 'image', '697x0' ); ?>
				<?php wld_the( 'title', 'about-process__title' ); ?>
				<?php wld_the( 'button', 'btn', '<p class="about-process__p">' ); ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
