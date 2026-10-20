<?php

class WLD_BAM_Content_Base {
	protected static string $block_name = '';

	protected static array $block_names = array();

	public static function init() : void {
		add_action(
			'the_content',
			array( static::class, 'set_classes' ),
			100
		);
	}

	public static function the_content( string $block_name ) : void {
		WLD_BAM_Content::set_block_name( $block_name );
		the_content();
		WLD_BAM_Content::remove_block_name();
	}

	public static function set_block_name( string $block_name ) : void {
		if ( static::$block_name ) {
			static::$block_names[] = static::$block_name;
		}
		static::$block_name = $block_name;
	}

	public static function remove_block_name() : void {
		static::$block_name = (string) array_pop( static::$block_names );
	}

	public static function set_classes( string $content ) : string {
		if ( static::$block_name ) {
			$tags = new WP_HTML_Tag_Processor( $content );
			while ( $tags->next_tag() ) {
				$tag_name = strtolower( $tags->get_tag() );
				if ( 'btn' !== $tags->get_attribute( 'class' ) ) {
					$tags->add_class( static::$block_name . '__' . $tag_name );
				}
			}

			return $tags->get_updated_html();
		}

		return $content;
	}
}
