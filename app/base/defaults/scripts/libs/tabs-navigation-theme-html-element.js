import { ThemeHTMLElement } from './theme-html-element.js';

export class TabsNavigationThemeHtmlElement extends ThemeHTMLElement {
	static slug = 'theme-tabs-navigation';

	runAfterChildrenComplete() {
		this.className = this.getElementClassName();
		this.setAttribute( 'role', 'tablist' );
	}
}
