<?php

class WLD_GF_Custom_Merge_Tags extends WLD_GF_Custom_Merge_Tags_Base {
	public static function add( array $merge_tags ) : array {
		$merge_tags[] = array(
			'label' => esc_html__( 'Domain', 'theme' ),
			'tag'   => '{domain}',
		);
		$merge_tags[] = array(
			'label' => esc_html__( 'Site Name', 'theme' ),
			'tag'   => '{site_name}',
		);
		$merge_tags[] = array(
			'label' => esc_html__( 'No Reply Email', 'theme' ),
			'tag'   => '{no_reply_email}',
		);
		$merge_tags[] = array(
			'label' => esc_html__( 'OM File', 'theme' ),
			'tag'   => '{om_file}',
		);

		return $merge_tags;
	}

	public static function replace( string $text ) : string {
		global $post;
		$domain         = str_replace( array( 'https://', 'www.' ), '', home_url( '', 'https' ) );
		$site_name      = wp_strip_all_tags( get_bloginfo( 'name' ) );
		$no_reply_email = 'noreply@' . $domain;
		$om_file        = get_field( 'file', $post->ID );

		return str_replace(
			array( '{domain}', '{site_name}', '{no_reply_email}', '{om_file}' ),
			array( $domain, $site_name, $no_reply_email, $om_file ),
			$text
		);
	}
}
