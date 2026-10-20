import { toggleFocus } from '../../base/defaults/scripts/libs/toggle-focus';
import liveDom from '../../base/defaults/scripts/libs/live-dom';
import TomSelect from 'tom-select';
import '../../base/defaults/scripts/npm-proxy/tom-select/scss/tom-select.default.scss';

toggleFocus( '.gfield' );
toggleFocus( 'span', '.ginput_complex' );
toggleFocus( '.ginput_product_price_wrapper' );

liveDom( 'select.gfield_select' ).init( function () {
	const select = new TomSelect( this, {
		controlInput: null,
	} );

	const toggleFocusTomSelect = function () {
		this.wrapper
			.closest( '.gfield' )
			.classList.add( 'gfield_in-focus-or-has-value' );
	};

	select.on( 'item_add', toggleFocusTomSelect );
} );
liveDom('#link-file').init(function (  ) {
	this.click();
});

