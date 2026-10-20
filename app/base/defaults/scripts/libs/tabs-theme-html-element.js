import { ThemeHTMLElement } from './theme-html-element.js';
import { TabsButtonThemeHtmlElement } from './tabs-button-theme-html-element.js';
import { TabsContentThemeHtmlElement } from './tabs-content-theme-html-element.js';
import { TabsWrapperThemeHtmlElement } from './tabs-wrapper-theme-html-element.js';
import { TabsNavigationThemeHtmlElement } from './tabs-navigation-theme-html-element.js';

export class TabsThemeHTMLElement extends ThemeHTMLElement {
	static tabIndex = 0;
	static slug = 'theme-tabs';

	childrenNames = [
		TabsButtonThemeHtmlElement.slug,
		TabsContentThemeHtmlElement.slug,
		TabsNavigationThemeHtmlElement.slug,
		TabsWrapperThemeHtmlElement.slug,
	];
	firstButton = null;
	lastButton = null;
	buttons = [];
	contents = [];

	constructor() {
		super();
		++this.constructor.tabIndex;
	}

	runBeforeChildrenComplete() {
		const blockName = this.getBlockName();
		const buttons =
			this.childrenElements[ TabsButtonThemeHtmlElement.slug ];
		const contents =
			this.childrenElements[ TabsContentThemeHtmlElement.slug ];
		const navigations =
			this.childrenElements[ TabsNavigationThemeHtmlElement.slug ];
		const wrappers =
			this.childrenElements[ TabsWrapperThemeHtmlElement.slug ];

		buttons.forEach( ( button, index ) => {
			const content = contents[ index ];
			const buttonId = `tabs-${
				this.constructor.tabIndex
			}-button-${ ++index }`;
			const contentId = `tabs-${ this.constructor.tabIndex }-content-${ index }`;

			button.setAttribute( 'aria-controls', contentId );
			button.setAttribute( 'id', buttonId );
			button.blockName = blockName;

			content.setAttribute( 'id', contentId );
			content.setAttribute( 'aria-labelledby', buttonId );
			content.blockName = blockName;
			content.tabContentIndex = index - 1;
		} );

		if ( navigations.length ) {
			navigations[ 0 ].append( ...buttons );
			navigations[ 0 ].blockName = blockName;
		} else {
			const navigation = this.getNewElement( {
				name: TabsNavigationThemeHtmlElement.slug,
				append: buttons,
			} );

			navigation.blockName = blockName;

			this.prepend( navigation );
		}

		if ( wrappers.length ) {
			wrappers[ 0 ].append( ...contents );
			wrappers[ 0 ].blockName = blockName;
		} else {
			const wrapper = this.getNewElement( {
				name: TabsWrapperThemeHtmlElement.slug,
				append: contents,
			} );

			wrapper.blockName = blockName;

			this.append( wrapper );
		}
	}

	runBeforeComplete() {
		this.buttons = this.childrenElements[ TabsButtonThemeHtmlElement.slug ];
		this.contents =
			this.childrenElements[ TabsContentThemeHtmlElement.slug ];

		this.addAllTab().then( () => {
			this.firstButton = this.buttons[ 0 ].currentElement;
			this.lastButton =
				this.buttons[ this.buttons.length - 1 ].currentElement;

			this.buttons.forEach( ( button ) => {
				button.currentElement.addEventListener(
					'keydown',
					this.onKeydownByTab.bind( this )
				);
				button.currentElement.addEventListener(
					'click',
					this.onClickByTab.bind( this )
				);
			} );

			this.setCurrentTab( this.firstButton );
		} );
	}

	runAfterComplete() {
		this.classList.add(
			...this.getBlockClassName( [ 'ready' ] ).split( ' ' )
		);
	}

	setCurrentTab( currentButton ) {
		this.buttons.forEach( ( button, index ) => {
			const buttonElement = button.currentElement;
			if ( currentButton === buttonElement ) {
				const buttonActiveClassName = button.getElementClassName( [
					'active',
				] );
				const contentActiveClassName = this.contents[
					index
				].getElementClassName( [ 'active' ] );

				buttonElement.setAttribute( 'aria-selected', 'true' );
				buttonElement.removeAttribute( 'tabindex' );
				buttonElement.className = buttonActiveClassName;
				this.contents[ index ].hidden = false;
				this.contents[ index ].className = contentActiveClassName;
			} else {
				const buttonClassName = button.getElementClassName(
					[],
					[ 'active' ]
				);
				const contentClassName = this.contents[
					index
				].getElementClassName( [], [ 'active' ] );

				buttonElement.setAttribute( 'aria-selected', 'false' );
				buttonElement.setAttribute( 'tabindex', '-1' );
				buttonElement.className = buttonClassName;
				this.contents[ index ].hidden = true;
				this.contents[ index ].className = contentClassName;
			}
		} );
	}

