<?php
get_header();
$location          = get_field( 'location' );
$zoom              = $location['zoom'] ?? 6;
$lat               = $location['lat'] ?? '';
$lng               = $location['lng'] ?? '';
$address           = $location['address'];
$state_term        = get_the_terms( get_the_ID(), 'property_state' )[0]->name ?? '';
$type_term         = get_the_terms( get_the_ID(), 'property_type' )[0]->name ?? '';
$status_term_name  = get_the_terms( get_the_ID(), 'property_status' )[0]->name ?? '';
$status_term_color = get_field( 'marker_color', get_the_terms( get_the_ID(), 'property_status' )[0] );

?>
	<section class="properties-detail">
		<div class="inner">
			<div class="properties-detail__heading">
				<?php the_title( '<h1 class="properties-detail__title">', '</h1>' ); ?>
				<?php if ( 'closed' !== strtolower( $status_term_name ) ) : ?>
					<p class="properties-detail__p">
						<a class="btn"
						   href="#popup-download-om"><?php esc_html_e( 'Download OM', 'theme' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>
			<?php $gallery = get_field( 'gallery' ); ?>
			<?php if ( $gallery ) : ?>
				<?php $index = 1; ?>
				<div class="properties-detail__slider">
					<div class="properties-detail__items" data-slider="properties-detail">
						<?php foreach ( $gallery as $gallery_id ) : ?>
							<div class="properties-detail__item">
								<?php
								echo wp_get_attachment_image(
									$gallery_id,
									'1398x0',
									false,
									array(
										'class'   => 'properties-detail__img',
										'loading' => ( 1 === $index ) ? 'eager' : 'lazy',
									),
								);
								?>
							</div>
							<?php ++$index; ?>
						<?php endforeach; ?>
					</div>
					<ul class="properties-detail__slider-thumbnails">
						<?php foreach ( $gallery as $gallery_id ) : ?>
							<li class="properties-detail__thumbnails">
								<?php
								echo wp_get_attachment_image(
									$gallery_id,
									'80x80',
									false,
									array(
										'class'   => 'properties-detail__img',
										'loading' => 'lazy',
									),
								);
								?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="properties-detail__wrapper">
				<div class="properties-detail__content">
					<span
						class="properties-detail__status"
						style="--status-color: <?php echo esc_attr( $status_term_color ); ?>"><?php echo esc_html( $status_term_name ); ?></span>
					<?php if ( wld_get( 'price' ) ) : ?>
						<p class="properties-detail__price">$<?php wld_the( 'price' ); ?></p>
					<?php endif; ?>
					<ul class="properties-detail__details">
						<li class="properties-detail__li">
							<?php wld_the_svg( 'area-icon-2.svg' ); ?>
							<?php wld_the( 'area' ); ?>
						</li>
						<li class="properties-detail__li">
							<?php wld_the_svg( 'units-icon.svg' ); ?>
							<?php wld_the( 'units' ); ?>
						</li>
						<li class="properties-detail__li">
							<?php wld_the_svg( 'location-marker.svg' ); ?>
							<?php echo esc_html( $address ); ?>
						</li>
						<li class="properties-detail__li"><!-- location-marker.svg -->
							<?php wld_the_svg( 'location-marker.svg' ); ?>
							<?php echo esc_html( wld_get( 'city' ) . ', ' . short_state( $state_term ) ); ?>
						</li>
						<li class="properties-detail__li">
							<?php wld_the_svg( 'type-icon.svg' ); ?>
							<?php echo esc_html( $type_term ); ?>
						</li>
					</ul>
					<?php if ( 'closed' !== strtolower( $status_term_name ) ) : ?>
						<p class="properties-detail__p">
							<a
								class="btn"
								href="#popup-download-om"><?php esc_html_e( 'Download OM', 'theme' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>
				<div class="properties-detail__map-wrap">
					<div
						class="properties-detail__map"
						data-latitude="<?php echo esc_attr( $lat ); ?>"
						data-longitude="<?php echo esc_attr( $lng ); ?>"
						data-zoom="<?php echo esc_attr( $zoom ); ?>"
						data-map="properties-detail"></div>
				</div>
			</div>
			<?php WLD_BAM_Content::set_block_name( 'properties-detail' ); ?>
			<?php the_content(); ?>
			<?php WLD_BAM_Content::remove_block_name(); ?>
		</div>
	</section>
	<dialog id="popup-download-om" class="popup popup_download-om popup_base popup_inline" autofocus>
		<div class="popup__wrapper">
			<button class="popup__close-btn">
				<?php wld_the_svg( 'popup-close.svg', 'popup__close-icon' ); ?>
				<span class="popup__close-text"><?php esc_html_e( 'Close', 'theme' ); ?></span>
			</button>
			<div class="popup__content">
				<?php wld_the( 'wld_properties_form_title', 'popup__title' ); ?>
				<?php wld_the( 'wld_properties_form' ); ?>
			</div>
		</div>
	</dialog>
<?php
get_footer();
