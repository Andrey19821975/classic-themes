<?php

class WLD_ACF_Social_Links_Field_Base extends acf_field_repeater {
	public function initialize() : void {
		parent::initialize();

		$this->name     = 'social_links';
		$this->category = 'layout';
		$this->label    = __( 'Social Links', 'theme' );
		$this->defaults = array(
			'sub_fields'    => array(
				array(
					'ID'                => 0,
					'key'               => 'icon',
					'label'             => __( 'Icon', 'theme' ),
					'name'              => 'icon',
					'prefix'            => 'acf',
					'type'              => 'image',
					'value'             => null,
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '30',
						'class' => '',
						'id'    => '',
					),
					'return_format'     => 'array',
					'preview_size'      => 'thumbnail',
					'library'           => 'all',
					'min_width'         => '',
					'min_height'        => '',
					'min_size'          => '',
					'max_width'         => '',
					'max_height'        => '',
					'max_size'          => '',
					'mime_types'        => '',
					'_name'             => 'icon',
					'_valid'            => 1,
				),
				array(
					'ID'                => 0,
					'key'               => 'url',
					'label'             => __( 'URL', 'theme' ),
					'name'              => 'url',
					'prefix'            => 'acf',
					'type'              => 'text',
					'value'             => null,
					'instructions'      => '',
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
					'_name'             => 'url',
					'_valid'            => 1,
				),
				array(
					'ID'                => 0,
					'key'               => 'text',
					'label'             => __( 'Text', 'theme' ),
					'name'              => 'text',
					'prefix'            => 'acf',
					'type'              => 'text',
					'value'             => null,
					'instructions'      => '',
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
					'_name'             => 'text',
					'_valid'            => 1,
				),
			),
			'min'           => 0,
			'max'           => 0,
			'rows_per_page' => 20,
			'layout'        => 'table',
			'button_label'  => '',
			'collapsed'     => '',
		);
	}
}
