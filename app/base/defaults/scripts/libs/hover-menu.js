import { close, open } from './menu-helpers';

/**
 * @param {HTMLUListElement} menuUl
 */
export default function hoverMenu( menuUl ) {
	menuUl.addEventListener(
		'mouseover',
		() => {
			const dialogs = new Map();
			const hoverItems = Array.from(
				menuUl.querySelectorAll( 'div[hidden]' )
			).map( ( dialog ) => {
				const hoverItem = dialog.parentElement;

				dialogs.set( hoverItem, dialog );

				return hoverItem;
			} );

			hoverItems.forEach( ( hoverItem ) => {
				const dialog = dialogs.get( hoverItem );
				hoverItem.addEventListener( 'mouseover', () => {
					open( dialog, 'hover' );
				} );
				hoverItem.addEventListener( 'mouseout', () => {
					close( dialog, 'hover' );
				} );
			} );
		},
		{ once: true }
	);
}
