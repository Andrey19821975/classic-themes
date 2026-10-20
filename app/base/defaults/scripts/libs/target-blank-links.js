import liveDom from '../libs/live-dom';
import tooltip from '../libs/tooltip';
import domReady from '../libs/dom-ready';

export function targetBlankLinks( selector ) {
	domReady( () => {
		liveDom( selector ).init( function () {
			const link = this;
			const isExternal = this.hostname !== window.location.hostname;
			const isFile =
				this.pathname.indexOf( '.' ) !== -1 &&
				'html' !== this.pathname.split( '.' ).pop();
			const isTargetBlank =
				link.hasAttribute( 'target' ) &&
				link.getAttribute( 'target' ) === '_blank';

			if ( isExternal || isFile || isTargetBlank ) {
				if ( ! link.hasAttribute( 'target' ) ) {
					link.setAttribute( 'target', '_blank' );
				}
				if ( ! link.hasAttribute( 'rel' ) ) {
					link.setAttribute( 'rel', 'noopener' );
				}
				const message = 'Opens a new window';
				if ( ! link.textContent.includes( message ) ) {
					const { open, close } = tooltip(
						link,
						message,
						'target-blank-tooltip'
					);

					link.innerHTML = `<span class="screen-reader-text">${ message } </span>${ link.innerHTML }`;

					link.addEventListener( 'mouseenter', open );
					link.addEventListener( 'mouseleave', close );
				}
			}
		} );
	} );
}
