import { TabsThemeHTMLElement } from '../libs/tabs-theme-html-element.js';
import { TabsButtonThemeHtmlElement } from '../libs/tabs-button-theme-html-element.js';
import { TabsContentThemeHtmlElement } from '../libs/tabs-content-theme-html-element.js';
import { TabsWrapperThemeHtmlElement } from '../libs/tabs-wrapper-theme-html-element.js';
import { TabsNavigationThemeHtmlElement } from '../libs/tabs-navigation-theme-html-element.js';

customElements.define(
	TabsButtonThemeHtmlElement.slug,
	TabsButtonThemeHtmlElement
);
customElements.define(
	TabsContentThemeHtmlElement.slug,
	TabsContentThemeHtmlElement
);
customElements.define(
	TabsWrapperThemeHtmlElement.slug,
	TabsWrapperThemeHtmlElement
);
customElements.define(
	TabsNavigationThemeHtmlElement.slug,
	TabsNavigationThemeHtmlElement
);
customElements.define( TabsThemeHTMLElement.slug, TabsThemeHTMLElement );
