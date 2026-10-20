import liveDom from './live-dom';
import domReady from './dom-ready';
import { getPopupObject } from './get-popup-object';

const isBrowserNotSupportPopup = window.HTMLDialogElement === undefined;
if ( isBrowserNotSupportPopup ) {
	( async () => {
		const { default: polyfill } = await import( 'dialog-polyfill' );
		liveDom( 'dialog' ).init( function () {
			polyfill.registerDialog( this );
		} );
	} )();
}

export function popup( options = {} ) {
	return new Promise( ( resolve, reject ) => {
		// eslint-disable-next-line no-param-reassign
		options = Object.assign(
			{
				type: 'inline',
				isModal: true,
				popupId: '',
				linkSelector: '',
			},
			options
		);

		domReady( () => {
			if ( ! options.linkSelector ) {
				const popupObject = getPopupObject( options, null, reject );
				resolve( popupObject );
			} else {
				liveDom( options.linkSelector ).firstShow( function () {
					this.setAttribute( 'aria-haspopup', 'dialog' );
					this.setAttribute( 'aria-expanded', 'false' );

					const images = this.querySelectorAll( 'img' );
					if ( options.type === 'video' && images.length > 0 ) {
						this.classList.add( 'with-btn-play' );
						images.forEach( ( image ) => {
							image.classList.add( 'with-btn-play__img' );
							if ( image.classList.contains( 'alignleft' ) ) {
								this.classList.add(
									'with-btn-play_align-left'
								);
							} else if (
								image.classList.contains( 'alignright' )
							) {
								this.classList.add(
									'with-btn-play_align-right'
								);
							} else if (
								image.classList.contains( 'alignnone' )
							) {
								this.classList.add(
									'with-btn-play_align-none'
								);
							}
						} );
					}

					const popupObject = getPopupObject( options, this, reject );

					this.addEventListener( 'click', ( e ) => {
						e.preventDefault();
						if ( popupObject ) {
							popupObject.open();
						}
					} );

					if ( popupObject ) {
						resolve( popupObject );
					}
				} );
			}
		} );
	} );
}
