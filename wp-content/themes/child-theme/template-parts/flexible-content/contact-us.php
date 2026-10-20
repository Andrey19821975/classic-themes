<?php
$all_types = get_terms(
	array(
		'taxonomy'   => 'property_type',
		'hide_empty' => false,
	)
);
?>
<section class="contact-us<?php echo wld_get( 'show_buttons' ) ? '' : ' contact-us_hide_buttons'; ?>">
	<div class="inner">
		<?php the_title( '<h1 class="screen-reader-text">', '</h1>' ); ?>
		<?php if ( wld_get( 'show_buttons' ) ) : ?>
			<div class="contact-us__buttons">
				<?php foreach ( $all_types as $one_type ) : ?>
					<button
						class="btn"
						type="button"
						data-type="<?php echo esc_attr( $one_type->name ); ?>">
						<?php echo esc_html( $one_type->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="contact-us__wrapper">
			<?php wld_the( 'description', '<div class="contact-us__description contact-us__description_mobile">' ); ?>
			<div
				class="contact-us__map"
				data-latitude="33.57490144551746"
				data-longitude="-94.24964527517238"
				data-zoom="5"
				data-map="contact-us">
			</div>
			<div class="contact-us__content">
				<?php wld_the( 'description', '<div class="contact-us__description contact-us__description_desktop">' ); ?>
				<?php while ( wld_loop( 'members' ) ) : ?>
					<?php $property_type = get_sub_field( 'type' ) ? get_sub_field( 'type' )->name : ''; ?>
					<?php
					$area_arr = array();
					switch ( $property_type ) {
						case 'Self Storage':
							$area_arr = get_sub_field( 'area_self_storage' );
							break;
						case 'Manufactured Housing':
							$area_arr = get_sub_field( 'area_manufactured_housing' );
							break;
						case 'Multifamily':
							$area_arr = get_sub_field( 'area_multifamily' );
							break;
					}
					$area = '';
					if ( ! empty( $area_arr ) ) {
						$area = implode( ', ', $area_arr );
					}
					?>
					<div
						class="contact-us__item"
						data-type="<?php echo esc_attr( $property_type ); ?>"
						data-area="<?php echo esc_html( $area ); ?>">
						<h2 class="contact-us__title"><?php echo esc_html( $area ); ?></h2>
						<?php wld_the( 'image', '348x0', 'contact-us__img', '<div class="contact-us__image">' ); ?>
						<div class="contact-us__text">
							<?php wld_the( 'name', 'contact-us__h3' ); ?>
							<?php wld_the( 'position', 'contact-us__h4' ); ?>
							<?php if ( $property_type ) : ?>
								<p class="contact-us__p"><?php echo esc_html( $property_type ); ?></p>
							<?php endif; ?>
							<?php while ( wld_loop( 'contacts', '<div class="contact-us__contacts">' ) ) : ?>
								<p class="contact-us__p"><?php echo esc_html__( 'Office:', 'theme' ) . ' ' . wld_get( 'office', 'contact-us__a' ); ?></p>
								<p class="contact-us__p"><?php echo esc_html__( 'Cell:', 'theme' ) . ' ' . wld_get( 'cell', 'contact-us__a' ); ?></p>
								<?php wld_the( 'email', 'contact-us__a', '<p class="contact-us__p">' ); ?>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</section>
