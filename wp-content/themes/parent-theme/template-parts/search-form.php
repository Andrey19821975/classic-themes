<?php while ( wld_loop( 'wld_search_form' ) ) : ?>
	<section class="search-form search-form_search-results-page">
		<div class="search-form__wrapper inner">
			<?php wld_the( 'background', '1170x0', array( 'class' => 'search-form__image' ) ); ?>
			<?php wld_the( 'search_form_title', 'search-form__title' ); ?>
			<form class="search-form__form" role="search" action="<?php echo esc_url( home_url() ); ?>">
				<label class="search-form__label">
					<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'theme' ); ?></span>
					<input
						class="search-form__input"
						type="search"
						placeholder="<?php esc_attr_e( 'Type your question', 'theme' ); ?>"
						value=""
						name="s">
				</label>
				<input class="search-form__submit" type="submit" value="<?php esc_attr_e( 'Search', 'theme' ); ?>">
			</form>
		</div>
	</section>
<?php endwhile; ?>
