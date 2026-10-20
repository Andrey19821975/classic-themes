// noinspection JSCheckFunctionSignatures, JSUnresolvedVariable, JSUnresolvedFunction, JSUnresolvedVariable

import { map } from '../../base/defaults/scripts/libs/map';

map( '[data-map="properties-detail"]', function () {
	/**
	 * @type {HTMLDivElement}
	 */
	const mapCanvas = this;
	const position = new google.maps.LatLng(
		mapCanvas.dataset.latitude,
		mapCanvas.dataset.longitude
	);
	const markerIcon = mapCanvas.dataset.icon;
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
	const mapObject = new google.maps.Map( mapCanvas, {
		zoom: mapZoom || 17,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM,
		},
		center: position,
		disableDefaultUI: true,
		styles: mapStyles,
	} );

	const svgMarker = {
		path: 'M20 0C12.012 0 5.514 6.499 5.514 14.486c0 9.913 12.964 24.467 13.516 25.081a1.305 1.305 0 0 0 1.94 0c.553-.614 13.517-15.168 13.517-25.08C34.487 6.498 27.988 0 20 0Zm0 21.775c-4.019 0-7.288-3.27-7.288-7.289S15.982 7.198 20 7.198c4.019 0 7.288 3.27 7.288 7.288 0 4.02-3.269 7.289-7.288 7.289Z',
		fillColor: '#1e4e8f',
		strokeColor: '#fff',
		fillOpacity: 1,
		strokeWeight: 1.5,
		width: 20,
		height: 20,
		rotation: 0,
		scale: 1,
		anchor: new google.maps.Point( 0, 20 ),
	};

	new google.maps.Marker( {
		position,
		map: mapObject,
		icon: markerIcon || svgMarker,
	} );
} );

map( '[data-map="demo"]', function () {
	/**
	 * @type {HTMLDivElement}
	 */
	const mapCanvas = this;
	const position = new google.maps.LatLng(
		mapCanvas.dataset.latitude,
		mapCanvas.dataset.longitude
	);
	const markerIcon = mapCanvas.dataset.icon;
	const mapZoom = +mapCanvas.dataset.zoom;
	const mapObject = new google.maps.Map( mapCanvas, {
		zoom: mapZoom || 17,
		center: position,
		disableDefaultUI: true,
	} );

	new google.maps.Marker( {
		position,
		map: mapObject,
		icon: markerIcon || '',
	} );
} );
