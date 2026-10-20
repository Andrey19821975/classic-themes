<?php
/**
 * @var array{
 *     section_index: int,
 *     tab_index: int,
 *     open: boolean
 * } $args
 */
$section_index = $args['section_index'] ?? 0;
$tab_index     = $args['tab_index'] ?? 'all';
$open          = $args['open'] ?? false;
$postfix       = $section_index . '-' . $tab_index . '-' . get_the_ID();
?>
<div
	class="accordion<?php echo $open ? ' accordion_active' : ''; ?>"
	id="<?php echo esc_attr( 'accordion-' . $postfix ); ?>"
>
	<h2 class="accordion__header">
		<button
			class="accordion__trigger"
			id="<?php echo esc_attr( 'accordion-btn-' . $postfix ); ?>"
			type="button"
			aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
			aria-controls="<?php echo esc_attr( 'accordion-panel-' . $postfix ); ?>"
		>
			<span class="accordion__title"><?php the_title(); ?></span>
		</button>
	</h2>
	<div
		class="accordion__panel"
		id="<?php echo esc_attr( 'accordion-panel-' . $postfix ); ?>"
		role="region"
		aria-labelledby="<?php echo esc_attr( 'accordion-btn-' . $postfix ); ?>"
		<?php echo $open ? '' : 'hidden'; ?>
	>
		<?php the_content(); ?>
	</div>
</div>
