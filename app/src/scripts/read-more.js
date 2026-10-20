import liveDom from '../../base/defaults/scripts/libs/live-dom';

function scrollToElement( element ) {
	const header = document.querySelector( '.page-header' );
	const headerHeight = header.getBoundingClientRect().height + 20;

	const top = element.getBoundingClientRect().top;
	const y = top + window.pageYOffset - headerHeight;

	window.scrollTo( { top: y, behavior: 'smooth' } );
}

liveDom( '[data-controls="read-more"]' ).init( function () {
	const parent = this;
	const buttons = parent.querySelectorAll( '.read-more__btn' );

	if ( buttons ) {
		buttons.forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const item = button.closest( '.info-three-columns__item' );
				const isHide = button.closest( '.read-more_hide' );
				parent.classList.toggle( 'show-all' );

				if ( isHide ) scrollToElement( item );
			} );
		} );
	}
} );
