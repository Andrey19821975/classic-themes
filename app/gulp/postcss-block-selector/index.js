module.exports = ( options = { parentSelector: '&&' } ) => {
	return {
		postcssPlugin: 'postcss-block-selector',
		Once( root ) {
			root.walkRules( ( rule ) => {
				if ( rule.selector.includes( options.parentSelector ) ) {
					let block = rule;
					while (
						block &&
						block.parent &&
						block.parent.type !== 'root'
					) {
						block = block.parent;
					}

					rule.selector = rule.selector.replaceAll(
						options.parentSelector,
						block.selector
					);
				}
			} );
		},
	};
};

module.exports.postcss = true;
