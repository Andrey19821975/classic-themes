import TomSelect from '../npm-proxy/tom-select.js';
import liveDom from '../libs/live-dom.js';

// You must always specify SELECT, otherwise after TomSelect has worked, a DIV with the same class will be created and
// liveDom will work again on the div, which will cause an error in the console.
liveDom( 'select.example-select__select_simple', {
	init() {
		new TomSelect( this );
	},
} );

liveDom( 'select.example-select__select_checkboxes', {
	init() {
		new TomSelect( this, {
			plugins: [ 'checkbox_options' ],
		} );
	},
} );
