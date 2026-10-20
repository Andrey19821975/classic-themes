export function maybeScrollTo( hashOrIdOrName ) {
	if ( hashOrIdOrName.startsWith( '#!' ) ) {
		return;
	}

	if ( hashOrIdOrName.startsWith( '#' ) ) {
		hashOrIdOrName = hashOrIdOrName.slice( 1 );
	}

	let target = document.getElementById( hashOrIdOrName );
	if ( ! target ) {
		target =
			document.getElementsByName( `[name=${ hashOrIdOrName }]` )[ 0 ] ||
			null;
	}

	if ( target ) {
		if ( target.closest( 'theme-tabs-content' ) ) {
			target
				.closest( 'theme-tabs' )
				.openTab( target.closest( 'theme-tabs-content' ).getIndex() );
		}

		scrollToElement( target ).then();
	}
}

export function smoothScroll( target, fixY ) {
	let start;
	let previousTimeStamp;
	let done = false;

	const header = document.querySelector( '.page-header' );
	const sticky = document.querySelector( '[data-smooth-scroll-fix]' );
	const headerInitHeight = header ? header.getBoundingClientRect().height : 0;
	const stickyInitHeight = sticky ? sticky.getBoundingClientRect().height : 0;
	const targetInitY =
		target.getBoundingClientRect().top -
		headerInitHeight -
		fixY -
		stickyInitHeight;
	const duration = targetInitY > 1000 ? 1000 : 500;
	const stepInitY = targetInitY / ( duration / 60 );

	return new Promise( ( resolve ) => {
		const step = ( timestamp ) => {
			if ( start === undefined ) {
				start = timestamp;
			}

			const elapsed = timestamp - start;
			const headerHeight = header
				? header.getBoundingClientRect().height
				: 0;
			const stickyHeight = sticky
				? sticky.getBoundingClientRect().height
				: 0;
			const targetY =
				target.getBoundingClientRect().top -
				headerHeight -
				fixY -
				stickyHeight;

			if ( previousTimeStamp !== timestamp ) {
				const stepY =
					targetY < 0
						? Math.max( stepInitY, targetY )
						: Math.min( stepInitY, targetY );
				if ( stepY === targetY ) {
					done = true;
				}

				window.scrollBy( 0, stepY );
			}

			if ( elapsed < duration || done ) {
				previousTimeStamp = timestamp;
				if ( ! done ) {
					requestAnimationFrame( step );
				} else {
					resolve( target );
				}
			} else {
				window.scrollBy( 0, targetY );
				resolve( target );
			}
		};

		requestAnimationFrame( step );
	} );
}

export function restoreFocus( target ) {
	const tabindex = target.getAttribute( 'tabindex' );
	target.setAttribute( 'tabindex', '0' );
	target.focus( { preventScroll: true } );

	if ( tabindex ) {
		target.setAttribute( 'tabindex', tabindex );
	} else {
		target.removeAttribute( 'tabindex' );
	}
}

export function scrollToElement( target, fixY = 18 ) {
	return smoothScroll( target, fixY ).then( restoreFocus );
}