	moveFocusToPreviousTab( currentButton ) {
		if ( currentButton === this.firstButton ) {
			this.lastButton.focus();
		} else {
			this.buttons[
				this.buttons
					.map( ( button ) => button.currentElement )
					.indexOf( currentButton ) - 1
			].currentElement.focus();
		}
	}

	moveFocusToNextTab( currentButton ) {
		if ( currentButton === this.lastButton ) {
			this.firstButton.focus();
		} else {
			this.buttons[
				this.buttons
					.map( ( button ) => button.currentElement )
					.indexOf( currentButton ) + 1
			].currentElement.focus();
		}
	}

	async addAllTab() {
		return new Promise( ( resolve ) => {
			const allTitle = this.getAttribute( 'all' );
			if ( allTitle ) {
				let allButtonReady = false;
				let allContentReady = false;

				const allButton = this.buttons[ 0 ].cloneNode( true );

				allButton.blockName = this.buttons[ 0 ].blockName;
				allButton.addEventListener(
					'complete-child',
					() => {
						allButtonReady = true;
						if ( allContentReady ) {
							resolve();
						}
					},
					{ once: true }
				);

				[ 'id', 'aria-controls' ].forEach( ( attributeName ) => {
					allButton.setAttribute(
						attributeName,
						allButton
							.getAttribute( attributeName )
							.replace( /-1$/, '' ) + '-all'
					);
				} );

				allButton.currentElement.innerHTML = allTitle;
				this.buttons[ 0 ].currentElement.before( allButton );
				this.buttons.unshift( allButton );

				const allContent = this.contents[ 0 ].cloneNode( true );
				allContent.addEventListener(
					'complete-child',
					() => {
						allContentReady = true;
						if ( allButtonReady ) {
							resolve();
						}
					},
					{ once: true }
				);

				this.contents.forEach( ( content, index ) => {
					if ( index ) {
						allContent.append(
							...content.cloneNode( true ).childNodes
						);
					}
				} );

				[ 'id', 'aria-labelledby' ].forEach( ( attributeName ) => {
					allContent.setAttribute(
						attributeName,
						allContent
							.getAttribute( attributeName )
							.replace( /-1$/, '' ) + '-all'
					);
				} );

				[ 'id', 'for', 'aria-labelledby', 'aria-controls' ].forEach(
					( attributeName ) => {
						this.setAttributeAllPostfix(
							allContent,
							attributeName
						);
					}
				);

				this.contents[ 0 ].before( allContent );
				this.contents.unshift( allContent );
			} else {
				resolve();
			}
		} );
	}

	setAttributeAllPostfix( allContent, attributeName ) {
		allContent
			.querySelectorAll( `[${ attributeName }]` )
			.forEach( ( element ) => {
				element.setAttribute(
					attributeName,
					element.getAttribute( attributeName ) + '-all'
				);
			} );
	}

	onKeydownByTab( event ) {
		const currentButton = event.currentTarget;
		let isStopEvent = false;

		switch ( event.key ) {
			case 'ArrowLeft':
			case 'ArrowUp':
				this.moveFocusToPreviousTab( currentButton );
				isStopEvent = true;
				break;
			case 'ArrowRight':
			case 'ArrowDown':
				this.moveFocusToNextTab( currentButton );
				isStopEvent = true;
				break;
			case 'Home':
				this.firstButton.focus();
				isStopEvent = true;
				break;
			case 'End':
				this.lastButton.focus();
				isStopEvent = true;
				break;
			default:
				break;
		}

		if ( isStopEvent ) {
			event.stopPropagation();
			event.preventDefault();
		}
	}

	onClickByTab( event ) {
		this.setCurrentTab( event.currentTarget );
	}

	openTab( index ) {
		this.setCurrentTab( this.buttons[ index ].currentElement );
	}
}
