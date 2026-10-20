import tooltip from './tooltip';

export default function menuTooltip( menu, hasChildren, modifier ) {
	const message = hasChildren
		? 'Use &#8592; &#8595; &#8594; to navigate'
		: 'Use &#8592; &#8594; to navigate';

	const a = menu.querySelector( 'a' );
	const { open } = tooltip( a, message, modifier );

	open();
}
