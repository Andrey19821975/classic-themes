<?php

class WLD_KSES_Base {
	protected static array $allowed_tags = array(
		'dialog'  => array(
			'open' => true,
		),
		'iframe'  => array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'title'           => true,
		),
		'img'     => array(
			'decoding'      => true,
			'sizes'         => true,
			'srcset'        => true,
			'fetchpriority' => true,
		),
		'form'    => array(
			'action'         => true,
			'accept'         => true,
			'accept-charset' => true,
			'enctype'        => true,
			'method'         => true,
			'name'           => true,
			'target'         => true,
		),
		'input'   => array(
			'accept'          => true,
			'accesskey'       => true,
			'align'           => true,
			'alt'             => true,
			'autocomplete'    => true,
			'autofocus'       => true,
			'checked'         => true,
			'contenteditable' => true,
			'dirname'         => true,
			'disabled'        => true,
			'draggable'       => true,
			'dropzone'        => true,
			'form'            => true,
			'formaction'      => true,
			'formenctype'     => true,
			'formmethod'      => true,
			'formnovalidate'  => true,
			'formtarget'      => true,
			'height'          => true,
			'hidden'          => true,
			'lang'            => true,
			'list'            => true,
			'max'             => true,
			'maxlength'       => true,
			'min'             => true,
			'multiple'        => true,
			'name'            => true,
			'pattern'         => true,
			'placeholder'     => true,
			'readonly'        => true,
			'required'        => true,
			'size'            => true,
			'spellcheck'      => true,
			'src'             => true,
			'step'            => true,
			'translate'       => true,
			'type'            => true,
			'value'           => true,
			'width'           => true,
		),
		'select'  => array(
			'accesskey'       => true,
			'autofocus'       => true,
			'contenteditable' => true,
			'disabled'        => true,
			'draggable'       => true,
			'dropzone'        => true,
			'form'            => true,
			'hidden'          => true,
			'lang'            => true,
			'multiple'        => true,
			'name'            => true,
			'onblur'          => true,
			'onchange'        => true,
			'oncontextmenu'   => true,
			'onfocus'         => true,
			'oninput'         => true,
			'oninvalid'       => true,
			'onreset'         => true,
			'onsearch'        => true,
			'onselect'        => true,
			'onsubmit'        => true,
			'required'        => true,
			'size'            => true,
			'spellcheck'      => true,
			'translate'       => true,
		),
		'option'  => array(
			'disabled' => true,
			'label'    => true,
			'selected' => true,
			'value'    => true,
		),
		'button'  => array(
			'aria-expanded' => true,
			'aria-controls' => true,
		),
		'picture' => array(),
		'source'  => array(
			'type'   => true,
			'srcset' => true,
			'sizes'  => true,
			'media'  => array(
				'required' => true,
			),
			'height' => true,
			'width'  => true,
		),
	);

	// There are no filters https://developer.mozilla.org/en-US/docs/Web/SVG/Element#filter_primitive_elements
	// And there are no deprecated tags https://developer.mozilla.org/en-US/docs/Web/SVG/Element#obsolete_and_deprecated_elements
	protected static array $allowed_svg_tags = array(
		'a'                => array(
			'download',
			'href',
			'hreflang',
			'ping',
			'referrerpolicy',
			'rel',
			'target',
			'type',
			'type',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'animate'          => array(
			array(
				'animation' => array( 'timing', 'value', 'other' ),
				'global'    => array( 'core', 'style' ),
			),
		),
		'animatemotion'    => array(
			'keypoints',
			'path',
			'rotate',
			array(
				'animation' => array( 'timing', 'value', 'other' ),
				'global'    => array( 'core', 'style' ),
			),
		),
		'animatetransform' => array(
			'by',
			'from',
			'to',
			'type',
			array(
				'animation' => array( 'target_attribute', 'timing', 'value', 'other' ),
				'global'    => array( 'core' ),
			),
		),
		'circle'           => array(
			'cx',
			'cy',
			'r',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'clippath'         => array(
			'clippathunits',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'defs'             => array(
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'desc'             => array(
			array(
				'global' => array( 'core', 'style' ),
			),
		),
		'ellipse'          => array(
			'cx',
			'cy',
			'rx',
			'ry',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'g'                => array(
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'image'            => array(
			'x',
			'y',
			'width',
			'height',
			'href',
			'preserveaspectratio',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'line'             => array(
			'x1',
			'x2',
			'y1',
			'y2',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'lineargradient'   => array(
			'gradientunits',
			'gradienttransform',
			'href',
			'spreadmethod',
			'x1',
			'x2',
			'y1',
			'y2',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'marker'           => array(
			'markerheight',
			'markerunits',
			'markerwidth',
			'orient',
			'preserveaspectratio',
			'refx',
			'refy',
			'viewbox',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'mask'             => array(
			'height',
			'maskcontentunits',
			'maskunits',
			'x',
			'y',
			'width',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'metadata'         => array(
			array(
				'global' => array( 'core' ),
			),
		),
		'mpath'            => array(
			array(
				'global' => array( 'core' ),
			),
		),
		'path'             => array(
			'd',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'pattern'          => array(
			'height',
			'href',
			'patterncontentunits',
			'patterntransform',
			'patternunits',
			'preserveaspectratio',
			'viewbox',
			'width',
			'x',
			'y',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'polygon'          => array(
			'points',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'polyline'         => array(
			'points',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'radialgradient'   => array(
			'cx',
			'cy',
			'fr',
			'fr',
			'fy',
			'gradientunits',
			'gradienttransform',
			'href',
			'r',
			'spreadmethod',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'rect'             => array(
			'x',
			'y',
			'width',
			'height',
			'rx',
			'ry',
			'pathlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'set'              => array(
			'to',
			array(
				'animation' => array( 'timing', 'other' ),
				'global'    => array( 'core', 'style' ),
			),
		),
		'stop'             => array(
			'offset',
			'stop-color',
			'stop-opacity',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'style'            => array(
			'type',
			'media',
			'title',
			array(
				'global' => array( 'core', 'style' ),
			),
		),
		'svg'              => array(
			'height',
			'preserveaspectratio',
			'viewbox',
			'width',
			'x',
			'y',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'switch'           => array(
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
			),
		),
		'symbol'           => array(
			'height',
			'preserveaspectratio',
			'refx',
			'refy',
			'viewbox',
			'width',
			'x',
			'y',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'text'             => array(
			'x',
			'y',
			'dx',
			'dy',
			'rotate',
			'lengthadjust',
			'textlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'textpath'         => array(
			'href',
			'lengthadjust',
			'method',
			'path',
			'side',
			'spacing',
			'startoffset',
			'textlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'title'            => array(
			array(
				'global' => array( 'core', 'style' ),
			),
		),
		'tspan'            => array(
			'x',
			'y',
			'dx',
			'dy',
			'rotate',
			'lengthadjust',
			'textlength',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'use'              => array(
			'href',
			'x',
			'y',
			'width',
			'height',
			array(
				'global' => array( 'core', 'style' ),
				'presentation',
				'aria',
			),
		),
		'view'             => array(
			'viewbox',
			'preserveaspectratio',
			array(
				'global' => array( 'core' ),
			),
		),
	);

	// There are no filters https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute#filters_attributes
	// And there are no events https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute#event_attributes
	// And there are no conditional processing attributes.
	protected static array $allowed_svg_attr = array(
		'global'       => array(
			'core'  => array(
				'id',
				'lang',
				'tabindex',
				'xml:base',
				'xml:lang',
				'xml:space',
			),
			'style' => array(
				'class',
				'style',
			),
		),
		'presentation' => array(
			'alignment-baseline',
			'baseline-shift',
			'clip',
			'clip-path',
			'clip-rule',
			'color',
			'color-interpolation',
			'color-interpolation-filters',
			'color-profile',
			'color-rendering',
			'cursor',
			'd',
			'direction',
			'display',
			'dominant-baseline',
			'enable-background',
			'fill',
			'fill-opacity',
			'fill-rule',
			'filter',
			'flood-color',
			'flood-opacity',
			'font-family',
			'font-size',
			'font-size-adjust',
			'font-stretch',
			'font-style',
			'font-variant',
			'font-weight',
			'glyph-orientation-horizontal',
			'glyph-orientation-vertical',
			'image-rendering',
			'kerning',
			'letter-spacing',
			'lighting-color',
			'marker-end',
			'marker-mid',
			'marker-start',
			'mask',
			'opacity',
			'overflow',
			'pointer-events',
			'shape-rendering',
			'solid-color',
			'solid-opacity',
			'stop-color',
			'stop-opacity',
			'stroke',
			'stroke-dasharray',
			'stroke-dashoffset',
			'stroke-linecap',
			'stroke-linejoin',
			'stroke-miterlimit',
			'stroke-opacity',
			'stroke-width',
			'text-anchor',
			'text-decoration',
			'text-rendering',
			'transform',
			'unicode-bidi',
			'vector-effect',
			'visibility',
			'word-spacing',
			'writing-mode',
		),
		'animation'    => array(
			'target_element'   => array(
				'href',
			),
			'target_attribute' => array(
				'attributetype',
				'attributename',
			),
			'timing'           => array(
				'begin',
				'dur',
				'end',
				'min',
				'max',
				'restart',
				'repeatcount',
				'repeatdur',
				'fill',
			),
			'value'            => array(
				'calcmode',
				'values',
				'keytimes',
				'keysplines',
				'from',
				'to',
				'by',
				'autoreverse',
				'accelerate',
				'decelerate',
			),
			'other'            => array(
				'additive',
				'accumulate',
			),
		),
		'aria'         => array(
			'aria-activedescendant',
			'aria-atomic',
			'aria-autocomplete',
			'aria-busy',
			'aria-checked',
			'aria-colcount',
			'aria-colindex',
			'aria-colspan',
			'aria-controls',
			'aria-current',
			'aria-describedby',
			'aria-details',
			'aria-disabled',
			'aria-dropeffect',
			'aria-errormessage',
			'aria-expanded',
			'aria-flowto',
			'aria-grabbed',
			'aria-haspopup',
			'aria-hidden',
			'aria-invalid',
			'aria-keyshortcuts',
			'aria-label',
			'aria-labelledby',
			'aria-level',
			'aria-live',
			'aria-modal',
			'aria-multiline',
			'aria-multiselectable',
			'aria-orientation',
			'aria-owns',
			'aria-placeholder',
			'aria-posinset',
			'aria-pressed',
			'aria-readonly',
			'aria-relevant',
			'aria-required',
			'aria-roledescription',
			'aria-rowcount',
			'aria-rowindex',
			'aria-rowspan',
			'aria-selected',
			'aria-setsize',
			'aria-sort',
			'aria-valuemax',
			'aria-valuemin',
			'aria-valuenow',
			'aria-valuetext',
			'role',
		),
	);

	public static function init() : void {
		static::$allowed_tags = array_map(
			array( static::class, 'add_global_attributes' ),
			static::$allowed_tags
		);

		add_filter(
			'wp_kses_allowed_html',
			array( static::class, 'wp_kses_allowed_html_hook' ),
			10,
			2
		);
	}

	public static function wp_kses_allowed_html_hook( array $allowed_tags, string $context ) : array {
		static $_allowed_tags = null;

		if ( 'post' === $context ) {
			if ( null === $_allowed_tags ) {
				$_allowed_tags = $allowed_tags;
				foreach ( static::$allowed_tags as $allowed_tag => $allowed_attributes ) {
					if ( ! isset( $_allowed_tags[ $allowed_tag ] ) ) {
						$_allowed_tags[ $allowed_tag ] = $allowed_attributes;
					} else {
						$_allowed_tags[ $allowed_tag ] = array_merge(
							$allowed_attributes,
							$allowed_tags[ $allowed_tag ]
						);
					}
				}
				$exclude_tabindex = array(
					'br'     => true,
					'dialog' => true,
					'option' => true,
				);
				foreach ( $_allowed_tags as $allowed_tag => $allowed_attributes ) {
					if ( ! isset( $exclude_tabindex[ $allowed_tag ] ) ) {
						$_allowed_tags[ $allowed_tag ]['tabindex'] = true;
					}
				}
			}

			/** @noinspection CallableParameterUseCaseInTypeContextInspection */
			$allowed_tags = $_allowed_tags;
		}

		return $allowed_tags;
	}

	public static function get_by_tag( $tag, $included_wrapper_tags = true ) : array {
		static $allowed_html = null;

		if ( null === $allowed_html ) {
			$allowed_html = wp_kses_allowed_html( 'post' );
		}

		if ( $included_wrapper_tags ) {
			return array(
				$tag     => $allowed_html[ $tag ] ?? array(),
				'div'    => $allowed_html['div'] ?? array(),
				'span'   => $allowed_html['span'] ?? array(),
				'strong' => $allowed_html['strong'] ?? array(),
				'em'     => $allowed_html['em'] ?? array(),
			);
		}

		return array(
			$tag => $allowed_html[ $tag ] ?? array(),
		);
	}

	public static function get_by_nav() : array {
		return array_merge(
			wp_kses_allowed_html( 'post' ),
			self::get_by_svg(),
			self::get_by_form(),
		);
	}

	public static function get_by_form() : array {
		return array_merge(
			self::get_by_tag( 'form', false ),
			self::get_by_tag( 'input', false ),
			self::get_by_tag( 'select', false ),
			self::get_by_tag( 'option', false ),
			self::get_by_tag( 'button', false ),
		);
	}

	public static function get_by_picture() : array {
		return array_merge(
			self::get_by_tag( 'picture', false ),
			self::get_by_tag( 'img', false ),
			self::get_by_tag( 'source', false ),
		);
	}

	public static function get_by_svg() : array {
		static $allowed_svg;

		if ( null === $allowed_svg ) {
			foreach ( static::$allowed_svg_tags as $allowed_tag => $allowed_attributes ) {
				$allowed_svg[ $allowed_tag ] = array();
				foreach ( $allowed_attributes as $attribute_sets_or_key ) {
					if ( is_array( $attribute_sets_or_key ) ) {
						foreach ( $attribute_sets_or_key as $set_key => $set_keys ) {
							if ( is_array( $set_keys ) ) {
								foreach ( $set_keys as $set_key_2 ) {
									foreach ( static::$allowed_svg_attr[ $set_key ][ $set_key_2 ] as $attribute_svg_key ) {
										$allowed_svg[ $allowed_tag ][ $attribute_svg_key ] = true;
									}
								}
							} else {
								foreach ( static::$allowed_svg_attr[ $set_keys ] as $attribute_svg_key ) {
									$allowed_svg[ $allowed_tag ][ $attribute_svg_key ] = true;
								}
							}
						}
					} else {
						$allowed_svg[ $allowed_tag ][ $attribute_sets_or_key ] = true;
					}
				}
			}
		}

		return $allowed_svg;
	}

	protected static function add_global_attributes( $tag_attributes ) {
		/**
		 * @see _wp_add_global_attributes()
		 * @noinspection DuplicatedCode
		 */
		$global_attributes = array(
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'class'            => true,
			'data-*'           => true,
			'dir'              => true,
			'id'               => true,
			'lang'             => true,
			'style'            => true,
			'title'            => true,
			'role'             => true,
			'xml:lang'         => true,
		);

		if ( true === $tag_attributes ) {
			$tag_attributes = array();
		}

		if ( is_array( $tag_attributes ) ) {
			return array_merge( $tag_attributes, $global_attributes );
		}

		return $tag_attributes;
	}

	public static function get_attrs( array $attrs ) : string {
		$html               = '';
		$boolean_attributes = array(
			'allowfullscreen' => true,
			'async'           => true,
			'autofocus'       => true,
			'autoplay'        => true,
			'checked'         => true,
			'controls'        => true,
			'default'         => true,
			'defer'           => true,
			'disabled'        => true,
			'formnovalidate'  => true,
			'inert'           => true,
			'ismap'           => true,
			'itemscope'       => true,
			'loop'            => true,
			'multiple'        => true,
			'muted'           => true,
			'nomodule'        => true,
			'novalidate'      => true,
			'open'            => true,
			'playsinline'     => true,
			'readonly'        => true,
			'required'        => true,
			'reversed'        => true,
			'selected'        => true,
		);

		foreach ( $attrs as $key => $value ) {
			$key = strtolower( $key );
			if ( is_string( $value ) ) {
				$value = trim( $value );
			} elseif ( is_bool( $value ) ) {
				if ( false === $value ) {
					continue;
				}

				$value = '';
			} else {
				$value = wp_json_encode( $value );
			}

			if ( $value ) {
				$html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			} elseif ( isset( $boolean_attributes[ $key ] ) || str_starts_with( $key, 'data-' ) ) {
				$html .= sprintf( ' %s', esc_attr( $key ) );
			}
		}

		return trim( $html );
	}

	public static function the_attrs( array $attrs ) : void {
		echo static::get_attrs( $attrs );
	}
}
