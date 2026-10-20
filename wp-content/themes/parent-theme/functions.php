<?php
if ( ! class_exists( 'WLD_Theme' ) ) {
	locate_template( 'classes/base/class-wld-theme-base.php', true );
	locate_template( 'classes/class-wld-theme.php', true );
	WLD_Theme::init();
}
