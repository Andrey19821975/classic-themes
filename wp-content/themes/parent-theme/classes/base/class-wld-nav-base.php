<?php

class WLD_Nav_Base {
	public static array $navs = array();

	public static function init() : void {
		add_action( 'after_setup_theme', array( static::class, 'register' ) );
	}

	public static function get_location( string $label ) : string {
		return mb_strtolower( str_replace( ' ', '_', $label ) . '_location' );
	}

	public static function register() : void {
		register_nav_menus( static::$navs );
	}

	public static function add( string $label ) : void {
		static::$navs[ static::get_location( $label ) ] = $label;
	}

	public static function get_bam_block_name( string $label ) : string {
		return mb_strtolower( 'menu-' . str_replace( ' ', '-', $label ) );
	}

	public static function has_sub_menu( array $menu_location_labels ) : bool {
		$locations = get_nav_menu_locations();
		foreach ( $menu_location_labels as $menu_location_label ) {
			$menu_locations = static::get_location( $menu_location_label );
			$menu_id        = $locations[ $menu_locations ] ?? 0;
			if ( $menu_id ) {
				$menu_items = wp_get_nav_menu_items( $menu_id );
				if ( $menu_items ) {
					foreach ( $menu_items as $menu_item ) {
						if ( $menu_item->menu_item_parent ) {
							return true;
						}
					}
				}
			}
		}

		return false;
	}

	public static function get_the_nav( string $location_label, bool $is_mobile = false, array $args = array() ) : string {
		$defaults = array(
			'keyboard' => false,
			'expend'   => false,
			'hover'    => false,
		);

		$defaults['bam_block_name'] = WLD_Nav::get_bam_block_name( $location_label );
		if ( $is_mobile ) {
			$defaults['bam_block_name'] .= '-mobile';
		}

		$container_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
			'Footer Main'   => true,
		);

		if ( isset( $container_navs[ $location_label ] ) ) {
			$defaults['container'] = 'nav';
		}

		$keyboard_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
			'Footer Main'   => true,
			'Footer Links'  => true,
		);

		if ( isset( $keyboard_navs[ $location_label ] ) ) {
			$defaults['keyboard'] = $is_mobile ? false : 'auto-close';
		}

		$expend_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
		);

		if ( isset( $expend_navs[ $location_label ] ) ) {
			$defaults['expend'] = $is_mobile ? true : 'auto-close';
		}

		$hover_navs = array(
			'Header Main'   => true,
			'Header Second' => true,
		);

		if ( isset( $hover_navs[ $location_label ] ) ) {
			$defaults['hover'] = ! $is_mobile;
		}

		$defaults['theme_location']       = WLD_Nav::get_location( $location_label );
		$defaults['fallback_cb']          = '__return_empty_string';
		$defaults['is_mobile']            = $is_mobile;
		$defaults['echo']                 = false;
		$defaults['container_aria_label'] = $location_label;
		$defaults['items_wrap']           = '<ul class="%2$s">%3$s</ul>';
		$defaults['walker']               = new WLD_Walker_Nav_Menu();

		$args = wp_parse_args( $args, $defaults );

		$attributes = '';
		foreach ( array( 'expend', 'keyboard', 'hover' ) as $attribute ) {
			if ( ! empty( $args[ $attribute ] ) ) {
				$attributes .= ' data-' . $attribute . '-menu';
				if ( is_string( $args[ $attribute ] ) ) {
					$attributes .= '="' . $args[ $attribute ] . '"';
				}
			}
		}

		if ( $attributes ) {
			$args['items_wrap'] = str_replace( '<ul', '<ul' . $attributes, $args['items_wrap'] );
		}

		return (string) wp_nav_menu( $args );
	}
}
