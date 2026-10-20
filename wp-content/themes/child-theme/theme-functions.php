<?php
WLD_Sort_Properties_In_Admin::init();

// Specify styles for .btn as in file styles.css
WLD_TinyMCE::add_editor_styles( '.btn', 'background-color:;color:#fff;' );

// Specify styles for login page
WLD_Login_Style::set( 'btn_bg', '' );
WLD_Login_Style::set( 'btn_color', '' );

// Security
WLD_CSP::init(
	array(
		'connect-src' => array(
			'*.acsbapp.com',
			'*.cookieyes.com',
			'cdn-cookieyes.com',
			'in-automate.brevo.com',
		),
		'img-src'     => array(
			'*.acsbapp.com',
			'cdn-cookieyes.com',
		),
		'script-src'  => array(
			'*.acsbapp.com',
			'cdn-cookieyes.com',
			'sibautomation.com',
		),
		'frame-src'   => array(
			'sibautomation.com',
		),
	)
);

// Add custom post types
WLD_CPT::add(
	'testimonial',
	array(
		'menu_icon' => 'dashicons-testimonial',
		'supports'  => array( 'title', 'editor', 'thumbnail' ),
	)
);

WLD_CPT::add(
	'faq',
	array(
		'menu_icon' => 'dashicons-welcome-write-blog',
		'supports'  => array( 'title', 'editor' ),
	)
);
WLD_CPT::add(
	'property',
	array(
		'menu_icon' => 'dashicons-building',
		'supports'  => array( 'title', 'editor', 'thumbnail' ),
		'public'    => true,
	)
);

// Add custom taxonomies
WLD_Tax::add(
	'faq_category',
	array(
		'object_type' => 'faq',
	)
);

WLD_Tax::add(
	'property_state',
	array(
		'object_type'  => 'property',
		'hierarchical' => true,
	)
);

WLD_Tax::add(
	'property_type',
	array(
		'object_type'  => 'property',
		'hierarchical' => true,
	)
);

WLD_Tax::add(
	'property_status',
	array(
		'object_type'  => 'property',
		'hierarchical' => true,
	)
);


// Add menus
WLD_Nav::add( 'Header Left' );
WLD_Nav::add( 'Header Right' );
WLD_Nav::add( 'Header Tablet Left' );
WLD_Nav::add( 'Header Tablet Right' );
WLD_Nav::add( 'Header Mobile' );
WLD_Nav::add( 'Footer Main' );
WLD_Nav::add( 'Footer Links' );

// Add image sizes
// WLD_Images::add_size( '30x30' );

// Add Shortcodes for Content

add_shortcode(
	'learn-more',
	static function () {
		return sprintf(
			'<div class="read-more read-more_show">
            <button class="read-more__btn" type="button">%s%s</button>
            </div>',
			esc_html__( 'Learn More', 'theme' ),
			wld_get_svg( 'link-arrow.svg' ),
		);
	}
);

add_filter(
	'wld_enqueue_get_theme_object',
	static function ( array $theme ) {
		$types      = array();
		$type_terms = get_terms(
			array(
				'taxonomy'   => 'property_type',
				'hide_empty' => false,
			)
		);
		foreach ( $type_terms as $type_term ) {
			$types[] = array(
				'name'        => $type_term->name,
				'strokeColor' => get_field( 'marker_border_color', $type_term ),
				'fillColor'   => get_field( 'marker_fill_color', $type_term ),
			);

		}
		if ( ! empty( $types ) ) {
			$theme['propertyTypes'] = $types;
		}

		return $theme;
	}
);

