<section class="about">
	<div class="inner">
		<div class="about__wrapper">
			<?php while ( wld_loop( 'left_side', '<div class="about__left">' ) ) : ?>
				<?php wld_the( 'title', 'title' ); ?>
				<?php wld_the( 'text' ); ?>
			<?php endwhile; ?>
			<?php while ( wld_loop( 'right_side', '<div class="about__right">' ) ) : ?>
				<?php wld_the( 'title', 'about__h2' ); ?>
				<?php wld_the( 'text' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
