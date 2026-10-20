import { PopupBase } from './popup-base';
import { getYouTubeVideoIdFromUrl } from './get-you-tube-video-id-from-url';
import { getVimeoVideoIdFromUrl } from './get-vimeo-video-id-from-url';

export class PopupIframe extends PopupBase {
	constructor( link, isModal = true ) {
		const dialog = document.createElement( 'dialog' );

		super( dialog, link, isModal );
		this.setSrc( link );
		this.create();

		document.body.appendChild( dialog );
	}

	open() {
		this.iframe.src = this.src;
		super.open();
	}

	close() {
		super.close();
		this.iframe.src = 'about:blank';
	}

	create() {
		this.iframe = document.createElement( 'iframe' );
		this.iframe.classList.add( 'popup__iframe' );
		this.iframe.allowfullscreen = true;
		this.iframe.src = 'about:blank';

		const popupContent = document.createElement( 'div' );
		popupContent.classList.add( 'popup__content' );
		popupContent.append( this.iframe );

		const popupCloseButton = document.createElement( 'button' );
		popupCloseButton.classList.add( 'popup__close-btn' );
		popupCloseButton.setAttribute( 'type', 'button' );
		popupCloseButton.innerHTML = `${ this.getCloseSvg() }<span class="popup__close-text">Close</span>`;

		this.wrapper = document.createElement( 'div' );
		this.wrapper.classList.add( 'popup__wrapper' );
		this.wrapper.append( popupContent );
		this.wrapper.append( popupCloseButton );

		this.dialog.classList.add( 'popup' );
		this.dialog.classList.add( 'popup_base' );
		this.dialog.classList.add( 'popup_iframe' );
		this.dialog.append( this.wrapper );
	}

	setSrc( link ) {
		let src = link.getAttribute( 'href' );

		// todo: It is not very optimal to check each link twice using regular expressions.
		// todo: Links can have GET parameters, such as playback time, they need to somehow add a final SRC.
		// todo: Added Loom https://www.loom.com/embed/%id%.
		const youTubeId = getYouTubeVideoIdFromUrl( src );
		if ( youTubeId ) {
			src = `https://www.youtube.com/embed/${ youTubeId }/?rel=0`;
		} else {
			const vimeoId = getVimeoVideoIdFromUrl( src );
			if ( vimeoId ) {
				src = `https://player.vimeo.com/video/${ vimeoId }/?title=0&byline=0`;
			}
		}

		this.src = src;
	}
}