function short_state( string $full_state ) : string {
	$states = array(
		'Alabama'        => 'AL',
		'Alaska'         => 'AK',
		'Arizona'        => 'AZ',
		'Arkansas'       => 'AR',
		'California'     => 'CA',
		'Colorado'       => 'CO',
		'Connecticut'    => 'CT',
		'Delaware'       => 'DE',
		'Florida'        => 'FL',
		'Georgia'        => 'GA',
		'Hawaii'         => 'HI',
		'Idaho'          => 'ID',
		'Illinois'       => 'IL',
		'Indiana'        => 'IN',
		'Iowa'           => 'IA',
		'Kansas'         => 'KS',
		'Kentucky'       => 'KY',
		'Louisiana'      => 'LA',
		'Maine'          => 'ME',
		'Maryland'       => 'MD',
		'Massachusetts'  => 'МА',
		'Michigan'       => 'MI',
		'Minnesota'      => 'MN',
		'Mississippi'    => 'MS',
		'Missouri'       => 'МО',
		'Montana'        => 'MT',
		'Nebraska'       => 'NE',
		'Nevada'         => 'NV',
		'New Hampshire'  => 'NH',
		'New Jersey'     => 'NJ',
		'New Mexico'     => 'NM',
		'New York'       => 'NY',
		'North Carolina' => 'NC',
		'North Dakota'   => 'ND',
		'Ohio'           => 'ОН',
		'Oklahoma'       => 'ОК',
		'Oregon'         => 'OR',
		'Pennsylvania'   => 'РА',
		'Rhode Island'   => 'RI',
		'South Carolina' => 'SC',
		'South Dakota'   => 'SD',
		'Tennessee'      => 'TN',
		'Texas'          => 'TX',
		'Utah'           => 'UT',
		'Vermont'        => 'VT',
		'Virginia'       => 'VA',
		'Washington'     => 'WA',
		'West Virginia'  => 'WV',
		'Wisconsin'      => 'WI',
		'Wyoming'        => 'WY',
	);

	return $states[ $full_state ];
}

function wld_get_salesforce_contact_id( string $email ) : string {
	if ( class_exists( 'OCGFSF_API' ) ) {
		$client_id      = get_option( 'ocgfsf_client_id' );
		$client_secret  = get_option( 'ocgfsf_client_secret' );
		$username       = get_option( 'ocgfsf_username' );
		$password       = ocgfsf_crypt( get_option( 'ocgfsf_password' ), 'd', $client_secret );
		$salesforce     = new OCGFSF_API( $client_id, $client_secret, $username, $password );
		$authentication = null;
		$configuration  = 0;
		$oauth          = get_option( 'ocgfsf_oauth' );
		if ( 'token' === $oauth ) {
			$token = get_option( 'ocgfsf_token' );
			$salesforce->getRefreshToken( $token );
			$token = get_option( 'ocgfsf_token' );
			if ( $token ) {
				$authentication = $token;
				$configuration  = 1;
			}
		} else {
			$authentication = $salesforce->authentication();
			if ( ! isset( $authentication->error ) ) {
				$configuration = 1;
			}
		}

		if ( $authentication && $configuration ) {
			$records = $salesforce->getRecords(
				$authentication->instance_url,
				$authentication->token_type,
				$authentication->access_token,
				'Contact',
				array( 'Id' ),
				"Email='$email'"
			);
			if ( ! empty( $records->records ) ) {
				return $records->records[0]->Id;
			}
		}
	}

	return false;
}

add_action(
	'gform_pre_submission',
	static function ( $form ) : void {
		global $post;
		if ( 'check-contact-email' === $form['cssClass'] ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$contact_id = wld_get_salesforce_contact_id( $_POST['input_1'] );
			if ( $contact_id ) {
				$_POST['input_2'] = 'yes';
				$_POST['input_7'] = $contact_id;
				$_POST['input_9'] = '0126g000000GqjjAAC';
			} else {
				$_POST['input_2'] = 'no';
			}

			$_POST['input_3'] = $post->ID;
			$_POST['input_4'] = $post->post_title;
			$_POST['input_5'] = get_permalink( $post->ID );
		} elseif ( 'fill-salesforce-form' === $form['cssClass'] ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_POST['input_9'] = $_GET['property_id'];
		}
	}
);

add_action(
	'gform_after_submission',
	static function ( $entry, $form ) {
		if ( 'fill-salesforce-form' === $form['cssClass'] ) {
			$contact_id    = wld_get_salesforce_contact_id( $entry[4] );
			$property_post = get_post( $entry[9] );
			if ( $contact_id && $property_post ) {
				ocgfsf_integration(
					array(
						'1' => $entry[4],
						'2' => 'yes',
						'3' => $property_post->ID,
						'4' => $property_post->post_title,
						'5' => get_permalink( $property_post->ID ),
						'7' => $contact_id,
						'8' => '',
						'9' => '0126g000000GqjjAAC',
					),
					GFAPI::get_form( 7 )
				);
			}
		}
	},
	30,
	2
);

add_filter(
	'pre_update_option_ocgfsf_modules',
	static function ( $value ) {
		if ( 'a:3:{s:4:"Case";s:4:"Case";s:7:"Contact";s:7:"Contact";s:4:"Lead";s:4:"Lead";}' === $value ) {
			return 'a:4:{s:4:"Case";s:4:"Case";s:7:"Contact";s:7:"Contact";s:4:"Lead";s:4:"Lead";s:20:"McLabs2__Proposal__c";s:20:"McLabs2__Proposal__c";}';
		}

		return $value;
	}
);
