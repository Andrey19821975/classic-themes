<section class="see-available-properties<?php echo wld_get( 'reverse' ) ? ' see-available-properties_reverse' : '';
echo wld_get( 'block_type' ) ? ' see-available-properties_two-image' : ''; ?>">
	<div class="inner">
		<div class="see-available-properties__wrapper">
			<?php if ( get_sub_field( 'block_type' ) && get_sub_field( 'left_image' ) ) : ?>
				<div class="see-available-properties__media">
					<?php
					wld_the(
						'left_image',
						array(
							'mobile'  => array(
								'size'    => '767x0',
								'class'   => 'see-available-properties__img',
								'loading' => 'lazy',
							),
							'desktop' => array(
								'size'  => '576x0',
								'media' => '(min-width:768px)',
							),
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="see-available-properties__media">
					<?php while ( wld_loop( 'slider', '<div class="see-available-properties__slider" data-slider="available-properties">' ) ) : ?>
						<?php wld_the( 'slide', '1250x0', 'see-available-properties__img', '<div class="see-available-properties__item">', array( 'loading' => 'lazy' ) ); ?>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<div class="see-available-properties__content">
				<?php wld_the( 'title', 'see-available-properties__title' ); ?>
				<?php wld_the( 'button', 'btn', '<p class="see-available-properties__p">' ); ?>
			</div>
			<?php if ( get_sub_field( 'block_type' ) && get_sub_field( 'right_image' ) ) : ?>
				<div class="see-available-properties__media">
					<?php
					wld_the(
						'right_image',
						array(
							'mobile'  => array(
								'size'    => '767x0',
								'class'   => 'see-available-properties__img',
								'loading' => 'lazy',
							),
							'desktop' => array(
								'size'  => '576x0',
								'media' => '(min-width:768px)',
							),
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
