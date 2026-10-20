import liveDom from '../../base/defaults/scripts/libs/live-dom';
import {
	applyFiltersToTheMap,
	getMapObject,
	getCurrentZoom,
} from './locations';

let currentLocations = [];

export const getCurrentLocations = () => {
	return currentLocations;
};

export let resetFilters;

liveDom( '.real-properties' ).init( function () {
	const section = this;
	const wrapper = section.querySelector( '.real-estate-objects' );
	const realEstateItems = section.querySelectorAll( '.real-estate' );
	const filters = section.querySelectorAll( '[data-filter-by]' );
	const modeButtons = section.querySelectorAll(
		'.real-properties__mode-button'
	);
	const clearButton = section.querySelector( '.real-properties__clear-btn' );
	const selectedFilterWrapper = section.querySelector(
		'.real-properties__filters-selected'
	);
	const propertyTypes = window.theme.propertyTypes;
	const allLocations = [];
	let selectedFilterValues = [];
	let selectedValues = {
		state: [],
		type: [],
	};

	function renderItems( items ) {
		wrapper.innerHTML = '';
		items.forEach( ( item ) => wrapper.append( item ) );
	}

	function closeDetails() {
		setTimeout(
			() =>
				filters.forEach( ( filter ) =>
					filter.removeAttribute( 'open' )
				),
			200
		);
	}

	resetFilters = ( needUpdate = true ) => {
		selectedFilterWrapper.innerHTML = '';
		selectedFilterValues = [];
		selectedValues = {
			state: [],
			type: [],
		};
		section
			.querySelectorAll( '.real-properties__filters-btn' )
			.forEach( ( btn ) => {
				btn.classList.remove( 'active' );
			} );

		if ( needUpdate ) updateItems();
	};

	function updateItems() {
		if ( selectedFilterValues.length ) {
			const items = [ ...realEstateItems ].filter(
				( item ) =>
					( !! selectedValues.type.length
						? selectedFilterValues.includes( item.dataset.type )
						: true ) &&
					( !! selectedValues.state.length
						? selectedFilterValues.includes( item.dataset.state )
						: true )
			);

			renderItems( items );
		} else {
			renderItems( realEstateItems );
			closeDetails();
		}

		updateMap();
	}

	function createActiveFilter( filterBtn, filterBy ) {
		const value = filterBtn.dataset.value;
		const activeFilter = document.createElement( 'label' );
		activeFilter.classList.add( 'real-properties__active-filter' );
		activeFilter.dataset.value = value;
		activeFilter.dataset.type = filterBy;

		activeFilter.innerHTML = `${ value } <input type="checkbox" checked="true" value="${ value }"/>`;
		activeFilter
			.querySelector( 'input' )
			.addEventListener( 'change', () => {
				activeFilter.remove();
				filterBtn.classList.remove( 'active' );
				selectedFilterValues = selectedFilterValues.filter(
					( filter ) => filter !== value
				);

				selectedValues[ filterBy ] = selectedValues[ filterBy ].filter(
					( item ) => item !== value
				);

				updateItems();
			} );

		selectedFilterWrapper.append( activeFilter );
	}

	function applyFilter( filterBtn, filterBy ) {
		const value = filterBtn.dataset.value;
		const activeFilter = selectedFilterWrapper.querySelector(
			`[data-value="${ value }"]`
		);

		if ( ! activeFilter ) {
			createActiveFilter( filterBtn, filterBy );
			selectedValues[ filterBy ].push( value );
		} else {
			activeFilter.remove();
			selectedValues[ filterBy ] = selectedValues[ filterBy ].filter(
				( item ) => item !== value
			);
		}

		selectedFilterValues = [
			...selectedFilterWrapper.querySelectorAll( '[ data-value ]' ),
		].map( ( filter ) => filter.dataset.value );

		updateItems();
	}

	function getFiltersList( value ) {
		const values = [];

		realEstateItems.forEach( ( item ) => {
			const itemValue = item.dataset[ value ];

			if ( ! values.includes( itemValue ) && itemValue ) {
				values.push( itemValue );
			}
		} );

		return values.sort();
	}

	function createFiltersList() {
		filters.forEach( ( filter ) => {
			const filterBy = filter.dataset.filterBy;
			const filtersList = getFiltersList( filterBy );
			const filtersListEl = filter.querySelector( 'ul' );

			filtersList.forEach( ( item ) => {
				const listItem = document.createElement( 'li' );
				listItem.classList.add( 'real-properties__filters-list-item' );

				listItem.innerHTML = `<button class="real-properties__filters-btn" type="button" data-value="${ item }">${ item }</button>`;
				filtersListEl.append( listItem );

				const filterBtn = listItem.querySelector(
					'.real-properties__filters-btn'
				);

				filterBtn.addEventListener( 'click', () => {
					applyFilter( filterBtn, filterBy );
					filterBtn.classList.toggle( 'active' );
				} );
			} );

			filter.addEventListener( 'mouseleave', () => {
				filter.removeAttribute( 'open' );
			} );
		} );
	}

	function updateMap() {
		if ( selectedFilterValues.length ) {
			currentLocations = allLocations.filter(
				( location ) =>
					( !! selectedValues.type.length
						? selectedFilterValues.includes( location.type )
						: true ) &&
					( !! selectedValues.state.length
						? selectedFilterValues.includes( location.state )
						: true )
			);
		} else {
			currentLocations = allLocations;
		}

		if ( applyFiltersToTheMap ) applyFiltersToTheMap( currentLocations );
	}

	function getLocations( items ) {
		items.forEach( ( item ) => {
			const latitude = item.dataset.latitude;
			const longitude = item.dataset.longitude;
			const type = item.dataset.type;
			const state = item.dataset.state;
			const image = item.querySelector( '.real-estate__img' ).src;
			const title = item
				.querySelector( '.real-estate__title' )
				.textContent.trim();
			const url = item
				.querySelector( '.real-estate__link' )
				?.getAttribute( 'href' );
			const price = item
				.querySelector( '.real-estate__price' )
				?.textContent.trim();
			const properties = item.querySelector( '.real-estate__ul' );

			allLocations.push( {
				latitude,
				longitude,
				type,
				state,
				image,
				title,
				url,
				price,
				item,
				properties,
			} );
		} );

		currentLocations = allLocations;
	}

	function createMapCaption() {
		const caption = section.querySelector( '.locations__caption' );
		propertyTypes.forEach( ( property ) => {
			const item = document.createElement( 'div' );
			item.classList.add( 'locations__caption-item' );
			item.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none"><path fill="${ property.fillColor }" stroke="${ property.strokeColor }" d="M20 0C12.012 0 5.514 6.499 5.514 14.486c0 9.913 12.964 24.467 13.516 25.081a1.305 1.305 0 0 0 1.94 0c.553-.614 13.517-15.168 13.517-25.08C34.487 6.498 27.988 0 20 0Zm0 21.775c-4.019 0-7.288-3.27-7.288-7.289S15.982 7.198 20 7.198c4.019 0 7.288 3.27 7.288 7.288 0 4.02-3.269 7.289-7.288 7.289Z"/></svg><span>${ property.name }</span>`;
			caption.append( item );
		} );
	}

	function toggleMode() {
		modeButtons.forEach( ( btn ) => {
			btn.addEventListener( 'click', () => {
				const mode = btn.dataset.mode;
				const mapObject = getMapObject();
				if ( mapObject ) mapObject.setZoom( getCurrentZoom() );

				section.classList.remove(
					...[
						'real-properties_mode-list',
						'real-properties_mode-map',
					]
				);
				section.classList.add( `real-properties_mode-${ mode }` );

				modeButtons.forEach( ( button ) =>
					button.classList.toggle( 'active' )
				);
			} );
		} );
	}

	function setMapHeight() {
		const header = document.querySelector( '.page-header' );
		const action = section.querySelector( '.real-properties__actions' );

		const headerHeight = header.getBoundingClientRect().height;
		let top = headerHeight + action.getBoundingClientRect().height;
		let headerStickyHeight;

		function setStyleProperty( value ) {
			section.style.setProperty( '--top', `${ value }px` );
		}

		setStyleProperty( top );

		const observer = new MutationObserver( ( mutations ) => {
			if ( mutations[ 0 ].oldValue?.includes( 'page-header_sticky' ) ) {
				top = headerHeight + action.getBoundingClientRect().height;
			} else if ( ! headerStickyHeight ) {
				setTimeout( () => {
					headerStickyHeight = header.getBoundingClientRect().height;
					top = headerStickyHeight;
					setStyleProperty( top );
				}, 300 );
			} else {
				top = headerStickyHeight;
			}

			setStyleProperty( top );
		} );

		observer.observe( header, {
			attributes: true,
			subtree: true,
			attributeOldValue: true,
		} );
	}

	function init() {
		const params = new URL( document.location ).searchParams;
		const paramFromUrl = params.get( 'filter' );

		getLocations( realEstateItems );
		createFiltersList();
		if ( wrapper.getAttribute('data-show-filter')) {
			createMapCaption();
		}

		toggleMode();
		setMapHeight();

		if ( paramFromUrl ) {
			section.querySelector( `[data-value="${ paramFromUrl }"]` ).click();
		}

		if ( clearButton ) {
			clearButton.addEventListener( 'click', resetFilters );
		}
	}

	init();
} );

export default currentLocations;
