<div class="page-header__content">
	<?php
	wld_the_nav(
		'Header Left',
		false,
		array(
			'container'       => 'nav',
			'container_class' => 'menu-header-main menu-header-main_desktop',
			'bam_block_name'  => 'menu-header-main',
		)
	);
	wld_the_nav(
		'Header Tablet Left',
		false,
		array(
			'container'       => 'nav',
			'container_class' => 'menu-header-main menu-header-main_tablet',
			'bam_block_name'  => 'menu-header-main',
		)
	);
	?>
	<?php get_template_part( 'template-parts/logo' ); ?>
	<?php
	wld_the_nav(
		'Header Right',
		false,
		array(
			'container'       => 'nav',
			'container_class' => 'menu-header-main menu-header-main_desktop',
			'bam_block_name'  => 'menu-header-main',
		)
	);
	wld_the_nav(
		'Header Tablet Right',
		false,
		array(
			'container'       => 'nav',
			'container_class' => 'menu-header-main menu-header-main_tablet',
			'bam_block_name'  => 'menu-header-main',
		)
	);
	?>
</div>
