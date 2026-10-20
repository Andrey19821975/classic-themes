/* eslint-disable no-console */
console.time( `Full` );

const url = new URL( document.currentScript.dataset.pageUrl );
const restPath = document.currentScript.dataset.restPath;
const sizes = {};

url.searchParams.append( document.currentScript.dataset.requestKey, '1' );
url.searchParams.append( 'cache', Date.now().toString() );

async function bodyReady() {
	return new Promise( ( resolve ) => {
		setInterval( () => {
			if ( document.body ) {
				resolve();
			}
		}, 50 );
	} );
}

async function massageReady() {
	let message;

	return new Promise( ( resolve ) => {
		setInterval( () => {
			message = document.querySelector( '#message.updated' );
			if ( message ) {
				resolve( message );
			}
		}, 50 );
	} );
}

async function iFrameRunContentVisibilitySizesCalculate(
	startWidth,
	endWidth
) {
	await bodyReady();

	return new Promise( ( resolve ) => {
		console.time( `Load` );

		const iFrame = document.createElement( 'iframe' );
		const lastHeight = {};

		let iFrameWidth = startWidth;

		iFrame.width = startWidth + 'px';
		iFrame.height = '0';
		iFrame.credentialless = true;
		iFrame.style.setProperty( 'max-width', 'none', 'important' );
		iFrame.style.setProperty( 'height', '0', 'important' );
		iFrame.style.setProperty( 'position', 'absolute', 'important' );
		iFrame.style.setProperty( 'overflow-y', 'hidden', 'important' );
		iFrame.src = url.toString();

		document.body.appendChild( iFrame );

		iFrame.contentWindow.addEventListener( 'DOMContentLoaded', function () {
			console.timeLog( `Load` );
			console.time( `Calculate` );

			const blocks =
				iFrame.contentWindow.document.querySelectorAll(
					'[data-visibility]'
				);

			blocks.forEach( ( block ) => {
				const resizeObserver = new ResizeObserver( ( entries ) => {
					const targetId = entries[ 0 ].target.dataset.visibility;
					if ( targetId ) {
						if ( ! sizes[ targetId ] ) {
							sizes[ targetId ] = {};
						}
						if ( ! lastHeight[ targetId ] ) {
							lastHeight[ targetId ] = 0;
						}

						const blockHeight = entries[ 0 ].target
							.getBoundingClientRect()
							.height.toFixed( 0 );
						if ( lastHeight[ targetId ] === blockHeight ) {
							return;
						}

						lastHeight[ targetId ] = sizes[ targetId ][
							iFrameWidth
						] = blockHeight;
					}
				} );

				resizeObserver.observe( block );
			} );

			const intervalId = setInterval( () => {
				const currentWidth = iFrame.getBoundingClientRect().width;
				if ( currentWidth < endWidth ) {
					iFrameWidth += 20;
					iFrame.width = `${ iFrameWidth }px`;

					if ( currentWidth === 1025 ) {
						iFrame.style.setProperty(
							'overflow-y',
							'scroll',
							'important'
						);
					}
				} else {
					// eslint-disable-next-line no-console
					console.timeLog( `Calculate` );
					clearInterval( intervalId );
					resolve();
				}
			}, 10 );
		} );

		iFrame.contentWindow.onerror = function () {};
		iFrame.contentWindow.console.log = function () {};
		iFrame.contentWindow.console.error = function () {};
		iFrame.contentWindow.addEventListener(
			'error',
			function ( e ) {
				e.stopPropagation();
				e.preventDefault();
			},
			false
		);
	} );
}

