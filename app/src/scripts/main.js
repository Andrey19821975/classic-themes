import domReady from '../../base/defaults/scripts/libs/dom-ready';
import liveDom from '../../base/defaults/scripts/libs/live-dom';
import '../../base/defaults/scripts/main';

domReady( () => {
	liveDom( '.real-properties' ).onceInit( () => {
		import( './real-properties' );
	} );

	liveDom( '[data-controls="read-more"]' ).onceInit( () => {
		import( './read-more' );
	} );

	liveDom( '[data-map="locations"]' ).onceInit( () =>
		import( './locations' )
	);

	liveDom( '.contact-us' ).onceInit( () => import( './contact-us' ) );
} );

document.addEventListener( 'gform_main_scripts_loaded', () =>
	import( './gravity-forms' )
);
