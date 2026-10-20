<?php
/**
 * @var array{
 *     is_first: bool
 * } $args
 */
?>
<a class="blog-preview__item<?php echo $args['is_first'] ? ' blog-preview__item_first' : ''; ?>" href="<?php the_permalink(); ?>">
	<div class="blog-preview__image">
		<?php
		the_post_thumbnail(
			'671x0',
			array(
				'class' => 'object-fit object-fit-cover',
			)
		);
		?>
	</div>
	<div class="blog-preview__content">
		<p class="blog-preview__details">
			<span class="blog-preview__date"><?php echo get_the_date( 'F j, Y' ); ?></span>
			<span>|</span>
			<span class="blog-preview__category">
				<?php echo wp_kses( get_the_category_list( ' | ' ), 'strip' ); ?>
			</span>
		</p>
		<?php the_title( '<h2 class="blog-preview__sub-title">', '</h2>' ); ?>
	</div>
</a>
