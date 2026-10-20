const path = require( 'path' );

module.exports = function ( svgFileName ) {
	return '.svg' === path.extname( svgFileName );
};
