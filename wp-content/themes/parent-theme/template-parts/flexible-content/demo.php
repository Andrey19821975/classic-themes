<!--
The section will automatically be id=section-$i so that it can be referenced in a link
-->
<section class="block">
	<div class="inner">
		<?php
		/**
		 * Easy use of the most common fields
		 */
		?>
		<?php wld_the( 'title', 'section-title' ); ?>
		<?php wld_the( 'text' ); ?>
		<?php wld_the( 'image', '100x100' ); ?>
		<?php wld_the( 'background' ); ?>
		<?php wld_the( 'link' ); ?>
		<?php wld_the( 'button', 'btn' ); ?>
		<?php wld_the( 'phone' ); ?>
		<?php wld_the( 'email' ); ?>
		<?php wld_the( 'form' ); ?>
		<?php wld_the( 'map' ); ?>
		<?php wld_the( 'menu' ); ?>

		<hr>

		<?php
		/**
		 * If the field is "repeater" or "flexible_content" then a sub cycle will be set for it
		 *
		 * $wrapper - To wrap elements in one element you can specify in this format
		 */
		?>
		<?php while ( wld_loop( 'items', '<div class="block__items">' ) ) : ?>
			<div class="block__item">
				<?php wld_the( 'title' ); ?>
				<?php wld_the( 'text' ); ?>
			</div>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * If this field is of type "relationship" then the global variable $post will be set
		 *
		 * $wrapper - For more complex element wrapping you can use "%s"
		 */
		?>
		<?php while ( wld_loop( 'posts', '<div class="wrapper"><div class="inner">%s</div></div>' ) ) : ?>
			<div class="block__post">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( '460x320' ); ?>
				</a>
				<h2><?php the_title(); ?></h2>
				<?php wld_the( 'custom_post_field' ); ?>
			</div>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * If the field is of type "link", "wld_contact_link" or value URL then the content will be wrapped in a <a>
		 * If another type then the content will be wrapped in a <div>
		 *
		 * $class - html attribute class
		 * $force - by default, if there is no value, the content will be wrapped in a <div>, this can be disabled
		 * $attrs - you can also add other html attributes for the wrapper the $attrs parameter
		 */
		?>
		<?php while ( wld_wrap( 'link', 'block__link', false, array( 'data-i' => 1 ) ) ) : ?>
			<?php wld_the( 'title' ); ?>
			<?php wld_the( 'text' ); ?>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * Checks if there are non-empty fields
		 *
		 * $selectors_and_maybe_conditional - selectors separated by commas, you can also specify
		 * 'AND' as the first argument, in this case all fields must be filled
		 */
		?>
		<?php if ( wld_has( 'title', 'text' ) ) : ?>
			<div class="block__content">
				<?php wld_the( 'title' ); ?>
				<?php wld_the( 'text' ); ?>
			</div>
		<?php endif; ?>

		<hr>

		<?php
		/**
		 * By default, the first heading on the page will be H1,
		 * all subsequent H2 unless a specific level is configured in the admin.
		 *
		 * Title field supports:
		 * $class
		 * $wrapper
		 */
		wld_the( 'title', 'title', '<div class="block__title">' );
		?>

		<?php
		/**
		 * Often you do not need a tall wysiwyg, its height can be adjusted in the admin panel.
		 *
		 * Text, textarea or wysiwyg fields supports:
		 * $wrapper
		 */
		wld_the( 'text', '<div class="block__text">' );
		?>

		<?php
		/**
		 * Image field supports:
		 * $size - image must always have a size
		 * $attr - @see wp_get_attachment_image() $attr param
		 * $wrapper
		 */
		wld_the( 'image', '460x320', array( 'class' => 'alignright' ), '<div class="block__img">' );
		?>

		<?php
		/**
		 * Picture (group field)
		 */
		$is_eager = 1 === WLD_ACF_Flex_Content::get_index();
		wld_the(
			'background',
			array(
				'mobile'  => array(
					'size'  => '430x0',
					'class' => 'object-fit object-fit-cover',
					'args'  => array(
						'loading'       => $is_eager ? 'eager' : 'lazy',
						'fetchpriority' => $is_eager ? 'high' : 'low',
					),
				),
				'desktop' => array(
					'size'  => '1920x0',
					'media' => '(min-width:430px)',
				),
			),
		);
		?>

		<?php
		/**
		 * Link field supports:
		 * $class
		 * $empty
		 * $wrapper
		 */
		wld_the( 'link', 'more-link', false, '<div class="block__link">' );
		?>

		<?php
		/**
		 * WLD Contact Link field supports:
		 * $class
		 * $wrapper
		 * $attrs
		 */
		wld_the( 'phone', 'phone-link', '<p>', array( 'data-i' => 1 ) );
		?>

		<?php
		/**
		 * Form field supports:
		 * $show_title - whether to show the form header, hide by default
		 * $show_description- whether to show the form description, hide by default
		 * $wrapper
		 */
		wld_the( 'form', true, true, '<div class="block__form">' );
		?>

		<?php
		/**
		 * Google Map field supports:
		 * $marker - marker icon, should be in the "images" folder of the active theme
		 * $attrs
		 */
		wld_the( 'map', 'marker.png', array( 'data-i' => 1 ) );
		?>

		<?php
		/**
		 * Menu field supports:
		 * $args - @see wp_nav_menu() $args param
		 */
		wld_the( 'menu', array( 'container' => 'nav' ) );
		?>

		<?php
		/**
		 * You can pass the object for which you want to get data.
		 * Supported: WP_User, WP_Post, WP_Term, WP_Comment.
		 */
		$user = get_userdata( 1 );
		wld_the( 'test', $user );
		wld_the( 'test', get_post( 1 ) );
		wld_the( 'test', get_term( 1 ) );
		wld_the( 'test', get_comment( 1 ) );

		if ( wld_has( 'test', $user ) ) {
			wld_the( 'test', $user );
		}

		while ( wld_wrap( 'link', '', false, array(), $user ) ) {
			wld_the( 'test', $user );
		}

		while ( wld_loop( 'items', '', $user ) ) {
			wld_the( 'test', $user );
		}
		?>

		<?php
		/**
		 * $path - path to the template, from the theme folder.
		 * $post_id - can be null, false, WP_User, WP_Post, WP_Term or WP_Comment.
		 * - If null, then this is just a call to the standard get_template_part().
		 * - If false, then wld_the function will assume that it is called in the ACF loop, and get_sub_field() will be used.
		 * - If an object, then wld_the functions will use get_field().
		 * $args - an array of variables that will be extract().
		 */
		wld_the_template_part( 'template-parts/flexible-content/demo' );
		?>

		<?php
		$first_slide   = 1 === (int) get_row_index();
		$first_section = 1 === WLD_ACF_Flex_Content::get_index();
		$is_eager      = $first_slide && $first_section;
		$image_id      = (int) get_sub_field( 'background', false );
		$data          = array(
			array(
				'size'  => '1234x0',
				'media' => '(min-width:1234px)',
			),
			array(
				'id'   => $image_id,
				'size' => '123x0',
				'args' => array(
					'class'         => 'object-fit object-fit-cover',
					'loading'       => $is_eager ? 'eager' : 'lazy',
					'fetchpriority' => $is_eager ? 'high' : 'low',
				),
			),
		);

		echo wp_kses(
			WLD_Images::get_picture( $data ),
			WLD_KSES::get_by_picture()
		);
		?>
	</div>
</section>
