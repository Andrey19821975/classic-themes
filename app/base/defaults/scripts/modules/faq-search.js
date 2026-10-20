import liveDom from '../libs/live-dom.js';
import { scrollToElement } from '../libs/smooth-scroll.js';

liveDom( '.faq' ).init( function () {
	const section = this;
	const form = section.querySelector( '.faq__search-form' );

	if ( form ) {
		const input = section.querySelector( '[type="search"]' );

		const search = () => {
			const accordions = section.querySelectorAll( '.accordion' );

			if ( input.value ) {
				const pattern = new RegExp( input.value, 'i' );
				accordions.forEach( ( accordion ) => {
					if ( pattern.test( accordion.innerText ) ) {
						accordion.style.removeProperty( 'display' );
					} else {
						accordion.style.display = 'none';
					}
				} );
			} else {
				accordions.forEach( ( accordion ) => {
					accordion.style.removeProperty( 'display' );
				} );
			}
		};

		input.addEventListener( 'input', search );
		form.addEventListener( 'reset', () => setTimeout( search, 100 ) );
		form.addEventListener( 'submit', ( event ) => {
			event.preventDefault();
			scrollToElement(
				section.querySelector( '[role="tablist"]' ),
				30
			).then();
		} );
	}

	const tabs = section.querySelector( '.faq__tabs' );
	tabs.addEventListener( 'theme-tabs-ready', () => {
		const accordion = section.querySelector( '.accordion' );
		const trigger = accordion.querySelector( '.accordion__trigger' );
		const panel = accordion.querySelector( '.accordion__panel' );
		accordion.classList.add( 'accordion_active' );
		trigger.setAttribute( 'aria-expanded', 'true' );
		panel.removeAttribute( 'hidden' );
	} );
} );
