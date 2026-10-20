// noinspection JSCheckFunctionSignatures, JSUnresolvedVariable, JSUnresolvedFunction, JSUnresolvedVariable

import { map, libraries } from '../../base/defaults/scripts/libs/map';
import { stateCoords } from './states';
import { getCurrentLocations, resetFilters } from './real-properties';

libraries.push( 'places' );
libraries.push( 'geometry' );

export let applyFiltersToTheMap, getCurrentZoom;
let mapObject;

export const getMapObject = () => {
	return mapObject;
};

map( '[data-map="locations"]', function () {
	/**
	 * @type {HTMLDivElement}
	 */
	const mapCanvas = this;
	const wrapper = document.querySelector( '.real-estate-objects' );
	const clearFiltersBtn = document.querySelector(
		'.real-properties__clear-btn'
	);
	const position = new google.maps.LatLng(
		mapCanvas.dataset.latitude,
		mapCanvas.dataset.longitude
	);
	const mapStyles = [
		{
			featureType: 'poi',
			elementType: 'geometry',
			stylers: [ { color: '#c6d2c9' } ],
		},
		{
			featureType: 'poi',
			elementType: 'labels.text.fill',
			stylers: [ { color: '#40464A' } ],
		},
		{
			featureType: 'landscape',
			elementType: 'geometry',
			stylers: [ { color: '#d3dfd7' } ],
		},
		{
			featureType: 'road',
			elementType: 'geometry',
			stylers: [ { color: '#f0f4f1' } ],
		},
	];
	const mapZoom = +mapCanvas.dataset.zoom;
	mapObject = new google.maps.Map( mapCanvas, {
		zoom: mapZoom || 17,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM,
		},
		center: position,
		disableDefaultUI: true,
		styles: mapStyles,
	} );

	getCurrentZoom = () => {
		const mapObjectZoom = mapObject.getZoom();

		return mapObjectZoom < 10 && mapObjectZoom > 4 ? mapObjectZoom : 5;
	};

	const propertyTypes = window.theme.propertyTypes;

	const polygonStyle = {
		strokeColor: '#6784AB',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#6784AB',
		fillOpacity: 0.3,
	};
	const bounds = new google.maps.LatLngBounds();
	const states = [];
	const markers = [];
	const allLocations = getCurrentLocations();

	let draw, poly, openMarkerInfo, zoomChanged, centerChanged;
	let locations = allLocations;

	function togglePopup( marker, popup ) {
		if ( openMarkerInfo ) {
			openMarkerInfo.close();
		}
		popup.open( mapObject, marker );
		openMarkerInfo = popup;
	}

	function createMarker( location, latLng ) {
		const property = propertyTypes.filter(
			( type ) => type.name === location.type
		)[ 0 ];

		const svgMarker = {
			path: 'M10 0C6.006 0 2.757 3.25 2.757 7.243c0 4.957 6.482 12.233 6.758 12.54.259.29.712.289.97 0 .276-.307 6.758-7.583 6.758-12.54C17.243 3.25 13.994 0 10 0Zm0 10.887a3.648 3.648 0 0 1-3.644-3.644A3.648 3.648 0 0 1 10 3.6a3.648 3.648 0 0 1 3.644 3.644A3.648 3.648 0 0 1 10 10.887Z',
			fillColor: property.fillColor,
			strokeColor: property.strokeColor,
			fillOpacity: 1,
			strokeWeight: 1.5,
			width: 20,
			height: 20,
			rotation: 0,
			scale: 1,
			anchor: new google.maps.Point( 10, 20 ),
		};

		location.marker = new google.maps.Marker( {
			position: latLng,
			map: mapObject,
			icon: svgMarker,
		} );

		markers.push( location.marker );
	}

	function createMapLocations( isInit = false ) {
		for ( let i = 0; i < locations.length; i++ ) {
			const location = locations[ i ];
			const latLng = new google.maps.LatLng(
				location.latitude,
				location.longitude
			);

			bounds.extend( latLng );
			mapObject.fitBounds( bounds );

			createMarker( location, latLng );

			const title = location.url
				? `<a href="${ location.url }" class="real-estate__link">${ location.title }</a>`
				: location.title;

			const popup = new google.maps.InfoWindow( {
				content: `
	              <div class="real-estate__popup">
	                    <div class="real-estate__image"><img src="${ location.image }" alt=""/></div>
	                    <div class="real-estate__content">
		                    <h3 class="real-estate__title">${ title }</h3>
			                <p class="real-estate__price">${ location.price }</p>
							<ul class="real-estate__ul">${ location.properties.innerHTML }</ul>
						</div>
	              </div>
            `,
			} );

			location.marker.addListener( 'click', () =>
				togglePopup( location.marker, popup )
			);
		}

		if ( isInit ) {
			setTimeout( () => renderVisibleLocations(), 500 );
		}
	}

	function renderItem( location ) {
		wrapper.append( location.item );

		const fillColor = location?.marker.icon.fillColor;
		const strokeColor = location?.marker.icon.strokeColor;

		location.item.addEventListener( 'mouseover', () => {
			location.marker.setOptions( {
				icon: {
					...location.marker.icon,
					fillColor: '#1d4e8f',
					strokeColor: '#d5d2d2',
					scale: 2,
				},
			} );
		} );

		location.item.addEventListener( 'mouseout', () => {
			location.marker.setOptions( {
				icon: {
					...location.marker.icon,
					fillColor,
					strokeColor,
					scale: 1,
				},
			} );
		} );
	}

	function renderItems( items ) {
		wrapper.classList.add( 'preloader' );

		if ( !! items.length ) {
			wrapper.innerHTML = '';
			items.forEach( ( item ) => renderItem( item ) );
		} else {
			wrapper.innerHTML =
				'<p class="real-properties__empty">There are no properties meeting your selection</p>';
		}

		setTimeout( () => wrapper.classList.remove( 'preloader' ), 500 );
	}

	function renderVisibleLocations() {
		const visibleRegion = mapObject.getBounds();
		if ( visibleRegion ) {
			const visibleLocations = locations.filter( ( location ) =>
				visibleRegion.contains(
					new google.maps.LatLng(
						location.latitude,
						location.longitude
					)
				)
			);

			renderItems( visibleLocations );
		}

		if ( poly ) deletePolygon();
	}

	function renderSelectedLocations( path ) {
		const polygonBounds = new google.maps.LatLngBounds();

		path.forEach( ( coordinate ) => polygonBounds.extend( coordinate ) );

		const selectedLocations = locations.filter( ( location ) => {
			return isMarkerInsidePolygon( location );
		} );

		renderItems( selectedLocations );
		updateMap( selectedLocations );
	}

	function updateMap( newLocations ) {
		locations = newLocations;
		markers.forEach( ( marker ) => marker.setMap( null ) );
		markers.length = 0;

		createMapLocations();
	}

	applyFiltersToTheMap = ( newLocations ) => {
		if ( poly ) poly.setMap( null );

		updateMap( newLocations );
	};

	function createStates() {
		for ( let i = 0; i < stateCoords.length; i++ ) {
			const stateBounds = new google.maps.LatLngBounds();
			const coords = [];
			const points = stateCoords[ i ][ 1 ];
			const name = stateCoords[ i ][ 0 ];

			for ( let j = 0; j < points.length; j++ ) {
				const point = new google.maps.LatLng(
					points[ j ][ 0 ],
					points[ j ][ 1 ]
				);
				coords.push( point );
				stateBounds.extend( point );
			}

			const state = new google.maps.Polygon( {
				mapObject,
				paths: coords,
				fillColor: 'transparent',
				fillOpacity: 0.35,
				strokeColor: '#61ab53',
				strokeOpacity: 0.8,
				strokeWeight: 1,
			} );

			state.name = name;
			state.setMap( mapObject );
			states.push( state );

			state.addListener( 'mouseover', () => {
				if ( mapObject.getZoom() <= 6 ) {
					state.setOptions( {
						fillColor: '#61ab53',
					} );
				}
			} );

			state.addListener( 'mousedown', () => {
				if ( mapObject.getZoom() <= 6 ) {
					removeListenerZoom();
					const stateLocations = allLocations.filter(
						( location ) => location.state === state.name
					);
					renderItems( stateLocations );
					if ( poly ) deletePolygon();
					if ( clearFiltersBtn ) resetFilters( false );
					mapObject.fitBounds( stateBounds );
					setTimeout( () => addListenerZoom(), 500 );
					updateMap( stateLocations );
				}
			} );

			state.addListener( 'mouseout', () => {
				state.setOptions( {
					fillColor: 'transparent',
				} );
			} );
		}
	}

	function destroyStates() {
		states.forEach( ( state ) => state.setMap( null ) );

		updateMap( allLocations );
	}

	function addListenerZoom() {
		zoomChanged = mapObject.addListener( 'zoom_changed', () =>
			renderVisibleLocations()
		);
		centerChanged = mapObject.addListener( 'center_changed', () =>
			renderVisibleLocations()
		);
	}

	function removeListenerZoom() {
		google.maps.event.removeListener( zoomChanged );
		google.maps.event.removeListener( centerChanged );
	}

	function deletePolygon( renderLocations = false ) {
		if ( poly ) poly.setMap( null );

		if ( renderLocations ) {
			setTimeout( () => renderVisibleLocations(), 500 );
		}
	}

	function isMarkerInsidePolygon( location ) {
		const latLng = new google.maps.LatLng(
			location.latitude,
			location.longitude
		);

		return google.maps.geometry.poly.containsLocation( latLng, poly );
	}

	function drawPolygon() {
		if ( poly ) deletePolygon();
		poly = new google.maps.Polyline( {
			mapObject,
			clickable: false,
		} );

		const move = mapObject.addListener( 'mousemove', ( e ) => {
			poly.getPath().push( e.latLng );
			deletePolygon();
			poly = new google.maps.Polyline( {
				mapObject,
				path: poly.getPath(),
				clickable: false,
				...polygonStyle,
			} );

			poly.setMap( mapObject );
		} );

		const createPolygon = mapObject.addListener( 'mouseup', () => {
			google.maps.event.removeListener( move );
			const paths = poly.getPath();
			deletePolygon();
			poly = new google.maps.Polygon( {
				mapObject,
				paths,
				...polygonStyle,
			} );

			poly.setMap( mapObject );

			google.maps.event.removeListener( draw );
			google.maps.event.removeListener( createPolygon );

			renderSelectedLocations( paths );

			enable();
			createStates();
		} );
	}

	function disable() {
		mapObject.setOptions( {
			draggable: false,
			zoomControl: false,
			scrollwheel: false,
			disableDoubleClickZoom: false,
		} );
	}

	function enable() {
		mapObject.setOptions( {
			draggable: true,
			zoomControl: true,
			scrollwheel: true,
			disableDoubleClickZoom: true,
		} );
	}

	function init() {
		const drawBtn = document.querySelector( '#draw-btn' );
		const clearBtn = document.querySelector( '#clear-btn' );

		clearBtn.addEventListener( 'click', () => {
			deletePolygon( true );
			addListenerZoom();
			updateMap( allLocations );
		} );

		drawBtn.addEventListener( 'click', () => {
			disable();
			destroyStates();
			draw = mapObject.addListener( 'mousedown', () => drawPolygon() );
			removeListenerZoom();
		} );

		createMapLocations( true );
		createStates();
		addListenerZoom();
	}

	init();
} );
