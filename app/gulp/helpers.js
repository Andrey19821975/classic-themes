import { rimraf } from 'rimraf';
import gulpIf from 'gulp-if';
import { src, dest } from 'gulp';
import {
	ASSETS_PATH,
	APP_DIST_PATH,
	MARKUP_DIST_PATH,
	PRODUCTION,
	CACHE_DIR,
	IS_SERVER,
} from './constants';
import { existsSync, readFileSync, writeFileSync, mkdirSync } from 'fs';
import { resolve, sep } from 'path';

export async function clear( done ) {
	await rimraf( CACHE_DIR );
	await rimraf( APP_DIST_PATH );
	await rimraf( APP_DIST_PATH + '-start' );
	await rimraf( MARKUP_DIST_PATH );

	done();
}

export function copyAssets() {
	return src( ASSETS_PATH ).pipe( appDest() ).pipe( wwwDest() );
}

export function appDest( path = '', options = {} ) {
	return dest(
		`${ IS_SERVER ? APP_DIST_PATH + '-start' : APP_DIST_PATH }/${ path }`,
		options
	);
}

export function wwwDest( path = '', options = {} ) {
	return gulpIf(
		PRODUCTION,
		dest( `${ MARKUP_DIST_PATH }/${ path }`, options )
	);
}

function getLastRunPath( cacheKey ) {
	return resolve(
		CACHE_DIR,
		'last-run',
		cacheKey + ( PRODUCTION ? '-PRD' : '-DEV' )
	);
}

export function getLastRun( cacheKey ) {
	const cachePath = getLastRunPath( cacheKey );

	let lastRun = 0;
	if ( existsSync( cachePath ) ) {
		lastRun = parseInt( readFileSync( cachePath ).toString(), 10 );
	}

	setLastRun( cacheKey );

	return lastRun;
}

export function setLastRun( cacheKey ) {
	const cachePath = getLastRunPath( cacheKey );

	mkdirSync( cachePath.split( sep ).slice( 0, -1 ).join( sep ), {
		recursive: true,
	} );
	writeFileSync( cachePath, Date.now().toString() );
}
