<section class="blog-articles" id="blog-articles">
	<div class="inner">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts', 'theme' ); ?></h2>
		<div class="blog-articles__wrapper">
			<div class="blog-articles__content">
				<?php if ( have_posts() ) : ?>
					<ul class="blog-articles__items">
						<?php while ( have_posts() ) : ?>
							<?php the_post(); ?>
							<?php get_template_part( 'template-parts/blog/blog-item' ); ?>
						<?php endwhile; ?>
					</ul>
					<?php get_template_part( 'template-parts/pagination' ); ?>
				<?php else : ?>
					<p><?php esc_html_e( 'Nothing found', 'theme' ); ?></p>
				<?php endif; ?>
				<?php
				get_template_part(
					'template-parts/blog/blog-newsletter',
					null,
					array(
						'selector' => 'wld_blog_newsletter_bottom',
						'class'    => 'blog-newsletter_bottom',
					)
				);
				?>
			</div>
			<?php get_template_part( 'template-parts/blog/blog-sidebar' ); ?>
		</div>
	</div>
</section>
