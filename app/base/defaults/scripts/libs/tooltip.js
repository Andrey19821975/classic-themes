const span = document.createElement( 'span' );
span.setAttribute( 'aria-hidden', 'true' );
span.hidden = true;

document.body.append( span );

let openLink;

function move() {
	const { top: linkTop, left: linkLeft } = openLink.getBoundingClientRect();
	const { height: spanHeight, width: spanWidth } =
		span.getBoundingClientRect();

	span.style.top = `${ Math.max( linkTop - spanHeight, 0 ) }px`;
	span.style.left = `${ Math.max( linkLeft - spanWidth, 0 ) }px`;
}

function close() {
	if ( openLink === document.activeElement ) {
		return;
	}

	span.hidden = true;
	openLink = null;
	window.removeEventListener( 'scroll', move );
}

export default function tooltip( link, message, modifier = '' ) {
	function open() {
		if ( openLink ) {
			close();
		}

		openLink = link;

		span.className = modifier ? `tooltip tooltip_${ modifier }` : 'tooltip';
		span.innerHTML = message;
		span.hidden = false;

		move();

		window.addEventListener( 'scroll', move );
	}

	link.addEventListener( 'focus', open );
	link.addEventListener( 'blur', close );

	return { open, close };
}
