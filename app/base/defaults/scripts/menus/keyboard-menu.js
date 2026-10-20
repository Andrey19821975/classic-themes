import liveDom from '../libs/live-dom';
import keyboardMenu from '../libs/keyboard-menu';

liveDom( '[data-keyboard-menu]' ).firstShow( function () {
	keyboardMenu( this );
} );
