import { libraries, map } from '../../base/defaults/scripts/libs/map';
import { zones } from './zones';
import { allZones } from './all-zones';

libraries.push( 'places' );
libraries.push( 'geometry' );

map( '[data-map="contact-us"]', function () {
	/**
	 * @type {HTMLDivElement}
	 */
	const mapCanvas = this;
	const position = new google.maps.LatLng(
		mapCanvas.dataset.latitude,
		mapCanvas.dataset.longitude
	);
	const mapZoom = +mapCanvas.dataset.zoom;
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
	const polygonStyle = {
		strokeColor: '#171f27',
		strokeOpacity: 1,
		strokeWeight: 1,
		fillColor: '#266ccb',
		fillOpacity: 0.3,
	};
	const mapObject = new google.maps.Map( mapCanvas, {
		zoom: mapZoom || 5,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM,
		},
		center: position,
		disableDefaultUI: true,
		styles: mapStyles,
	} );

	const section = mapCanvas.closest( 'section' );
	const items = section.querySelectorAll( '.contact-us__item' );
	const description = section.querySelectorAll( '.contact-us__description' );
	const buttons = section.querySelectorAll( 'button[data-type]' );

	const areas = [];

	const showCurrentArea = ( currentArea, type ) => {
		const zone = currentArea.zoneName;

		items.forEach( ( item ) => {
			if (
				item.dataset.type === type &&
				item.dataset.area.includes( zone )
			) {
				item.style.display = 'grid';
			} else {
				item.style.display = 'none';
			}
		} );

		description.forEach( ( desc ) => desc.classList.add( 'hidden' ) );

		areas.forEach( ( area ) => {
			area.setOptions( {
				fillColor: '#266ccb',
			} );
		} );

		currentArea.setOptions( {
			fillColor: 'transparent',
		} );
	};

	const resetResults = () => {
		items.forEach( ( item ) => ( item.style.display = 'none' ) );
		description.forEach( ( desc ) => desc.classList.remove( 'hidden' ) );
	};

	const destroyAreas = () => {
		areas.forEach( ( area ) => area.setMap( null ) );
	};

	const createAreas = ( type ) => {
		const currentAreas = allZones.filter( ( area ) =>
			area.type.includes( type )
		);
		destroyAreas();

		for ( let i = 0; i < currentAreas.length; i++ ) {
			const coords = [];
			const points = currentAreas[ i ].coordinates;

			for ( let j = 0; j < points.length; j++ ) {
				const point = new google.maps.LatLng(
					points[ j ][ 1 ],
					points[ j ][ 0 ]
				);
				coords.push( point );
			}

			const area = new google.maps.Polygon( {
				mapObject,
				paths: coords,
				...polygonStyle,
			} );

			area.setMap( mapObject );
			area.zoneName = currentAreas[ i ].zoneName;
			areas.push( area );

			area.addListener( 'mouseover', () => {
				area.setOptions( {
					fillOpacity: '0',
				} );
			} );

			area.addListener( 'mouseout', () => {
				area.setOptions( {
					fillOpacity: '0.3',
				} );
			} );

			area.addListener( 'mousedown', () =>
				showCurrentArea( area, type )
			);
		}
	};

	const initAreas = ( type ) => {
		const currentAreas = allZones.filter( ( area ) =>
			area.type.includes( type )
		);

		for ( let i = 0; i < currentAreas.length; i++ ) {
			const points = currentAreas[ i ].coordinates;
			const coords = [];

			for ( let j = 0; j < points.length; j++ ) {
				const point = new google.maps.LatLng(
					points[ j ][ 1 ],
					points[ j ][ 0 ]
				);
				coords.push( point );
			}

			const poly = new google.maps.Polygon( {
				mapObject,
				paths: coords,
				strokeOpacity: 0,
				fillColor: '#266ccb',
				fillOpacity: 0.2,
			} );

			poly.setMap( mapObject );
			areas.push( poly );
		}
	};

	const setCenter = () => {
		const bounds = new google.maps.LatLngBounds();

		allZones.forEach( ( zone ) => {
			const points = zone.coordinates;

			points.forEach( ( point ) => {
				bounds.extend(
					new google.maps.LatLng( point[ 1 ], point[ 0 ] )
				);
			} );
		} );

		mapObject.fitBounds( bounds );
	};

	const init = () => {
		buttons.forEach( ( btn ) => {
			btn.addEventListener( 'click', () => {
				createAreas( btn.dataset.type );
				resetResults();

				buttons.forEach( ( button ) =>
					button.classList.remove( 'active' )
				);
				btn.classList.add( 'active' );
			} );
		} );

		const hasButtons = ! section.classList.contains(
			'contact-us_hide_buttons'
		);

		if ( buttons.length && hasButtons ) {
			initAreas( buttons[ 0 ].dataset.type );
		} else {
			initAreas( 'Self Storage' );
			createAreas( 'Self Storage' );
		}
	};

	init();
} );
