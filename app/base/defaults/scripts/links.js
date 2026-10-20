import { targetBlankLinks } from './libs/target-blank-links';
import { smoothScrollLinks } from './libs/smooth-scroll-links';
import { maybeScrollTo } from './libs/smooth-scroll';
import domReady from './libs/dom-ready';

const targetBlankSelector =
	'a' +
	':not([href^="#"])' +
	':not([href^="tel:"])' +
	':not([href^="mailto:"])' +
	':not([href^="javascript:"])' +
	':not([href^="#popup-"])' +
	':not([href^="https://www.youtube.com/watch?v="])' +
	':not([href^="https://vimeo.com/video/"])' +
	':not([download])' +
	':not(.target-self)';

targetBlankLinks( targetBlankSelector );

const smoothScrollLinksSelector =
	'a[href*="#"]' +
	':not([href="#"])' +
	':not([href^="#popup-"])' +
	':not([href^="#tab-"])';

smoothScrollLinks( smoothScrollLinksSelector );

domReady( () => {
	if ( window.themeNedToHash ) {
		maybeScrollTo( window.themeNedToHash );
	}
} );
