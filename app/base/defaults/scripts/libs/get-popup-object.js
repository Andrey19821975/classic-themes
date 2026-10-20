import { PopupInline } from './popup-inline';
import { PopupVideo } from './popup-video';
import { PopupIframe } from './popup-iframe';

export function getPopupObject( options, link, reject ) {
	let popupObject;
	if ( options.type === 'inline' ) {
		let { popupId } = options;
		if ( ! popupId && link ) {
			if ( link.getAttribute( 'href' ) ) {
				popupId = link.getAttribute( 'href' ).slice( 1 );
			} else {
				popupId = link.getAttribute( 'data-popup-id' );
			}
		}

		const dialog = document.getElementById( popupId );
		if ( dialog ) {
			popupObject = new PopupInline( dialog, link, options.isModal );
		} else {
			reject( new Error( `Popup not found. #${ popupId }` ) );
		}
	} else if ( options.type === 'video' ) {
		popupObject = new PopupVideo( link, options.isModal );
	} else if ( options.type === 'iframe' ) {
		popupObject = new PopupIframe( link, options.isModal );
	} else {
		reject( new Error( 'Unsupported popup type.' ) );
	}

	return popupObject;
}
