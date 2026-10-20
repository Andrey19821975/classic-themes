module.exports = ( options = { atRuleName: 'modifier' } ) => {
	return {
		postcssPlugin: 'postcss-at-modifier',
		AtRule: {
			[ options.atRuleName ]: ( atRule, { Rule } ) => {
				let block = atRule;
				while (
					block &&
					block.parent &&
					block.parent.type !== 'root'
				) {
					block = block.parent;
				}

				const parent = atRule.parent;
				const [ blockName, elementName ] = block.selector.split( '__' );
				const blockSelectorWithModifications = [];

				atRule.params.split( ' and ' ).forEach( ( modifier ) => {
					const [ modName, modValue ] = modifier
						.replace( /^\(| |\)$/g, '' )
						.split( ',' );
					blockSelectorWithModifications.push(
						`${ blockName }_${ modName }${
							modValue ? '_' + modValue : ''
						}`
					);
				} );

				const blockSelectorWithModification =
					blockSelectorWithModifications.join( '' );
				const nodes = [];
				const pseudoNodes = [];
				const notExtendNodes = [];
				const declarations = [];

				if ( elementName ) {
					nodes.push( ...atRule.nodes );
				} else {
					atRule.each( ( node ) => {
						if ( 'decl' === node.type || 'atrule' === node.type ) {
							declarations.push( node );
						} else if (
							node.selector &&
							node.selector.startsWith( '&::' )
						) {
							pseudoNodes.push( node );
						} else if (
							node.selector &&
							node.selector.startsWith( '&' )
						) {
							nodes.push( node );
						} else {
							notExtendNodes.push( node );
						}
					} );
				}

				if ( nodes.length ) {
					const blockWithModification = new Rule( {
						selector:
							blockSelectorWithModification +
							' ' +
							block.selector,
						nodes,
						source: atRule.source,
					} );

					atRule.root().insertAfter( block, blockWithModification );
				}

				if ( pseudoNodes.length ) {
					const blockWithModification = new Rule( {
						selector: blockSelectorWithModification,
						nodes: pseudoNodes,
						source: atRule.source,
					} );

					atRule.root().insertAfter( block, blockWithModification );
				}

				if ( notExtendNodes.length ) {
					const blockWithModification = new Rule( {
						selector: blockSelectorWithModification,
						nodes: notExtendNodes,
						source: atRule.source,
					} );

					atRule.root().insertAfter( block, blockWithModification );
				}

				if ( declarations.length ) {
					const blockWithModification = new Rule( {
						selector: blockSelectorWithModification,
						nodes: declarations,
						source: atRule.source,
					} );

					atRule.root().insertAfter( block, blockWithModification );
				}

				atRule.remove();

				if ( 0 === parent.nodes.length ) {
					parent.remove();
				}
			},
		},
	};
};

module.exports.postcss = true;
