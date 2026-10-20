module.exports = function ( value, defaultValue ) {
	if ( typeof value === 'undefined' ) {
		return defaultValue;
	}

	return value;
};
