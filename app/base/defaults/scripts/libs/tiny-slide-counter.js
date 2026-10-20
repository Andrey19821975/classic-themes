export default function tinySlideCounter( display = false, slider ) {
	const visuallyHiddenCounter = document.createElement( 'div' );
	const wrapper = slider.getInfo().container.closest( '.tns-outer' );

	// visuallyHiddenCounter.setAttribute( 'aria-live', 'polite' );
	// visuallyHiddenCounter.setAttribute( 'aria-atomic', 'true' );
	visuallyHiddenCounter.classList.add( 'screen-reader-text' );
	visuallyHiddenCounter.innerHTML += `slide <span class="current">1</span> of <span class="total"></span>`;

	wrapper.prepend( visuallyHiddenCounter );
	wrapper.querySelector( '.tns-liveregion' ).remove();

	setCount( visuallyHiddenCounter, slider );
	displayCount( display, wrapper, slider );
}

function setCount( parent, slider ) {
	const indexActiveEl = parent.querySelector( '.current' );
	const totalCountEl = parent.querySelector( '.total' );
	totalCountEl.textContent = slider.getInfo().slideCount;

	slider.events.on(
		'indexChanged',
		( info ) => ( indexActiveEl.textContent = info.displayIndex )
	);
}

function displayCount( display, wrapper, slider ) {
	if ( display ) {
		const visibleCounter = document.createElement( 'div' );

		visibleCounter.setAttribute( 'aria-hidden', 'true' );
		visibleCounter.classList.add( 'slide-counter' );
		visibleCounter.innerHTML += `<span class="current">1</span><span class="separator">/</span><span class="total"></span>`;

		wrapper.after( visibleCounter );
		setCount( visibleCounter, slider );
	}
}
