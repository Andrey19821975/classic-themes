import menuTooltip from './menu-tooltip';
import { setCloseEvents, open, openTypes } from './menu-helpers';

function isAccessiBeKeyboardEnabled() {
	return (
		true ===
		JSON.parse( localStorage.getItem( 'acsbState' ) || '{}' )?.actions
			?.keyNav?.enabled
	);
}

/**
 * @param {HTMLUListElement} menuUl
 */
export default function keyboardMenu( menuUl ) {
	menuUl.addEventListener(
		'keyup',
		() => {
			if ( isAccessiBeKeyboardEnabled() ) {
				return;
			}

			const needFocusOutClose =
				menuUl.dataset.keyboardMenu === 'auto-close';
			const needOpen = typeof menuUl.dataset.expendMenu !== 'undefined';
			const topLinks = Array.from(
				menuUl.querySelectorAll( ':scope > li > a' )
			);

			const dialogs = [];

			topLinks.forEach( ( topLink, topIndex ) => {
				const currentSubMenu = getSubMenu( topLink );
				topLink.addEventListener( 'keydown', ( event ) =>
					setEventsByLink(
						event,
						[],
						false,
						topLinks,
						topIndex,
						false,
						currentSubMenu,
						'horizontal'
					)
				);

				if ( currentSubMenu ) {
					const prevTopIndex = topIndex - 1;
					setEventsBySubMenu(
						currentSubMenu,
						topLinks,
						prevTopIndex < 0 ? topLinks.length - 1 : prevTopIndex,
						topIndex
					);
					dialogs.push( currentSubMenu );
				}
			} );

			dialogs.forEach( ( dialog ) => {
				setCloseEvents( dialog, 'keyboard', needFocusOutClose );
			} );

			menuTooltip(
				menuUl,
				!! dialogs.length,
				menuUl.className.includes( 'header' ) ? 'header' : 'footer'
			);

			function setEventsBySubMenu(
				currentSubMenu,
				parentLinks,
				prevParentIndex,
				topIndex
			) {
				const links = Array.from(
					currentSubMenu.querySelectorAll( ':scope > ul > li > a' )
				);
				links.forEach( ( link, index ) => {
					const nextSubMenu = getSubMenu( link );
					link.addEventListener( 'keydown', ( event ) => {
						setEventsByLink(
							event,
							parentLinks,
							prevParentIndex,
							links,
							index,
							currentSubMenu,
							nextSubMenu,
							'vertical',
							topIndex
						);
					} );

					if ( nextSubMenu ) {
						setEventsBySubMenu(
							nextSubMenu,
							links,
							index,
							topIndex
						);
						dialogs.push( nextSubMenu );
					}
				} );
			}

			function setEventsByLink(
				event,
				parentLinks,
				prevParentIndex,
				links,
				index,
				currentSubMenu,
				nextSubMenu,
				direction,
				topIndex = false
			) {
				let preventDefault = false;
				const key = event.key;
				const nextKey =
					'horizontal' === direction ? 'ArrowRight' : 'ArrowDown';
				const prevKey =
					'horizontal' === direction ? 'ArrowLeft' : 'ArrowUp';
				const parentNextKey =
					'horizontal' === direction ? 'ArrowDown' : 'ArrowRight';
				const parentPrevKey =
					'horizontal' === direction ? 'ArrowUp' : 'ArrowLeft';

				if ( nextKey === key ) {
					const next = index + 1;
					links[ next >= links.length ? 0 : next ].focus();
					preventDefault = true;
				} else if ( prevKey === key ) {
					const prev = index - 1;
					links[ prev < 0 ? links.length - 1 : prev ].focus();
					preventDefault = true;
				} else if ( parentNextKey === key ) {
					if ( nextSubMenu ) {
						if ( needOpen ) {
							open( nextSubMenu, 'keyboard' );
						}

						nextSubMenu.querySelector( 'a' ).focus();
						preventDefault = true;
					} else if ( false !== topIndex ) {
						const next = topIndex + 1;
						topLinks[ next >= topLinks.length ? 0 : next ].focus();
						preventDefault = true;
					}
				} else if ( parentLinks.length && parentPrevKey === key ) {
					parentLinks[ prevParentIndex ].focus();
					preventDefault = true;
				}

				if ( preventDefault ) {
					event.preventDefault();
					if ( currentSubMenu && needOpen ) {
						openTypes.set( currentSubMenu, 'keyboard' );
					}
				}
			}

			function getSubMenu( link ) {
				if ( link.nextElementSibling ) {
					if ( link.nextElementSibling.tagName === 'DIV' ) {
						return link.nextElementSibling;
					}

					if (
						link.nextElementSibling.nextElementSibling &&
						link.nextElementSibling.nextElementSibling.tagName ===
							'DIV'
					) {
						return link.nextElementSibling.nextElementSibling;
					}
				}

				return false;
			}
		},
		{ once: true }
	);
}
