import { ThemeHTMLElement } from './theme-html-element.js';

export class TabsContentThemeHtmlElement extends ThemeHTMLElement {
	static slug = 'theme-tabs-content';

	tabContentIndex = null;

	runAfterChildrenComplete() {
		this.setAttribute( 'role', 'tabpanel' );
	}

	getIndex() {
		return this.tabContentIndex;
	}
}
