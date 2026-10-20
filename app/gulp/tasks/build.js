import { parallel } from 'gulp';
import { copyAssets } from '../helpers';
import { pages } from './templates';
import { styles } from './styles';
import { fonts } from './fonts';
import { libs, modules, npmProxy, scripts } from './scripts';
import { images } from './images';

export function build( cb ) {
	return parallel(
		pages,
		styles,
		fonts,
		scripts,
		modules,
		npmProxy,
		libs,
		images,
		copyAssets
	)( cb );
}
