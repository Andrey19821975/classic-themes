// noinspection JSIgnoredPromiseFromCall

import { popup } from '../../base/defaults/scripts/libs/popup';
// import defer from '../../base/defaults/scripts/jquery/libs/jquery-defer';

popup( {
	linkSelector: '[href^="#popup-"], [data-popup-id]',
} );

// const menuCart = await popup( {
// 	linkSelector: '.menu-cart-link',
// 	popupId: 'popup-menu-cart',
// } );
//
// defer( () => {
// 	jQuery( document.body ).on( 'added_to_cart', () => menuCart.open() );
// } );

popup( {
	linkSelector: [
		'[href^="https://www.youtube.com/watch?v="]',
		'[href^="https://vimeo.com/video/"]',
		'[href^="https://player.vimeo.com/video/"]',
	].join(),
	type: 'video',
} );