function getContentVisibilityCSS( data ) {
	// eslint-disable-next-line no-console
	console.time( `Calculate2` );
	for ( const id in sizes ) {
		let css = '';
		const tmp = [];
		Object.keys( sizes[ id ] )
			.sort( ( a, b ) => a - b )
			.forEach( ( endpoint, index, endpoints ) => {
				const currentHeight = sizes[ id ][ endpoint ];
				if ( ! index ) {
					tmp.push( {
						endpoint,
						jump: 10000,
						height: currentHeight,
					} );
				}

				const nextEndpoint = endpoints[ index + 1 ];
				if ( nextEndpoint ) {
					const nextHeight = sizes[ id ][ nextEndpoint ];
					const jump = Math.abs( nextHeight - currentHeight );

					tmp.push( { endpoint, jump, height: currentHeight } );
				} else if ( index ) {
					tmp.push( {
						endpoint,
						jump: 10000,
						height: currentHeight,
					} );
				}
			} );

		const endpoints = tmp
			.sort( ( a, b ) => b.jump - a.jump )
			.slice( 0, 10 )
			.sort( ( a, b ) => parseInt( a.endpoint ) - parseInt( b.endpoint ) )
			.filter( ( current, index, array ) => {
				const next = array[ index + 1 ];

				return ! next || Math.abs( next.height - current.height ) > 10;
			} )
			.map( ( a ) => a.endpoint );

		if ( 'banner' === id ) {
			console.info( sizes );
		}

		const firstEndpoint = endpoints.shift();
		if ( firstEndpoint ) {
			const height = sizes[ id ][ firstEndpoint ];
			css += `[data-visibility="${ id }"]{position:relative;content-visibility:auto;contain-intrinsic-height:auto ${ height }px}`;
		}

		endpoints.forEach( ( endpoint ) => {
			const height = sizes[ id ][ endpoint ];
			css += `@media all and (min-width: ${ endpoint }px){[data-visibility="${ id }"]{contain-intrinsic-height:auto ${ height }px}}`;
		} );

		data.contentVisibilityCSS[ id ] = css;
	}

	console.timeLog( `Calculate2` );

	return data;
}

function beforeUnloadListener( event ) {
	event.preventDefault();

	return ( event.returnValue = '' );
}

const iFramePromise = iFrameRunContentVisibilitySizesCalculate( 320, 1920 );

massageReady().then( ( message ) => {
	window.addEventListener( 'beforeunload', beforeUnloadListener, {
		capture: true,
	} );

	const warningWrapper = document.createElement( 'div' );
	warningWrapper.className = 'notice notice-warning';
	warningWrapper.style.position = 'relative';
	warningWrapper.style.paddingRight = '46px';

	const warning = document.createElement( 'p' );
	warning.textContent =
		'Additional data is being saved, please do not close the page, it will take a few seconds.';

	const warningSpinner = document.createElement( 'span' );
	warningSpinner.className = 'spinner is-active';
	warningSpinner.style.position = 'absolute';
	warningSpinner.style.right = '3px';
	warningSpinner.style.top = 'calc(50% - 4px)';
	warningSpinner.style.transform = 'translateY(-50%)';

	warningWrapper.appendChild( warning );
	warningWrapper.appendChild( warningSpinner );
	message.after( warningWrapper );

	iFramePromise
		.then( () => {
			return {
				contentVisibilityCSS: {},
				fontsPreload: [],
			};
		} )
		.then( getContentVisibilityCSS )
		.then( ( data ) => {
			console.time( `Save` );
			wp.apiFetch( {
				path: restPath,
				method: 'POST',
				data: {
					postId: document.querySelector( '#post_ID' ).value,
					data,
				},
			} )
				.then( () => {
					warningWrapper.remove();
				} )
				.catch( ( error ) => {
					warningSpinner.remove();
					warningWrapper.classList.add( 'notice-error' );
					warningWrapper.classList.remove( 'notice-warning' );
					warning.textContent =
						'An error occurred while saving additional information: ' +
						error.message;
				} )
				.finally( () => {
					console.timeLog( `Save` );
					removeEventListener( 'beforeunload', beforeUnloadListener, {
						capture: true,
					} );
					console.timeLog( `Full` );
				} );
		} );
} );
