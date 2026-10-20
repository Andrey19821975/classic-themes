import { PopupIframe } from './popup-iframe';

export class PopupVideo extends PopupIframe {
	constructor( link, isModal ) {
		super( link, isModal );

		this.dialog.classList.add( 'popup_video' );
	}
}
