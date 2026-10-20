import { ThemeHTMLElement } from './theme-html-element.js';

export class TabsWrapperThemeHtmlElement extends ThemeHTMLElement {
	static slug = 'theme-tabs-wrapper';

	runAfterChildrenComplete() {
		this.className = this.getElementClassName();
	}
}
