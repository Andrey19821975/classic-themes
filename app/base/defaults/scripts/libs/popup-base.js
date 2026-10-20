export class PopupBase {
	constructor( dialog, control, isModal = true, isCloseOutsideClick = true ) {
		this.dialog = dialog;
		this.control = control;
		this.isModal = isModal;

		this.dialog.addEventListener( 'close', () => {
			document.documentElement.style.overflow = null;
			document.documentElement.style.scrollbarGutter = null;
		} );

		this.dialog.addEventListener( 'click', ( { target } ) => {
			if (
				target.matches( '.popup__close-btn' ) ||
				target.closest( '.popup__close-btn' )
			) {
				this.close();
			}
		} );

		this.dialog
			.querySelectorAll( '.popup__close-btn' )
			.forEach( ( closeButton ) =>
				closeButton.addEventListener( 'click', this.close.bind( this ) )
			);

		if ( isCloseOutsideClick ) {
			this.dialog.addEventListener( 'click', ( { target } ) => {
				if (
					this.dialog === target ||
					! this.dialog.contains( target )
				) {
					this.close();
				}
			} );
		}
	}

	open() {
		this.control.setAttribute( 'aria-expanded', 'true' );
		if ( this.isModal === true ) {
			document.documentElement.style.overflow = 'hidden';
			document.documentElement.style.scrollbarGutter = 'stable';
			this.dialog.classList.add( 'popup_modal' );
			this.dialog.showModal();
		} else {
			this.dialog.classList.remove( 'popup_modal' );
			this.dialog.show();
		}
		this.dialog.classList.add( 'popup_open' );
	}

	close() {
		this.dialog.classList.remove( 'popup_open' );
		Promise.all(
			this.dialog
				.getAnimations( { subtree: true } )
				.map( ( animation ) => animation.finished )
		).then( () => {
			this.dialog.close();
			this.control.setAttribute( 'aria-expanded', 'false' );
		} );
	}

	getCloseSvg() {
		return `
			<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" aria-hidden="true" class="popup__close-icon" viewBox="0 0 18 18">
				<path d="M.93 18a.926.926 0 0 1-.654-1.581L16.422.27a.926.926 0 1 1 1.31 1.31L1.584 17.728A.929.929 0 0 1 .929 18Z"/>
				<path d="M17.078 18a.919.919 0 0 1-.654-.272L.275 1.581a.926.926 0 0 1 1.31-1.31L17.732 16.42A.926.926 0 0 1 17.078 18Z"/>
			</svg>
		`;
	}
}
