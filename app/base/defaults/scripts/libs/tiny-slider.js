import liveDom from './live-dom';
import domReady from './dom-ready';
import tinySlideCounter from './tiny-slide-counter';

let tns;
const sliders = [];

export function createSlider( element, options ) {
	let data = null;
	let items = Array.from( element.cloneNode( true ).children );
	let isFiltered = null;
	let isSort = null;
	let isUpdated = null;

	const slider = {};
	const mediaQuery = window.matchMedia(
		'(prefers-reduced-motion: no-preference)'
	);

	options.container = element;

	if ( ! mediaQuery.matches ) {
		options.autoplay = false;
	}

	slider.getItems = () => items;
	slider.setItems = ( newItems ) => {
		items = newItems;

		slider.getTns().destroy();
		slider.setIsUpdated( true );
	};
	slider.getElement = () => element;
	slider.setElement = ( newElement ) => {
		element = newElement;

		return slider;
	};
	slider.getIsFiltered = () => isFiltered;
	slider.setIsFiltered = ( newIsFiltered ) => {
		isFiltered = newIsFiltered;

		return slider;
	};
	slider.getIsSort = () => isSort;
	slider.setIsSort = ( newIsSort ) => {
		isSort = newIsSort;

		return slider;
	};
	slider.getIsUpdated = () => isUpdated;
	slider.setIsUpdated = ( newIsUpdated ) => {
		isUpdated = newIsUpdated;

		return slider;
	};
	slider.getData = () => data;
	slider.setData = ( newData ) => {
		data = newData;

		return slider;
	};
	slider.filter = function () {
		slider.getTns().destroy();
		slider.setIsFiltered( true );
	};
	slider.sort = function () {
		slider.getTns().destroy();
		slider.setIsSort( true );
	};

	slider.setElement( element );

	let sliderTns = tns( options );

	tinySlideCounter( options.displayCounter || false, sliderTns );

	slider.getTns = () => sliderTns;

	slider.rebuildTns = function () {
		sliderTns = sliderTns.rebuild();
		tinySlideCounter( options.displayCounter || false, sliderTns );
	};

	slider.runActions = function () {
		if ( slider.getIsUpdated() ) {
			slider.getElement().innerHTML = '';
			slider.getElement().append( ...slider.getItems() );
			slider.rebuildTns();
			slider.setIsUpdated( false );
		} else if ( slider.getIsFiltered() ) {
			slider.getElement().innerHTML = '';
			slider.getElement().append(
				...slider.getItems().filter( ( item ) => {
					return options.filter( {
						slider,
						item,
					} );
				} )
			);

			slider.rebuildTns();
			slider.setIsFiltered( false );
		} else if ( slider.getIsSort() ) {
			slider.getElement().append(
				...slider.getItems().sort( ( a, b ) => {
					return options.sort( {
						slider,
						a,
						b,
					} );
				} )
			);
			slider.rebuildTns();
			slider.setIsSort( false );
		}
	};

	return slider;
}

export function sliderDependencyCallback( done, error ) {
	if ( typeof tns === 'function' ) {
		done();
	} else {
		import( /* webpackChunkName: 'tiny-slider' */ './tiny' )
			.then( ( tns1 ) => {
				tns = tns1.default;
				done();
			} )
			.catch( error );
	}
}

export function tinySlider(
	selector,
	optionsOrCallback = {},
	resolveCallback = () => {}
) {
	let callback;
	if ( typeof optionsOrCallback === 'function' ) {
		callback = optionsOrCallback;
	} else {
		callback = function defaultCallback( element ) {
			if ( typeof element.dataset.tnsSliderId !== 'undefined' ) {
				sliders[ element.dataset.tnsSliderId ]
					.setElement( element )
					.runActions();
			} else {
				element.dataset.tnsSliderId = sliders.length.toString();

				const slider = createSlider( element, optionsOrCallback );
				sliders.push( slider );

				resolveCallback( slider );
			}
		};
	}

	domReady( () => {
		liveDom( selector, {
			dependency: sliderDependencyCallback,
			firstShow() {
				callback( this );
			},
		} );
	} );
}
