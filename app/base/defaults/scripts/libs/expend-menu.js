import { setCloseEvents, toggle } from './menu-helpers';

/**
 * @param {HTMLUListElement} menuUl
 */
export default function expendMenu( menuUl ) {
	function init() {
		const needFocusOutClose = menuUl.dataset.expendMenu === 'auto-close';
		const dialogs = [];
		const expendButtons = Array.from(
			menuUl.querySelectorAll( 'button[aria-expanded]' )
		).map( ( expendButton ) => {
			const dialog = expendButton.nextElementSibling;

			dialog._expendButton = expendButton;
			dialogs.push( dialog );

			return expendButton;
		} );

		expendButtons.forEach( ( expendButton ) => {
			expendButton.addEventListener( 'click', () => {
				toggle( expendButton.nextElementSibling, 'expend' );
				if ( expendButton.nextElementSibling.hidden ) {
					expendButton.focus();
				} else {
					expendButton.nextElementSibling
						.querySelector( 'a' )
						.focus();
				}
			} );
		} );

		dialogs.forEach( ( dialog ) => {
			setCloseEvents( dialog, 'expend', needFocusOutClose );
		} );
	}

	menuUl.addEventListener( 'keydown', init, { once: true } );
	menuUl.addEventListener( 'mousedown', init, { once: true } );
}
