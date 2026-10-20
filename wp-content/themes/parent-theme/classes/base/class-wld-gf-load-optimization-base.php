<?php

class WLD_GF_Load_Optimization_Base {
	protected static array $scripts      = array();
	protected static array $init_scripts = array();

	public static function init() : void {
		if ( ! is_callable( array( 'GFForms', 'parse_shortcode' ) ) || is_admin() ) {
			return;
		}

		add_action(
			'init',
			array( static::class, 'shortcode' ),
			11
		);
		add_action(
			'wp_footer',
			array( static::class, 'print_scripts' ),
			111
		);

		remove_action( 'init', array( 'GFForms', 'init_buffer' ) );
	}

	/** @noinspection JSCheckFunctionSignatures */
	public static function print_scripts() : void {
		$hooks_javascript = is_callable(
			array(
				'GFCommon',
				'get_hooks_javascript_code',
			)
		) ? GFCommon::get_hooks_javascript_code() : '';

		wp_register_script( 'theme-gf-scripts-start', null, array(), 1, true );
		wp_register_script( 'theme-gf-scripts-end', null, array(), 1, true );
		wp_register_script( 'theme-gf-scripts-template', null, array(), 1, true );

		if ( static::$init_scripts ) {
			foreach ( static::$init_scripts as $script ) {
				if ( $hooks_javascript === $script ) {
					wp_add_inline_script( 'theme-gf-scripts-start', trim( $script ) );
					wp_add_inline_script( 'theme-gf-scripts-start', 'gform.domLoaded = true;' );
				} else {
					wp_add_inline_script( 'theme-gf-scripts-end', trim( $script ) );
				}
			}
		}

		if ( static::$scripts ) {
			foreach ( static::$scripts as $handle ) {
				wp_enqueue_script( $handle );
			}
		}

		echo '<template id="theme-gf-scripts-template">' . "\r\n";
		wp_scripts()->do_item( 'theme-gf-scripts-start' );
		print_footer_scripts();
		wp_scripts()->do_item( 'theme-gf-scripts-end' );
		echo '</template>' . "\r\n";

		wp_add_inline_script(
			'theme-gf-scripts-template',
			/** @lang JavaScript */ <<<'JS'
			let gfScriptsStarted = false;
			let gfScriptsInitiated = false;
			let gfScriptsOnClickElement = null;

			const gfScriptsForms    = document.querySelectorAll( '.gform_wrapper' );
			const gfScriptsTemplate = document.querySelector( '#theme-gf-scripts-template' );

			const gfScriptsInlineScripts = gfScriptsTemplate.content.querySelectorAll( 'script:not([src])' );
			gfScriptsInlineScripts.forEach( ( gfScriptsInlineScript ) => {
				gfScriptsInlineScript.src       = URL.createObjectURL( new Blob( [ gfScriptsInlineScript.innerHTML ], { type: 'text/javascript' } ) );
				gfScriptsInlineScript.innerHTML = '';
			} );

			const gfScriptsAllScripts = gfScriptsTemplate.content.querySelectorAll( 'script' );
			gfScriptsAllScripts.forEach( ( gfScriptsAllScript ) => {
				gfScriptsAllScript.async = false;
				gfScriptsAllScript.defer = true;
			} );

			function gfScriptsInit( event ) {
				if ( gfScriptsInitiated ) {
					return;
				}

				if ( event && 'submit' === event.type ) {
					event.preventDefault();
					event.stopImmediatePropagation();
				}

				if ( gfScriptsStarted ) {
					return;
				}

				gfScriptsStarted = true;

				gfScriptsForms.forEach( ( gfScriptsForm ) => {
					gfScriptsForm.removeEventListener( 'pointermove', gfScriptsInit );
					gfScriptsForm.removeEventListener( 'pointerdown', gfScriptsInit );
					gfScriptsForm.removeEventListener( 'focusin', gfScriptsInit );
					gfScriptsForm.removeEventListener( 'submit', gfScriptsInit );

					const gfScriptsOnClickElements = gfScriptsForm.querySelectorAll( '[onclick]' );
					gfScriptsOnClickElements.forEach( ( gfScriptsOnClickElement ) => {
						const onclick = gfScriptsOnClickElement.getAttribute( 'data-onclick' );

						gfScriptsOnClickElement.removeAttribute( 'data-onclick' );
						gfScriptsOnClickElement.setAttribute( 'onclick', onclick );
						gfScriptsOnClickElement.removeEventListener( 'pointerdown', gfScriptsInitButton );
					} );
				} );

				document.addEventListener( 'gform_main_scripts_loaded', () => {
					setTimeout( () => {
						gfScriptsInitiated = true;
						if ( gfScriptsOnClickElement ) {
							gfScriptsOnClickElement.click()
						}
					}, 500 );
				} );

				document.body.append( ...gfScriptsAllScripts );
			}

			function gfScriptsInitButton( event ) {
				if ( ! gfScriptsInitiated ) {
					gfScriptsInit();
					event.preventDefault();
					event.stopImmediatePropagation();
					gfScriptsOnClickElement = event.target;

					return false;
				}
			}

			gfScriptsForms.forEach( ( gfScriptsForm ) => {
				gfScriptsForm.addEventListener( 'pointermove', gfScriptsInit, { once: true } );
				gfScriptsForm.addEventListener( 'pointerdown', gfScriptsInit, { once: true } );
				gfScriptsForm.addEventListener( 'focusin', gfScriptsInit, { once: true } );
				gfScriptsForm.addEventListener( 'submit', gfScriptsInit, { once: true } );

				const gfScriptsOnClickElements = gfScriptsForm.querySelectorAll( '[onclick]' );
				gfScriptsOnClickElements.forEach( ( gfScriptsOnClickElement ) => {
					const onclick = gfScriptsOnClickElement.getAttribute( 'onclick' );

					gfScriptsOnClickElement.removeAttribute( 'onclick' );
					gfScriptsOnClickElement.setAttribute( 'data-onclick', onclick );
					gfScriptsOnClickElement.addEventListener( 'pointerdown', gfScriptsInitButton, { capture: true, once: true } );
				} );
			} );
			JS
		);
		wp_scripts()->do_item( 'theme-gf-scripts-template' );
	}

