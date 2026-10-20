import liveDom from '../libs/live-dom';
import expendMenu from '../libs/expend-menu';

liveDom( '[data-expend-menu]' ).firstShow( function () {
	expendMenu( this );
} );
