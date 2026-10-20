import liveDom from './libs/live-dom';

liveDom( '[data-expend-menu]' ).firstShow( () =>
	import( './menus/expend-menu' )
);
liveDom( '[data-hover-menu]' ).firstShow( () =>
	import( './menus/hover-menu' )
);
liveDom( '[data-keyboard-menu]' ).firstShow( () =>
	import( './menus/keyboard-menu' )
);
