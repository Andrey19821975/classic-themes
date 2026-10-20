import { ThemeHTMLElement } from './theme-html-element.js';

export class TabsButtonThemeHtmlElement extends ThemeHTMLElement {
	static slug = 'theme-tabs-button';

	runAfterChildrenComplete() {
		const attributes = {};

		attributes[ 'aria-selected' ] = 'false';
		attributes.type = 'button';
		attributes.role = 'tab';

		this.getAttributeNames().forEach( ( attributeName ) => {
			attributes[ attributeName ] = this.getAttribute( attributeName );
		} );

		attributes.class = this.getElementClassName();

		const element = this.getNewElement( {
			name: 'button',
			attributes,
			append: this.childNodes,
		} );

		this.replaceCurrent( element );
	}
}
