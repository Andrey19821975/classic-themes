export class ThemeHTMLElement extends HTMLElement {
	static slug = 'theme-element';

	currentElement = this;
	childrenNames = [];
	childrenElements = {};
	readyStatus = false;
	connectedStatus = false;
	blockName = '';

	connectedCallback() {
		if ( this.connectedStatus ) {
			return;
		}

		this.connectedStatus = true;

		const childrenComplete = [];
		this.childrenNames.forEach( ( name ) => {
			this.childrenElements[ name ] = [];

			this.querySelectorAll( `:scope > ${ name }` ).forEach(
				( childElement ) => {
					this.childrenElements[ name ].push( childElement );

					childrenComplete.push(
						new Promise( ( resolve ) => {
							childElement.addEventListener(
								'complete-child',
								resolve,
								{ once: true }
							);
						} )
					);
				}
			);
		} );

		this.runBeforeChildrenComplete();

		Promise.all( childrenComplete ).then( () => {
			this.runAfterChildrenComplete();

			this.runBeforeComplete();
			this.dispatchEvent(
				new CustomEvent( 'complete-child', {
					bubbles: true,
				} )
			);
			this.runAfterComplete();

			this.readyStatus = true;
			this.dispatchEvent(
				new CustomEvent( `${ this.constructor.slug }-ready`, {
					bubbles: true,
					cancelable: true,
				} )
			);
		} );
	}

	runBeforeChildrenComplete() {
	}

	runAfterChildrenComplete() {
	}

	runBeforeComplete() {
	}

	runAfterComplete() {
	}

	getBlockName() {
		if ( this.blockName ) {
			return this.blockName;
		}

		if ( this.hasAttribute( 'block-name' ) ) {
			return this.getAttribute( 'block-name' );
		}

		const className = this.classList.item( 0 );
		if ( className ) {
			return className.split( '__' )[ 0 ] || this.getSlugName();
		}

		return this.getSlugName();
	}

	getModifiers() {
		const modifiers = this.getAttribute( 'modifier' ) || this.getAttribute( 'modifiers' );
		return modifiers ? modifiers.split( ' ' ) : [];
	}

	getSlugName( slug = '' ) {
		if ( slug ) {
			return slug.replace( /^theme-/, '' );
		}
		return this.constructor.slug.replace( /^theme-/, '' );
	}

	getBlockClassName( modifiers = [], removeModifiers = [] ) {
		const blockName = this.getBlockName();
		const blockClassName =
			blockName === this.getSlugName()
				? blockName
				: `${ blockName }__${ this.getSlugName() }`;

		let className = blockClassName;

		modifiers.push( ...this.getModifiers() );
		modifiers.forEach( ( blockModifier ) => {
			if ( blockModifier ) {
				className += ` ${ blockClassName }_${ blockModifier }`;
			}
		} );

		const classes = `${ className } ${ this.currentElement.className }`
			.trim().split( / +/ )
			.filter( ( singleClassName, index, a ) => a.indexOf( singleClassName ) === index );

		if ( removeModifiers.length ) {
			const removeClassNames = []
			removeModifiers.forEach( ( modifier ) => {
				if ( modifier ) {
					removeClassNames.push( `${ blockClassName }_${ modifier }` );
				}
			} );

			return classes.filter( singleClassName => ! removeClassNames.includes( singleClassName ) ).join( ' ' );
		}

		return classes.join( ' ' );
	}

	getElementClassName( modifiers = [], removeModifiers = [] ) {
		const blockName = this.getBlockName();
		const elementName = this.getSlugName();
		const elementClassName = blockName === elementName ? elementName : `${ blockName }__${ elementName }`;

		let className = elementClassName;

		modifiers.push( ...this.getModifiers() );
		modifiers.forEach( ( modifier ) => {
			if ( modifier ) {
				className += ` ${ elementClassName }_${ modifier }`;
			}
		} );

		const classes = `${ className } ${ this.currentElement.className }`
			.trim().split( / +/ )
			.filter( ( singleClassName, index, a ) => a.indexOf( singleClassName ) === index );

		if ( removeModifiers.length ) {
			const removeClassNames = []
			removeModifiers.forEach( ( modifier ) => {
				if ( modifier ) {
					removeClassNames.push( `${ elementClassName }_${ modifier }` );
				}
			} );

			return classes.filter( singleClassName => ! removeClassNames.includes( singleClassName ) ).join( ' ' );
		}

		return classes.join( ' ' );
	}

	getNewElement( data ) {
		data = { ...{ name: '', properties: {}, attributes: {}, append: [] }, ...data };

		const element = document.createElement( data.name );
		if ( data.attributes ) {
			for ( const attributeName in data.attributes ) {
				element.setAttribute(
					attributeName,
					data.attributes[ attributeName ]
				);
			}
		}
		if ( data.properties ) {
			for ( const propertyName in data.properties ) {
				element[ propertyName ] = data.properties[ propertyName ];
			}
		}

		if ( data.append ) {
			element.append( ...data.append );
		}

		return element;
	}

	replaceCurrent( newElement ) {
		this.currentElement = newElement;
		this.replaceWith( newElement );
	}

	addEventListener( type, listener, options ) {
		if ( `${ this.constructor.slug }-ready` === type && this.readyStatus ) {
			listener();
		} else {
			super.addEventListener( type, listener, options );
		}
	}
}
