export const openTypes = new WeakMap();

function getClassName( element, regExp, modifier ) {
	let className = '';
	element.classList.forEach( ( c ) => {
		if ( regExp.test( c ) ) {
			className = c;
		}
	} );

	if ( modifier ) {
		className += '_' + modifier;
	}

	return className;
}

function openLinkClassName( element, isOpen ) {
	element.classList.toggle(
		getClassName( element, /__link$/, 'open' ),
		isOpen
	);
}

function openItemClassName( element, isOpen ) {
	element.classList.toggle(
		getClassName( element, /__item$/, 'open', isOpen )
	);
}

function openButtonClassName( element, isOpen ) {
	element.classList.toggle(
		getClassName( element, /__expend-button$/, 'open' ),
		isOpen
	);
}

/**
 * @param {HTMLDivElement} dialog
 * @param {string}         type
 */
export function toggle( dialog, type ) {
	if ( dialog.hidden ) {
		open( dialog, type );
	} else {
		close( dialog, type );
	}
}

/**
 * @param {HTMLDivElement} dialog
 * @param {string}         type
 */
export function open( dialog, type ) {
	openTypes.set( dialog, type );
	dialog.hidden = false;
	dialog.previousElementSibling.ariaExpanded = 'true';
	openButtonClassName( dialog.previousElementSibling, true );
	openLinkClassName(
		dialog.previousElementSibling.previousElementSibling,
		true
	);
	openItemClassName( dialog.parentElement, true );
}

/**
 * @param {HTMLDivElement} dialog
 * @param {string}         type
 */
export function close( dialog, type ) {
	if ( openTypes.get( dialog ) === type ) {
		openTypes.set( dialog, '' );
		dialog.hidden = true;
		dialog.previousElementSibling.ariaExpanded = 'false';
		dialog
			.querySelectorAll( 'button[aria-expanded]' )
			.forEach( ( expendButton ) => {
				close( expendButton.nextElementSibling, type );
			} );
		openButtonClassName( dialog.previousElementSibling, false );
		openLinkClassName(
			dialog.previousElementSibling.previousElementSibling,
			false
		);
		openItemClassName( dialog.parentElement, false );
	}
}

/**
 * @param {HTMLDivElement} dialog
 * @param {string}         type
 * @param {boolean}        needFocusOutClose
 */
export function setCloseEvents( dialog, type, needFocusOutClose ) {
	dialog.addEventListener( 'keydown', ( event ) => {
		if ( openTypes.get( dialog ) === type && event.key === 'Escape' ) {
			event.preventDefault();
			event.stopPropagation();
			close( dialog, type );

			if ( 'expend' === type ) {
				dialog.previousElementSibling.focus();
			} else {
				dialog.previousElementSibling.previousElementSibling.focus();
			}
		}
	} );

	if ( needFocusOutClose ) {
		dialog.addEventListener( 'focusout', ( event ) => {
			if (
				openTypes.get( dialog ) === type &&
				! dialog.contains( event.relatedTarget ) &&
				dialog.previousElementSibling !== event.relatedTarget
			) {
				close( dialog, type );
			}
		} );
	}
}
