<?php

class WLD_Styles_Shortcode_Base {
	public static function init() : void {
		add_filter(
			'acf_the_content',
			array( static::class, 'replace' ),
			1
		);
		add_filter(
			'the_content',
			array( static::class, 'replace' ),
			1
		);
	}

	public static function replace( string $content ) : string {
		if ( ! str_contains( $content, '[styles ' ) ) {
			return $content;
		}

		// Replace wrapper styles
		$content = preg_replace(
			'~(\[styles\s+(.+?)])[\r\n]+(.+?)[\r\n]+(\[/styles])~s',
			'<p class="$2">$3</p>',
			$content
		);

		// Replace inline styles
		$pattern = '~(\[styles\s+(.+?)])((?:(?!\[/?styles).)*)(\[/styles])~s';
		while ( preg_match( $pattern, $content ) ) {
			$content = preg_replace(
				$pattern,
				'<span class="$2">$3</span>',
				$content
			);
		}

		return $content;
	}
}
