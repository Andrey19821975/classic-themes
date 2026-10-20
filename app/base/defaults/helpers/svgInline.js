const Handlebars = require( 'handlebars' );
const path = require( 'path' );
const fs = require( 'fs' );

const root = path.resolve( __dirname, '../../../' );
const base = path.resolve( root, 'base/defaults/images' );
const src = path.resolve( root, 'src/images' );

module.exports = function ( svgPath, className = '' ) {
	let svg = '';
	let svgPathExists = '';
	if ( fs.existsSync( path.resolve( src, svgPath ) ) ) {
		svgPathExists = path.resolve( src, svgPath );
	} else if ( fs.existsSync( path.resolve( base, svgPath ) ) ) {
		svgPathExists = path.resolve( base, svgPath );
	}

	if ( svgPathExists ) {
		svg = fs
			.readFileSync( svgPathExists )
			.toString()
			.replace( '<svg ', '<svg aria-hidden="true" ' );
	}

 	if ( className && typeof className === 'string' ) {
		svg = svg.replace( '<svg ', '<svg class="' + className + '" ' );
	}

	return new Handlebars.SafeString( '<!-- ' + svgPath + ' -->\r\n' + svg );
};
