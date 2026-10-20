<?php

class WLD_Walker_Nav_Menu_Base extends Walker_Nav_Menu {
	protected string $current_link_title = '';

	protected static ?string $expend_icon = null;

	public function start_lvl( &$output, $depth = 0, $args = null ) : void {
		$output .= $this->get_expend_button( $args, $depth );
		$output .= $this->get_start_expend_wrapper( $args, $depth );

		parent::start_lvl( $output, $depth, $args );
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) : void {
		parent::end_lvl( $output, $depth, $args );
		$output .= $this->get_end_expend_wrapper();
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) : void {
		$this->set_current_link_title( $data_object, $args, $depth );
		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );
	}

	protected function set_current_link_title( $data_object, $args, $depth ) : void {
		$title                    = apply_filters( 'the_title', $data_object->title, $data_object->ID );
		$title                    = apply_filters( 'nav_menu_item_title', $title, $data_object, $args, $depth );
		$this->current_link_title = $title;
	}

	/** @noinspection HtmlUnknownAttribute */
	protected function get_start_expend_wrapper( $args, $depth ) : string {
		$level = 0 === $depth ? 'top' : 'sub';
		return sprintf(
			'<div class="%1$s__sub-menu %1$s__sub-menu_level_%2$d %1$s__sub-menu_level_%3$s" %4$s>',
			$args->bam_block_name ?? 'menu',
			++$depth,
			$level,
			$args->expend ? 'hidden' : '',
		);
	}

	protected function get_end_expend_wrapper() : string {
		return '</div>';
	}

	protected function get_expend_button( $args, $depth ) : string {
		if ( $args->expend ) {
			if ( null === self::$expend_icon ) {
				$expend_icon_path = WLD_File::get_assets_path( 'images/menu-marker.svg' );
				if ( file_exists( $expend_icon_path ) ) {
					self::$expend_icon = WLD_Filesystem::get_file_contents( $expend_icon_path );
				} else {
					self::$expend_icon = '';
				}
			}

			$submenu_title = sprintf( // translators: %s: Menu name for submenu.
				__( 'Submenu for "%s"', 'theme' ),
				$this->current_link_title
			);

			$level = 0 === $depth ? 'top' : 'sub';

			return sprintf(
				'
				<button class="%1$s__expend-button %1$s__expend-button_level_%2$d %1$s__expend-button_level_%3$s" aria-expanded="false">
					%4$s
					<span class="screen-reader-text">%5$s</span>
				</button>',
				$args->bam_block_name ?? 'menu',
				++$depth,
				$level,
				self::$expend_icon,
				$submenu_title
			);
		}

		return '';
	}
}