	public static function shortcode() : void {
		$tags = array( 'gravityforms', 'gravityform' );

		foreach ( $tags as $tag ) {
			remove_shortcode( $tag );
			add_shortcode( $tag, array( static::class, 'output' ) );
		}
	}

	public static function output( array $attributes ) : string {
		add_filter( 'gform_init_scripts_footer', '__return_false' );

		$original_scripts = wp_scripts()->queue;
		$form_html        = GFForms::parse_shortcode( $attributes );
		$dequeue_scripts  = array_diff( wp_scripts()->queue, $original_scripts );

		foreach ( $dequeue_scripts as $handle ) {
			wp_dequeue_script( $handle );
		}

		static::get_scripts_from_html( $form_html );

		static::$scripts = array_merge( static::$scripts, $dequeue_scripts );

		return $form_html;
	}

	protected static function get_scripts_from_html( string &$html ) : void {
		$re = '~<script(?<attributes>[^>]*)>(?<inline>.*?)</script>~ms';

		$matches = array();
		$html    = preg_replace_callback(
			$re,
			static function ( $script_match ) use ( &$matches ) {
				$matches[] = $script_match;

				return '';
			},
			$html
		);

		foreach ( $matches as $match ) {
			$re = '~(?<name>\w+)(=(?:[\'"])?(?<value>[^\'"]+)(?:[\'"])?)?~ms';
			preg_match_all( $re, $match['attributes'], $attributes_matches, PREG_SET_ORDER );

			if ( ( empty( $attributes['type'] ) || 'text/javascript' === $match['type'] ) && trim( $match['inline'] ) ) {
				static::$init_scripts[] = $match['inline'];
			}
		}
	}
}
