<?php
$om_file   = false;
$back_url = "#";
if ( isset( $_GET['property_id'] ) ) {
	$om_file  = get_field( 'file', $_GET['property_id'] );
	$back_url = get_permalink( $_GET['property_id'] );
}
?>

<section class="thank-you">
	<div class="inner">
		<?php if ( wld_has( 'title', 'text' ) ) : ?>
			<div class="thank-you__wrapper">
				<?php wld_the( 'title', 'thank-you__title' ); ?>
				<?php if ( $om_file ) : ?>
					<p class="thank-you__p">
						<?php esc_html_e( 'Your download will start in few seconds...If the download does not start automatically click ', 'theme' ); ?>
						<a id="link-file"
						   href="<?php echo esc_url( $om_file ); ?>"><?php esc_html_e( 'here', 'theme' ); ?></a>
					</p>
					<script>
						setTimeout( () => {
								document.getElementById( 'link-file' ).click();
							},
							400 );
					</script>
				<?php else : ?>
					<?php wld_the( 'text' ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( wld_has( 'social_links_enabled', 'wld_social_links' ) ) : ?>
			<div class="thank-you__wrapper-social-links">
				<?php wld_the( 'social_links_title', 'thank-you__subtitle' ); ?>
				<?php wld_the_template_part( 'template-parts/social-links', array( 'class' => 'social-links_color social-links_size_50 social-links_gap_20' ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( $om_file ) : ?>
			<div class="thank-you__wrapper-buttons">
				<a href="<?php echo esc_url( $back_url ); ?>"
				   class="thank-you__button btn"><?php esc_html_e( 'Back to Property', 'theme' ); ?></a>
			</div>
		<?php else : ?>
			<?php while ( wld_loop( 'buttons', '<div class="thank-you__wrapper-buttons">' ) ) : ?>
				<?php wld_the( 'button', 'thank-you__button btn' ); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</section>
