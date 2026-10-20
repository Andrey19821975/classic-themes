<?php

class WLD_Gf_Bam_Classes_Base {
	public static function init() : void {
	}

	public static function add( string $form_html, string $prefix = 'form__' ) : string {
		if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
			require_once ABSPATH . WPINC . '/html-api/class-wp-html-attribute-token.php';
			require_once ABSPATH . WPINC . '/html-api/class-wp-html-span.php';
			require_once ABSPATH . WPINC . '/html-api/class-wp-html-text-replacement.php';
			require_once ABSPATH . WPINC . '/html-api/class-wp-html-tag-processor.php';
		}

		$tags = new WP_HTML_Tag_Processor( $form_html );
		while ( $tags->next_tag() ) {
			$tag        = strtolower( $tags->get_tag() );
			$class_name = $tags->get_attribute( 'class' );
			$type       = $tags->get_attribute( 'type' );
			$for        = $tags->get_attribute( 'for' );
			$classes    = array();

			if ( $class_name ) {
				foreach ( explode( ' ', $class_name ) as $class ) {
					$classes[ $class ] = true;
				}
			}

			if ( isset( $classes['gform_wrapper'] ) ) {
				$tags->add_class( rtrim( $prefix, '_' ) );
			} elseif ( isset( $classes['gform_anchor'] ) ) {
				$tags->add_class( $prefix . 'anchor' );
			} elseif ( isset( $classes['gform_validation_errors'] ) ) {
				$tags->add_class( $prefix . 'errors' );
			} elseif ( isset( $classes['gform_submission_error'] ) ) {
				$tags->add_class( $prefix . 'errors-title' );
			} elseif ( isset( $classes['gform-icon--close'] ) ) {
				$tags->add_class( $prefix . 'errors-close-icon' );
			} elseif ( isset( $classes['gform_heading'] ) ) {
				$tags->add_class( $prefix . 'heading' );
			} elseif ( isset( $classes['gform_required_legend'] ) ) {
				$tags->add_class( $prefix . 'legend' );
			} elseif ( isset( $classes['gfield_required'] ) ) {
				if ( isset( $classes['gfield_required_asterisk'] ) ) {
					$tags->add_class( $prefix . 'required' );
				} else {
					$tags->add_class( $prefix . 'asterisk' );
				}
			} elseif ( 'form' === $tag ) {
				$tags->add_class( $prefix . 'wrapper' );
			} elseif ( isset( $classes['gform_body'] ) ) {
				$tags->add_class( $prefix . 'body' );
			} elseif ( isset( $classes['gform_fields'] ) ) {
				$tags->add_class( $prefix . 'fields' );
			} elseif ( isset( $classes['gfield'] ) ) {
				$tags->add_class( $prefix . 'field' );
				if ( preg_match( '/gfield--type-(\S+) /', $class_name, $matches ) ) {
					$tags->add_class( $prefix . 'field_' . $matches[1] );
				}

				if ( isset( $classes['gfield--type-section'] ) ) {
					$tags->add_class( $prefix . 'section' );
				}
			} elseif ( isset( $classes['gsection_title'] ) ) {
				$tags->add_class( $prefix . 'section-title' );
			} elseif ( isset( $classes['gsection_description'] ) ) {
				$tags->add_class( $prefix . 'section-description' );
			} elseif ( isset( $classes['gfield_label'] ) ) {
				$tags->add_class( $prefix . 'label' );
			} elseif ( isset( $classes['ginput_container'] ) ) {
				$tags->add_class( $prefix . 'field-wrapper' );
			}
		}

		$tags = new WP_HTML_Tag_Processor( $tags->get_updated_html() );
		while ( $tags->next_tag( '.hidden_label' ) ) {
			if ( $tags->next_tag( '.gfield_label' ) ) {
				$tags->add_class( 'screen-reader-text' );
			}
		}

		if ( isset( $_GET['clear'] ) ) {
			$deleted_classes = array(
				'gf_browser_chrome',
				'gform_wrapper',
				'gravity-theme',
				'gform-theme--no-framework',
				'horizontal_wrapper',
				'gform_anchor',
				'gform_heading',
				'gform_required_legend',
				'gfield_required',
				'gfield_required_asterisk',
				'gform_body',
				'gform_fields',
				'gfield',
				'gsection',
				'gfield_section',
				'gfield--type-section',
				'gfield--type-text',
				'gfield--type-email',
				'gfield--type-number',
				'gfield_text',
				'gfield_visibility_visible',
				'field_description_below',
				'gfield--has-description',
				'field_sublabel_below',
				'gfield_contains_required',
				'gfield--width-full',
				'gsection_title',
				'gsection_description',
				'gfield_label',
				'gform-field-label',
				'ginput_container',
			);

			foreach ( $deleted_classes as $deleted_class ) {
				$tags = new WP_HTML_Tag_Processor( $tags->get_updated_html() );
				while ( $tags->next_tag() ) {
					$tags->remove_class( $deleted_class );
					$tags->remove_attribute( 'data-form-theme' );
					$tags->remove_attribute( 'data-field-class' );
					$tags->remove_attribute( 'data-js-reload' );
				}
			}
		}

		return str_replace(
			array( 'class=" ', "class=' " ),
			array( 'class="', "class='" ),
			$tags->get_updated_html()
		);
	}
}
