<?php
$categories_tags = new WP_HTML_Tag_Processor( get_the_category_list( ' ' ) );
while ( $categories_tags->next_tag() ) {
	if ( 'A' === $categories_tags->get_tag() ) {
		$categories_tags->add_class( 'blog-article-categories__link' );
	}
}
?>
<section class="blog-post">
	<div class="inner">
		<div class="blog-post__wrapper">
			<div class="blog-post__left">
				<?php get_template_part( 'template-parts/blog/back-link' ); ?>
				<section class="blog-post__item">
					<?php the_title( '<h1 class="blog-post__title">', '</h1>' ); ?>
					<div class="blog-post__posted"><?php echo get_the_date( 'F j, Y' ); ?></div>
					<div class="blog-post__thumbnail">
						<?php
						the_post_thumbnail(
							'800x400',
							array(
								'class' => 'blog-post__image',
							),
						);
						?>
					</div>
					<div class="blog-article-categories blog-post__categories" aria-label="Categories">
						<?php echo wp_kses( $categories_tags->get_updated_html(), WLD_KSES::get_by_tag( 'a' ) ); ?>
					</div>
				</section>
				<section class="blog-post-content">
					<div class="blog-post-content__content">
						<?php WLD_BAM_Content::the_content( 'blog-post-content' ); ?>
					</div>
					<?php get_template_part( 'template-parts/blog/blog-back-link' ); ?>
					<?php get_template_part( 'template-parts/blog/blog-share-post' ); ?>
				</section>
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
			<div class="blog-post__right">
				<?php get_template_part( 'template-parts/blog/blog-sidebar' ); ?>
			</div>
		</div>
	</div>
</section>
