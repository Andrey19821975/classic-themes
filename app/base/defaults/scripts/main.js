import domReady from './libs/dom-ready';
import liveDom from './libs/live-dom';

import './hash-reset';
import './links';
import './menus';
import './sticky-header';

domReady( () => {
	liveDom(
		'[href^="#popup-"],' +
			'[href^="https://www.youtube.com/watch?v="],' +
			'[href^="https://vimeo.com/video/"],' +
			'[data-popup-id]'
	).onceInit( () => import( '../../../src/scripts/popups' ) );

	liveDom( '[data-map]' ).onceInit( () =>
		import( '../../../src/scripts/maps' )
	);

	liveDom( '[data-slider]' ).onceInit( () =>
		import( '../../../src/scripts/sliders' )
	);

	liveDom( '.form__item' ).onceInit( () =>
		import( './libs/toggle-focus' ).then( ( { toggleFocus } ) =>
			toggleFocus( '.form__item' )
		)
	);

	liveDom( 'textarea,input' ).onceInit( () =>
		import( './forms/disabled-visual-focus' )
	);

	liveDom( '.accessibility-card' ).onceInit( () => import( './cards' ) );

	liveDom( '.accordion' ).onceInit( () => import( './accordions' ) );

	import( 'instant.page' );
} );
