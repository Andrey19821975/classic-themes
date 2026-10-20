// todo: Remove support for additional parameters if they are needed, there should be a mechanism to set them differently.
// todo: Remove support for method chaining.
// todo: Remove all private functions from the main function.
// todo: Make one watcher for the body, in which to check which element has changed and call the appropriate action.
// todo: Pull element properties to WakeMap.
// todo: Wait until the element is filled with all descendants.

export default function liveDom( selector, actions ) {
	if ( ! isValidSelector( selector ) ) {
		// eslint-disable-next-line no-console
		console.error( 'Invalid Selector', selector );
	}

	const observers = new Map();
	const margin = { top: 250, bottom: 250 };
	const elements = [];
	let observer = null;
	let stopped = false;
	let dependencyFunction = null;
	let dependencyStatus = 'ready';
	let dependencyMissedCallbacks = [];
	let dependencyMissedAddElements = [];
	const autoStop = {
		onceInit: null,
		onceShow: null,
		onceAlways: null,
		onceHide: null,
	};
	const autoUnobserve = {
		firstInit: null,
		firstShow: null,
		firstAlways: null,
		firstHide: null,
	};
	const callbacks = {
		onceInit: null,
		onceShow: null,
		onceAlways: null,
		onceHide: null,
		init: null,
		firstShow: null,
		show: null,
		firstAlways: null,
		always: null,
		firstHide: null,
		hide: null,
	};
	const __callbacks = {
		init: null,
		firstShow: null,
		show: null,
		firstAlways: null,
		always: null,
		firstHide: null,
		hide: null,
	};
	const config = {
		doneFirst: {
			always: [],
			show: [],
			hide: [],
		},
		doneOnce: {
			init: false,
			always: false,
			show: false,
			hide: false,
		},
	};

	// noinspection JSUnusedGlobalSymbols
	const functions = {
		onceInit,
		onceShow,
		onceAlways,
		onceHide,
		init,
		firstShow,
		show,
		firstHide,
		hide,
		firstAlways,
		always,
		dependency,
		setMargin,
	};

	function isValidSelector( s ) {
		try {
			document.createDocumentFragment().querySelector( s );
		} catch ( e ) {
			return false;
		}
		return true;
	}

	if ( actions ) {
		for ( const func in actions ) {
			functions[ func ]( actions[ func ] );
		}
	}

	function onceInit( callback, isNeedAutoStop = true ) {
		callbacks.onceInit = callback;
		autoStop.onceInit = isNeedAutoStop;
		start();
	}

	function onceShow( callback, isNeedAutoStop = true ) {
		callbacks.onceShow = callback;
		autoStop.onceShow = isNeedAutoStop;
		start();
	}

	function onceAlways( callback, isNeedAutoStop = true ) {
		callbacks.onceAlways = callback;
		autoStop.onceAlways = isNeedAutoStop;
		start();
	}

	function onceHide( callback, isNeedAutoStop = true ) {
		callbacks.onceHide = callback;
		autoStop.onceHide = isNeedAutoStop;
		start();
	}

	function init( callback, autoUnObserve = false, autoStart = true ) {
		callbacks.init = callback;
		autoUnobserve.firstInit = autoUnObserve;
		if ( autoStart === true ) {
			start();
		}
	}

	function firstShow( callback, autoUnObserve = false, autoStart = true ) {
		callbacks.firstShow = callback;
		autoUnobserve.firstShow = autoUnObserve;
		if ( autoStart === true ) {
			start();
		}
	}

	function show( callback, autoStart = true ) {
		callbacks.show = callback;
		if ( autoStart === true ) {
			start();
		}
	}

	function firstHide( callback, autoUnObserve = false, autoStart = true ) {
		callbacks.firstHide = callback;
		autoUnobserve.firstHide = autoUnObserve;
		if ( autoStart === false ) {
			start();
		}
	}

	function hide( callback, autoStart = true ) {
		callbacks.hide = callback;
		if ( autoStart === true ) {
			start();
		}
	}

	function firstAlways( callback, autoUnObserve = false, autoStart = true ) {
		callbacks.firstAlways = callback;
		autoUnobserve.firstAlways = autoUnObserve;
		if ( autoStart === false ) {
			start();
		}
	}

	function always( callback ) {
		callbacks.always = callback;
	}

	function dependency( dependencyFunc ) {
		dependencyFunction = dependencyFunc;
		dependencyStatus = 'not_ready';
	}

	function setMargin( [ top = 250, bottom = 250 ] ) {
		margin.top = top;
		margin.bottom = bottom;
	}

	function addElement( element ) {
		if ( elements.includes( element ) ) {
			return;
		}

		elements.push( element );
		initElement( element );

		if ( stopped === false ) {
			if ( typeof IntersectionObserver === 'undefined' ) {
				badBrowsers( element );
			} else {
				goodBrowsers( element );
			}
		}
	}

	function badBrowsers( element ) {
		const done = () => {
			dependencyStatus = 'ready';
			badBrowsers( element );
		};

		const error = ( e ) => {
			observers.get( element ).unobserve( element );
			if ( e ) {
				// eslint-disable-next-line no-console
				console.error( e );
			}
		};

		if ( dependencyStatus === 'ready' ) {
			Object.keys( callbacks ).forEach( ( callback ) => {
				if ( __callbacks[ callback ] ) {
					callbacks[ callback ].bind( element )();
				}
				if ( callbacks[ callback ] ) {
					callbacks[ callback ].bind( element )();
				}
			} );
		} else if ( dependencyStatus === 'not_ready' ) {
			dependencyStatus = 'process';
			dependencyFunction( done, error );
		}
	}

	function goodBrowsers( element ) {
		let intersectionObserverInit = false;

		observers.set(
			element,
			new IntersectionObserver(
				( [ entry ] ) => {
					if (
						intersectionObserverInit ||
						entry.intersectionRatio > 0
					) {
						intersectionObserverInit = true;

						if ( entry.isIntersecting ) {
							runElement( element, 'show' );
						} else if ( entry.isIntersecting === false ) {
							runElement( element, 'hide' );
						}
						runElement( element, 'always' );
					} else {
						intersectionObserverInit = true;
					}
				},
				{
					rootMargin: `${ margin.bottom }px 0px ${ margin.top }px 0px`,
					threshold: [ 0.01 ],
				}
			)
		);

		observers.get( element ).observe( element );
	}

	function initElement( element ) {
		if ( callbacks.init || __callbacks.init || callbacks.onceInit ) {
			const done = () => {
				dependencyStatus = 'ready';
				dependencyMissedAddElements.forEach( ( elem ) =>
					initElement( elem )
				);
				dependencyMissedAddElements = [];
			};
			const error = ( e ) => {
				observers.get( element ).unobserve( element );
				if ( e ) {
					// eslint-disable-next-line no-console
					console.error( e );
				}
			};

			if ( dependencyStatus === 'ready' ) {
				if ( callbacks.onceInit ) {
					if ( config.doneOnce.init === false ) {
						config.doneOnce.init = true;
						callbacks.onceInit.bind( element )();
						if ( autoStop.onceInit ) {
							stop();
						}
					}
					return;
				}
				if ( __callbacks.init ) {
					__callbacks.init.bind( element )();
				}
				if ( callbacks.init ) {
					callbacks.init.bind( element )();
				}
			} else if ( dependencyStatus === 'not_ready' ) {
				dependencyStatus = 'process';
				dependencyMissedAddElements.push( element );
				dependencyFunction( done, error );
			} else if ( dependencyStatus === 'process' ) {
				dependencyMissedAddElements.push( element );
			}
		}
	}

	function runElement( element, type ) {
		if ( dependencyStatus === 'ready' ) {
			const Type = type.charAt( 0 ).toUpperCase() + type.slice( 1 );

			if ( callbacks[ `once${ Type }` ] ) {
				if ( config.doneOnce[ type ] === false ) {
					config.doneOnce[ type ] = true;
					callbacks[ `once${ Type }` ].bind( element )();
					if ( autoStop[ `once${ Type }` ] ) {
						stop();

						return;
					}
				}
			}

			if ( ! config.doneFirst[ type ].includes( element ) ) {
				config.doneFirst[ type ].push( element );
				if ( __callbacks[ `first${ Type }` ] ) {
					__callbacks[ `first${ Type }` ].bind( element )();
				}
				if ( callbacks[ `first${ Type }` ] ) {
					callbacks[ `first${ Type }` ].bind( element )();
					if ( autoUnobserve[ `first${ Type }` ] ) {
						observers.get( element ).unobserve( element );
					}
				}
			}

			if ( __callbacks[ type ] ) {
				__callbacks[ type ].bind( element )();
			}
			if ( callbacks[ type ] ) {
				callbacks[ type ].bind( element )();
			}
		} else if ( dependencyStatus === 'not_ready' ) {
			const done = () => {
				dependencyStatus = 'ready';
				dependencyMissedCallbacks.forEach(
					( { element: missedElement, type: missedType } ) => {
						runElement( missedElement, missedType );
					}
				);
				dependencyMissedCallbacks = [];
			};
			const error = ( e ) => {
				observers.get( element ).unobserve( element );
				if ( e ) {
					// eslint-disable-next-line no-console
					console.error( e );
				}
			};

			dependencyStatus = 'process';
			dependencyMissedCallbacks.push( { type, element } );
			dependencyFunction( done, error );
		} else if ( dependencyStatus === 'process' ) {
			dependencyMissedCallbacks.push( { type, element } );
		}
	}

	function start() {
		if ( null === observer && isValidSelector( selector ) ) {
			observer = new MutationObserver( ( mutationsList ) => {
				mutationsList.forEach( ( mutation ) => {
					if (
						mutation.type === 'childList' &&
						mutation.addedNodes.length
					) {
						mutation.addedNodes.forEach( ( element ) => {
							if ( element instanceof HTMLElement ) {
								if ( element.matches( selector ) ) {
									addElement( element );
								}

								element
									.querySelectorAll( selector )
									.forEach( ( childElement ) => {
										addElement( childElement );
									} );
							}
						} );
					}
				} );
			} );

			const bodyInit = () => {
				if ( document.body ) {
					observer.observe( document.body, {
						subtree: true,
						childList: true,
					} );
					document
						.querySelectorAll( selector )
						.forEach( ( element ) => addElement( element ) );
				} else {
					window.setTimeout( bodyInit, 50 );
				}
			};

			bodyInit();
		}
	}

	function stop() {
		stopped = true;
		observer.disconnect();

		elements.forEach( ( element ) => {
			if ( observers.has( element ) ) {
				observers.get( element ).disconnect();
			}
		} );
	}

	return Object.freeze( {
		onceInit,
		onceShow,
		onceAlways,
		onceHide,
		init,
		firstShow,
		show,
		firstHide,
		hide,
		firstAlways,
		always,
		dependency,
		setMargin,
	} );
}
