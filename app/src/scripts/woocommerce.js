import liveDom from '../../base/defaults/scripts/libs/live-dom';
import domReady from '../../base/defaults/scripts/libs/dom-ready';
import { tinySlider } from '../../base/defaults/scripts/libs/tiny-slider';
import '../../base/woocommerce/scripts/jquery/light-tabs';
import '../../base/woocommerce/scripts/jquery/add-to-cart';
import '../../base/woocommerce/scripts/jquery/checkout-blocks';
import '../../base/woocommerce/scripts/jquery/fix-woo-scroll-to-notice';
import '../../base/woocommerce/scripts/jquery/quantity';
import '../../base/woocommerce/scripts/jquery/product';
import '../../base/woocommerce/scripts/jquery/shop';
import '../../base/woocommerce/scripts/menu-cart';

domReady( () => {
	liveDom( 'form.cart' ).init( function () {
		jQuery( this ).wldAddToCart();
	} );

	jQuery( '.section-auth' ).lightTabs();
	jQuery( '.checkout .block' ).wldCheckoutBlocks();

	function toggleFocus( $input ) {
		$input
			.closest( '.form-row' )
			.toggleClass( 'in-focus-or-has-value', !! $input.val() );
	}

	jQuery( document )
		.on(
			'focus',
			'.form-row input, .form-row textarea, .form-row select',
			function () {
				jQuery( this )
					.closest( '.form-row' )
					.addClass( 'in-focus-or-has-value' );
			}
		)
		.on(
			'blur',
			'.form-row input, .form-row textarea, .form-row select',
			function () {
				toggleFocus( jQuery( this ) );
			}
		);

	jQuery( window ).on( 'load', () => {
		jQuery( '.form-row :input' ).each( function () {
			toggleFocus( jQuery( this ) );
		} );
	} );

	liveDom( '.woocommerce-section .close' ).init( function () {
		$( this ).on( 'click', function () {
			jQuery( this ).closest( 'div' ).hide();
		} );
	} );
} );

tinySlider( '[data-slider="related-products"] .products', {
	items: 1,
	autoplay: true,
	autoplayTimeout: 5000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: false,
	navPosition: 'bottom',
	navAsThumbnails: true,
	swipeAngle: false,
	responsive: {
		412: {
			items: 2,
		},
		568: {
			items: 3,
		},
		991: {
			items: 4,
		},
		1200: {
			items: 5,
		},
	},
} );
