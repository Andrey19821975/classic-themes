<?php
/**
 * phpcs:disable WordPress.WP.EnqueuedResources
 *
 * @noinspection ES6ConvertVarToLetConst, JSUnresolvedVariable, EqualityComparisonWithCoercionJS,
 *               JSSuspiciousEqPlus, PhpFormatFunctionParametersMismatchInspection, HtmlUnknownTarget
 */

class WLD_GA_GTM_Base {
	private static string $ga_id   = '';
	private static string $gtag_id = '';
	private static string $gtm_id  = '';

	public static function init() : void {
		if ( 'production' === wp_get_environment_type() ) {
			static::$ga_id   = (string) get_option( 'options_wld_api_ga_id', '' );
			static::$gtag_id = (string) get_option( 'options_wld_api_gtag_id', '' );
			static::$gtm_id  = (string) get_option( 'options_wld_api_gtm_id', '' );

			if ( static::$ga_id || static::$gtag_id || static::$gtm_id ) {
				add_action( 'wp_head', array( static::class, 'head' ), 2 );
			}
			if ( static::$gtm_id ) {
				add_action( 'wp_body_open', array( static::class, 'body' ), 1 );
			}
		}
	}

	public static function head() : void {
		$function = 'themeUserActiveActionLoadScript';
		$param    = 4000;

		if ( static::$ga_id ) {
			// Google Analytics https://developers.google.com/analytics/devguides/collection/analyticsjs
			printf(
				"
				<script id=\"ga-inline-js\">
					window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments);};ga.l=+new Date;
					ga('create','%s','auto');
					ga('send','pageview');
					%s('%s', 'ga-js', '%s');
				</script>",
				esc_js( static::$ga_id ),
				esc_js( $function ),
				'https://www.google-analytics.com/analytics.js',
				esc_js( $param )
			);
		}

		if ( static::$gtag_id ) {
			// Google tag https://developers.google.com/gtagjs/devguide/snippet
			printf(
				"
				<script id=\"gtag-inline-js\">
					%s('%s', 'gtag-js', '%s');
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js',new Date());
					gtag('config','%s');
				</script>",
				esc_js( $function ),
				esc_js( esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . static::$gtag_id ) ),
				esc_js( $param ),
				esc_js( static::$gtag_id )
			);
		}

		if ( static::$gtm_id ) {
			// Google Tag Manager https://developers.google.com/tag-manager/quickstart
			if ( WLD_Is::get_request( 'gtm_debug' ) ) {
				printf(
					"
					<!-- Google Tag Manager -->
					<script id=\"gtm-inline-js\">
						(function(w,d,s,l,i){
							w[l]=w[l]||[];
							w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
							var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
							j.async=true;
							j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
							f.parentNode.insertBefore(j,f);
						})(window,document,'script','dataLayer','%s');
					</script>
					<!-- End Google Tag Manager -->",
					esc_js( static::$gtm_id )
				);
			} else {
				printf(
					"
					<!-- Google Tag Manager -->
					<script id=\"gtm-inline-js\">
						(function(w,l){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});})(window,'dataLayer');
						%s('%s', 'gtm-js', '%s');
					</script>
					<!-- End Google Tag Manager -->",
					esc_js( $function ),
					esc_js( esc_url( 'https://www.googletagmanager.com/gtm.js?id=' . static::$gtm_id ) ),
					esc_js( $param )
				);
			}
		}
	}

	public static function body() : void {
		printf(
			'
			<!-- Google Tag Manager (noscript) -->
			<noscript>
				<iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe>
			</noscript>
			<!-- End Google Tag Manager (noscript) -->
			',
			esc_attr( static::$gtm_id )
		);
	}
}
