<?php
// Scripts
WLD_Defer_Scripts::add( 'jquery-core' );

WLD_Enqueue_Scripts::enqueue_blocks(
	array(
		'faq' => array(
			'faq-search.js',
			'tabs.js',
			'accordion.js' => array(
				'jquery',
			),
		),
	)
);

// Styles
WLD_Enqueue_Styles::enqueue_blocks(
	array(
		'site-map'                  => array(
			'menu-site-map.css',
		),
		'faq'                       => array(
			'forms.css',
			'accordion.css',
		),
		'real-properties-available' => array(
			'real-properties.css',
			'locations.css',
			'real-estate.css',
		),
		'real-properties-closed'    => array(
			'real-properties.css',
			'locations.css',
			'real-estate.css',
		),
		'om-download'               => array(
			'gravity-forms.css',
		),
		'contact-us-new'            => array(
			'contact-us.css',
		),
	),
	static function ( $css ) {
		if ( is_singular( 'property' ) ) {
			$css .= WLD_Enqueue_Styles::get_css( 'properties-detail.css' );
			$css .= WLD_Enqueue_Styles::get_css( 'gravity-forms.css' );
		}

		return $css;
	}
);

// Preload
WLD_Preload::set_images(
	static function ( array $images ) : array {
		$logo_attachment_id = (int) get_option( 'options_wld_header_logo', 0 );
		if ( $logo_attachment_id ) {
			$images[] = array( 'src' => wp_get_attachment_image_url( $logo_attachment_id, 'full' ) );
		}

		if ( is_front_page() ) {
			$attachment_id = (int) get_post_meta( get_the_ID(), 'content_0_background', true );
			if ( $attachment_id ) {
				$images[] = array(
					'imagesrcset' => wp_get_attachment_image_srcset( $attachment_id, '1920x0' ),
					'imagesizes'  => wp_get_attachment_image_sizes( $attachment_id, '1920x0' ),
				);
			}
		}

		return $images;
	}
);

WLD_Preload::set_scripts(
	static function ( array $scripts ) : array {
		/** @noinspection ClassConstantCanBeUsedInspection */
		if (
			function_exists( 'cky_disable_banner' ) &&
			class_exists( 'CookieYes\Lite\Admin\Modules\Settings\Includes\Settings' ) &&
			false === cky_disable_banner()
		) {
			global $cky_loader;

			$settings = new CookieYes\Lite\Admin\Modules\Settings\Includes\Settings();
			if ( $settings->is_connected() ) {
				$scripts[] = array(
					'version' => false,
					'src'     => $settings->get_script_url(),
				);
			} elseif ( isset( $cky_loader ) ) {
				$cookie_front_path = WP_PLUGIN_DIR . '/cookie-law-info/lite/frontend/';
				$suffix            = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				if ( file_exists( $cookie_front_path . 'js/script' . $suffix . '.js' ) ) {
					$scripts[] = array(
						'version' => $cky_loader->get_version(),
						'src'     => plugin_dir_url( $cookie_front_path . 'class-frontend.php' ) . 'js/script' . $suffix . '.js',
					);
				}
			}
		}

		return $scripts;
	}
);
