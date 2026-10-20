import domReady from './dom-ready';
import liveDom from './live-dom';
import { maybeScrollTo } from './smooth-scroll';

export function smoothScrollLinks( selector ) {
	domReady( () => {
		liveDom( selector ).init( function () {
			const link = this;
			const windowPathname = window.location.pathname.replace(
				/^\//,
				''
			);
			const linkPathname = link.pathname.replace( /^\//, '' );

			if (
				link.parentNode.classList.contains( 'popup-link' ) ||
				windowPathname !== linkPathname ||
				window.location.hostname !== link.hostname
			) {
				return;
			}

			link.addEventListener( 'click', function ( event ) {
				event.preventDefault();

				maybeScrollTo( link.hash );
			} );
		} );
	} );
}
