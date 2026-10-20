<?php
$posts = get_posts(
	array(
		'posts_per_page' => -1,
		'post_type'      => 'property',
		'meta_key'       => 'closing_date',
		'orderby'        => array(
			'meta_value_num' => 'DESC',
			'meta_key'       => 'ASC',
		),
		'tax_query'      => array(
			array(
				'taxonomy' => 'property_status',
				'field'    => 'name',
				'terms'    => 'Closed',
				'operator' => 'IN',
			),
		),
	)
);

?>
<section class="real-properties real-properties_closed real-properties_mode-list">
	<div class="inner">
		<?php the_title( '<h1 class="screen-reader-text">', '</h1>' ); ?>
		<div class="real-properties__actions">
			<div class="real-properties__mode">
				<button class="real-properties__mode-button active" data-mode="list">
					<?php wld_the_svg( 'list-icon.svg' ); ?>
					<?php esc_html_e( 'List', 'theme' ); ?>
				</button>
				<button class="real-properties__mode-button" data-mode="map">
					<?php wld_the_svg( 'location-icon.svg' ); ?>
					<?php esc_html_e( 'Map', 'theme' ); ?>
				</button>
			</div>
		</div>
		<?php $post = get_sub_field( 'recently_closed' ); ?>
		<?php if ( $post ) : ?>
			<div class="real-properties__recently-closed">
				<?php
				setup_postdata( $post );
				$type_term  = get_the_terms( get_the_ID(), 'property_type' )[0]->name ?? '';
				$state_term = get_the_terms( get_the_ID(), 'property_state' )[0]->name ?? '';
				?>
				<?php $gallery = get_field( 'gallery' ); ?>
				<?php if ( $gallery ) : ?>
					<?php $index = 1; ?>
					<div class="real-properties__slider-wrap">
						<div class="real-properties__slider" data-slider="recently-closed">
							<?php foreach ( $gallery as $gallery_id ) : ?>
								<div class="real-properties__item">
									<?php
									echo wp_get_attachment_image(
										$gallery_id,
										'647x0',
										false,
										array(
											'class'   => 'real-properties__img',
											'loading' => ( 1 === $index ) ? 'eager' : 'lazy',
										),
									);
									?>
								</div>
								<?php ++$index; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php else : ?>
					<div class="real-properties__slider-wrap">
						<div class="real-properties__slider" data-slider="recently-closed">
							<div class="real-properties__item">
								<?php
								the_post_thumbnail(
									'647x0',
									array(
										'class'   => 'real-properties__img',
										'loading' => 'lazy',
									),
								);
								?>
							</div>
						</div>
					</div>

				<?php endif; ?>
				<div class="real-properties__content">
					<h2 class="real-properties__title"><?php esc_html_e( 'Recently Closed', 'theme' ); ?></h2>
					<?php the_title( '<h3 class="real-properties__h3">', '</h3>' ); ?>
					<ul class="real-properties__ul">
						<li class="real-properties__li">
							<?php wld_the_svg( 'area-icon.svg' ); ?>
							<?php wld_the( 'area' ); ?>
						</li>
						<li class="real-properties__li">
							<?php wld_the_svg( 'units-icon.svg' ); ?>
							<?php wld_the( 'units' ); ?>
						</li>
						<li class="real-properties__li">
							<svg
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" height="800"
								width="800">
								<path d="M8.956 6v7.944H8V6h.956z" fill="currentColor"/>
								<path
									d="M8.511.016C6.047.016 4.047 2.024 4.047 4.5c0 2.478 2 4.484 4.464 4.484 2.465 0 4.464-2.007 4.464-4.484S10.977.016 8.511.016Zm.159 1.626a3.008 3.008 0 0 0-3.018 2.997c0 .662-.648.346-.648-.645A3.008 3.008 0 0 1 8.022.997c.996 0 1.314.645.648.645Z"
									fill="currentColor"/>
								<path
									d="M10.109 11.156v.926c2.477.248 3.729 1.062 3.729 1.47 0 .509-1.887 1.501-5.344 1.501-3.459 0-5.346-.992-5.346-1.501 0-.438 1.342-1.181 3.758-1.421v-.927c-2.379.211-4.872.938-4.872 2.36 0 1.598 3.249 2.433 6.46 2.433 3.209 0 6.459-.835 6.459-2.433 0-1.462-2.56-2.203-4.844-2.408Z"
									fill="currentColor"/>
							</svg>
							<?php echo esc_html( wld_get( 'city' ) . ', ' . short_state( $state_term ) ); ?>
						</li>
						<li class="real-properties__li">
							<?php wld_the_svg( 'type-icon.svg' ); ?>
							<?php echo esc_html( $type_term ); ?>
						</li>
					</ul>
				</div>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<div class="real-properties__wrapper">
			<?php if ( $posts ) : ?>
				<div class="real-estate-objects">
					<?php
					foreach ( $posts as $post ) :
						setup_postdata( $post );
						if ( ! get_field( 'show_on_top' ) ) :
							$location = get_field( 'location' );
							$lat = $location['lat'] ?? '';
							$lng = $location['lng'] ?? '';
							$state_term = get_the_terms( get_the_ID(), 'property_state' )[0]->name ?? '';
							$type_term = get_the_terms( get_the_ID(), 'property_type' )[0]->name ?? '';
							?>
							<div
								class="real-estate"
								data-latitude="<?php echo esc_attr( $location['lat'] ); ?>"
								data-longitude="<?php echo esc_attr( $location['lng'] ); ?>"
								data-type="<?php echo esc_attr( $type_term ); ?>"
								data-state="<?php echo esc_attr( $state_term ); ?>">
								<div class="real-estate__image">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php
										the_post_thumbnail(
											'281x0',
											array(
												'class'   => 'real-estate__img',
												'loading' => 'lazy',
											),
										);
										?>
									<?php endif; ?>
								</div>
								<div class="real-estate__content">
									<span
										class="real-estate__status"
										style="--status-color: ;"><?php esc_html_e( 'Closed', 'theme' ); ?></span>
									<?php the_title( '<h2 class="real-estate__title">', '</h2>' ); ?>
									<p class="real-estate__price">$<?php wld_the( 'price' ); ?></p>
									<ul class="real-estate__ul">
										<li class="real-estate__li">
											<?php wld_the_svg( 'area-icon.svg' ); ?>
											<?php wld_the( 'area' ); ?>
										</li>
										<li class="real-estate__li">
											<?php wld_the_svg( 'units-icon.svg' ); ?>
											<?php wld_the( 'units' ); ?>
										</li>
										<li class="real-estate__li">
											<?php wld_the_svg( 'location-icon-2.svg' ); ?>
											<?php echo esc_html( $state_term ); ?>
										</li>
										<li class="real-estate__li">
											<?php wld_the_svg( 'type-icon.svg' ); ?>
											<?php echo esc_html( $type_term ); ?>
										</li>
									</ul>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
			<div class="real-properties__map">
				<div class="locations">
					<div class="locations__buttons">
						<button
							class="btn"
							type="button"
							id="draw-btn"><?php esc_html_e( 'Click Here To Draw On Map', 'theme' ); ?></button>
						<button
							class="btn"
							type="button"
							id="clear-btn"><?php esc_html_e( 'Clear Map', 'theme' ); ?></button>
					</div>
					<div class="locations__caption"></div>
					<div
						class="locations__map"
						data-latitude="40.611243698524994"
						data-longitude="-101.6233937638726"
						data-zoom="8"
						data-map="locations"></div>
				</div>
			</div>
		</div>
	</div>
</section>
