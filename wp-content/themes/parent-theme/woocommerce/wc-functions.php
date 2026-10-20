<?php
WLD_WC_ACF_Location::init();
WLD_WC_Loop_Base::init();
WLD_WC_Cart::init();
WLD_WC_Cart_In_Menu::init();
WLD_WC_Checkout_Blocks::init();
WLD_WC_Fields::init();
WLD_WC_Global::init();
WLD_WC_Image_Sizes::init();
WLD_WC_Images::init();
WLD_WC_My_Account::init();
WLD_WC_Quantity::init();
WLD_WC_Sidebar::init();
WLD_WC_Product_Reviews::init();
WLD_WC_Product_Reviews_Share::init();
WLD_WC_Product_Reviews_Vote::init();

WLD_Defer_Scripts::add( 'jquery-blockui' );
WLD_Defer_Scripts::add( 'wc-add-to-cart' );
WLD_Defer_Scripts::add( 'js-cookie' );
WLD_Defer_Scripts::add( 'woocommerce' );
WLD_Defer_Scripts::add( 'wc-cart-fragments' );

add_filter(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
);

add_filter(
	'woocommerce_single_product_image_thumbnail_nav_html',
	static function ( $html ) {
		return wp_kses(
			$html,
			array(
				'a'   => array(
					'href' => true,
				),
				'img' => array(
					'width'                   => true,
					'height'                  => true,
					'src'                     => true,
					'class'                   => true,
					'alt'                     => true,
					'decoding'                => true,
					'loading'                 => true,
					'title'                   => true,
					'data-caption'            => true,
					'data-src'                => true,
					'data-large_image'        => true,
					'data-large_image_width'  => true,
					'data-large_image_height' => true,
					'srcset'                  => true,
					'sizes'                   => true,
				),
			),
		);
	},
);
