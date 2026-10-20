<?php
$posts = get_posts(
	array(
		'posts_per_page' => -1,
		'post_type'      => 'property',
		'tax_query'      => array(
			array(
				'taxonomy' => 'property_status',
				'field'    => 'name',
				'terms'    => 'Closed',
				'operator' => 'NOT IN',
			),
		),
	)
);
?>
<section
	class="real-properties real-properties_available<?php echo wld_get( 'show_filter_type' ) ? '' : ' real-properties_hide_filter_type'; ?>">
	<div class="inner">
		<?php the_title( '<h1 class="screen-reader-text">', '</h1>' ); ?>
		<div class="real-properties__actions">
			<button class="real-properties__clear-btn" type="button"><?php esc_html_e( 'Clear', 'theme' ); ?></button>
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
			<div class="real-properties__filters-selected"></div>
			<div class="real-properties__filters">
				<?php if ( wld_get( 'show_filter_type' ) ) : ?>
					<details class="real-properties__filters-details" data-filter-by="type">
						<summary class="real-properties__filters-summary">
							<span><?php esc_html_e( 'Filter by Type', 'theme' ); ?></span>
							<?php wld_the_svg( 'menu-marker.svg' ); ?>
						</summary>
						<ul class="real-properties__filters-list"></ul>
					</details>
				<?php endif; ?>
				<details class="real-properties__filters-details" data-filter-by="state">
					<summary class="real-properties__filters-summary">
						<span><?php esc_html_e( 'Filter by State', 'theme' ); ?></span>
						<?php wld_the_svg( 'menu-marker.svg' ); ?>
					</summary>
					<ul class="real-properties__filters-list"></ul>
				</details>
			</div>
		</div>
		<div class="real-properties__wrapper">
			<?php if ( $posts ) : ?>
				<div class="real-estate-objects" data-show-filter="<?php wld_the( 'show_filter_type' ); ?>">
					<?php
					foreach ( $posts as $post ) :
						setup_postdata( $post );
						?>
						<?php
						$location          = get_field( 'location' );
						$lat               = $location['lat'] ?? '';
						$lng               = $location['lng'] ?? '';
						$state_term        = get_the_terms( get_the_ID(), 'property_state' )[0]->name ?? '';
						$type_term         = get_the_terms( get_the_ID(), 'property_type' )[0]->name ?? '';
						$status_term_name  = get_the_terms( get_the_ID(), 'property_status' )[0]->name ?? '';
						$status_term_color = get_field( 'marker_color', get_the_terms( get_the_ID(), 'property_status' )[0] );
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
									style="--status-color: <?php echo esc_attr( $status_term_color ); ?>;"><?php echo esc_html( $status_term_name ); ?></span>
								<h2 class="real-estate__title">
									<a
										href="<?php echo esc_url( get_permalink() ); ?>"
										class="real-estate__link">
										<?php the_title(); ?>
									</a>
								</h2>
								<?php if ( wld_get( 'price' ) ) : ?>
									<p class="real-estate__price"><?php echo preg_match( '/^(\d{1,3}(?:[\.,]?\d{3})*)(\.\d{1,2})?$/', wld_get( 'price' ) ) ? '$' : ''; ?><?php wld_the( 'price' ); ?></p>
								<?php endif; ?>
								<ul class="real-estate__ul">
									<?php if ( wld_has( 'area' ) ) : ?>
										<li class="real-estate__li">
											<?php wld_the_svg( 'area-icon.svg' ); ?>
											<?php wld_the( 'area' ); ?>
										</li>
									<?php endif; ?>
									<?php if ( wld_has( 'units' ) ) : ?>
										<li class="real-estate__li">
											<?php wld_the_svg( 'units-icon.svg' ); ?>
											<?php wld_the( 'units' ); ?>
										</li>
									<?php endif; ?>
									<?php if ( $state_term ) : ?>
										<li class="real-estate__li">
											<?php wld_the_svg( 'location-icon-2.svg' ); ?>
											<?php echo esc_html( $state_term ); ?>
										</li>
									<?php endif; ?>
									<?php if ( $type_term ) : ?>
										<li class="real-estate__li">
											<?php wld_the_svg( 'type-icon.svg' ); ?>
											<?php echo esc_html( $type_term ); ?>
										</li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
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
