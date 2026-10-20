import liveDom from '../libs/live-dom';
import hoverMenu from '../libs/hover-menu';

liveDom( '[data-hover-menu]' ).firstShow( function () {
	hoverMenu( this );
} );
