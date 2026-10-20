<?php
$section_index = get_row_index();
$faq_terms     = get_terms(
	array(
		'taxonomy' => 'faq_category',
	)
);
?>
<section class="faq">
	<div class="inner">
		<?php wld_the( 'title', 'title' ); ?>
		<?php get_template_part( 'template-parts/faq/form' ); ?>
		<?php if ( $faq_terms ) : ?>
			<theme-tabs class="faq__tabs" all="<?php esc_attr_e( 'All', 'theme' ); ?>">
				<?php foreach ( $faq_terms as $tab_index => $faq_term ) : ?>
					<?php
					$faq_posts = get_posts(
						array(
							'numberposts' => - 1,
							'post_type'   => 'faq',
							'tax_query'   => array(
								array(
									'taxonomy' => 'faq_category',
									'field'    => 'slug',
									'terms'    => $faq_term->slug,
								),
							),
						),
					);
					?>
					<theme-tabs-button class="faq__tabs-button">
						<?php echo esc_html( $faq_term->name ); ?>
					</theme-tabs-button>
					<theme-tabs-content class="faq__tabs-content">
						<?php if ( wld_get( 'display_faq_headers' ) ) : ?>
							<?php echo '<div class="faq__tabs-container">'; ?>
							<?php echo '<h2 class="faq__tabs-title">' . esc_html( $faq_term->name ) . '</h2>'; ?>
						<?php endif; ?>
						<?php while ( wld_loop( $faq_posts ) ) : ?>
							<?php
							get_template_part(
								'template-parts/faq/accordion',
								null,
								array(
									'section_index' => $section_index,
									'tab_index'     => $tab_index + 1,
								)
							);
							?>
						<?php endwhile; ?>
						<?php if ( wld_get( 'display_faq_headers' ) ) : ?>
							<?php echo '</div>'; ?>
						<?php endif; ?>
					</theme-tabs-content>
				<?php endforeach; ?>
			</theme-tabs>
		<?php else : ?>
			<?php
			$faq_posts = get_posts(
				array(
					'numberposts' => - 1,
					'post_type'   => 'faq',
				),
			);
			?>
			<div class="faq__tabs">
				<div class="faq__accordions">
					<?php while ( wld_loop( $faq_posts ) ) : ?>
						<?php
						get_template_part(
							'template-parts/faq/accordion',
							null,
							array(
								'section_index' => $section_index,
							)
						);
						?>
					<?php endwhile; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
