<?php

class WLD_Theme_Base {
	public static bool $disable_file_edit = true;
	public static bool $woo_enabled       = false;
	public static bool $acf_enabled       = false;
	public static bool $gf_enabled        = false;
	public static bool $yoast_enable      = false;

	public static string $parent_path = '';
	public static string $child_path  = '';
	public static string $parent_url  = '';
	public static string $child_url   = '';

	protected static array $cache_post_ids_taking_into_preview = array();
	protected static array $theme_files_list                   = array();
	protected static array $autoload_files_list                = array();

	public static function init() : void {
		static::set_variables();
		static::set_files();

		spl_autoload_register( array( static::class, 'autoloader' ) );

		static::require_file( 'inc/helpers.php' );
		static::init_classes();
		static::require_file( 'theme-functions.php' );
		static::set_hooks();
	}

	protected static function set_variables() : void {
		static::$parent_path  = wp_normalize_path( get_template_directory() ) . '/';
		static::$child_path   = wp_normalize_path( get_stylesheet_directory() ) . '/';
		static::$parent_url   = get_template_directory_uri() . '/';
		static::$child_url    = get_stylesheet_directory_uri() . '/';
		static::$woo_enabled  = function_exists( 'WC' );
		static::$acf_enabled  = function_exists( 'acf' );
		static::$gf_enabled   = class_exists( 'GFForms' );
		static::$yoast_enable = defined( 'WPSEO_FILE' );

		defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', static::$disable_file_edit );
	}

	protected static function set_files() : void {
		static::set_files_to_theme_files_list( '' );
		static::set_files_to_theme_files_list( 'inc' );
		static::set_files_to_theme_files_list( 'inc/beautify-html' );
		static::set_files_to_theme_files_list( 'classes' );
		static::set_files_to_theme_files_list( 'classes/base' );
		static::set_files_to_theme_files_list( 'classes/traits' );

		if ( static::$woo_enabled ) {
			static::set_files_to_theme_files_list( 'woocommerce' );
			static::set_files_to_theme_files_list( 'woocommerce/inc' );
			static::set_files_to_theme_files_list( 'woocommerce/classes' );
			static::set_files_to_theme_files_list( 'woocommerce/classes/base' );
			static::set_files_to_theme_files_list( 'woocommerce/classes/traits' );
		}
	}

	protected static function set_hooks() : void {
		add_action(
			'after_setup_theme',
			array( static::class, 'after_setup_theme' )
		);
		add_filter(
			'pre_get_posts',
			array( static::class, 'removed_sticky_for_blog' )
		);

		static::removed_wptexturize();
	}

	protected static function init_classes() : void {
		do_action( 'wld_before_init' );

		if ( static::$acf_enabled ) {
			/** @psalm-var  array<class-string, bool> $init_acf_classes */
			$init_acf_classes = apply_filters(
				'the_init_acf_classes',
				array(
					'WLD_ACF'                  => true,
					'WLD_ACF_Add_Field_Helper' => true,
					'WLD_ACF_Flex_Content'     => true,
					'WLD_ACF_Google_Maps_API'  => true,
					'WLD_ACF_Relationship_All' => true,
					'WLD_ACF_WYSIWYG_Height'   => true,
					'WLD_ACF_Search'           => true,
					'WLD_Not_A_Page'           => true, // todo: Remove dependence on ACF.
					'WLD_Extend_WPLink'        => true, // todo: Remove dependence on ACF.
				)
			);

			foreach ( $init_acf_classes as $class_name => $is_enabled ) {
				if ( $is_enabled ) {
					/** @noinspection PhpUndefinedMethodInspection */
					$class_name::init();
				}
			}
		}

		/** @psalm-var  array<class-string, bool> $init_classes */
		$init_classes = apply_filters(
			'the_init_classes',
			array(
				'WLD_Log'                    => true,
				'WLD_AccessiBe_Plugin'       => true,
				'WLD_Admin_Bar'              => true,
				'WLD_Admin_Notices'          => true,
				'WLD_Admin_Panel'            => true,
				'WLD_BAM_Content'            => true,
				'WLD_BAM_Menu'               => true,
				'WLD_Clear_Cache_Menu_Order' => true,
				'WLD_CPT'                    => true,
				'WLD_Defer_Scripts'          => true,
				'WLD_Delay_Scripts'          => true,
				'WLD_Dequeue'                => true,
				'WLD_Dequeue_Scripts'        => true,
				'WLD_Enqueue_Scripts'        => true,
				'WLD_Enqueue_Styles'         => true,
				'WLD_Favicon'                => true,
				'WLD_Fonts'                  => true,
				'WLD_Front'                  => true,
				'WLD_GA_GTM'                 => true,
				'WLD_GF'                     => true,
				'WLD_GF_Custom_Merge_Tags'   => true,
				'WLD_GF_Load_Optimization'   => true,
				'WLD_Images'                 => true,
				'WLD_Importer'               => true,
				'WLD_KSES'                   => true,
				'WLD_Local_External_Scripts' => true,
				'WLD_Login_Style'            => true,
				'WLD_Nav'                    => true,
				'WLD_Page_Additional_Data'   => true,
				'WLD_Preload'                => true,
				'WLD_QM'                     => true,
				'WLD_Security_Headers'       => true,
				'WLD_Site_Map'               => true,
				'WLD_Styles_Shortcode'       => true,
				'WLD_SVG'                    => true,
				'WLD_Tax'                    => true,
				'WLD_TinyMCE'                => true,
				'WLD_Widgets'                => true,
				'WLD_Yoast'                  => true,
				'WLD_Yoast_SEO_Score_Fix'    => true,
			)
		);

		foreach ( $init_classes as $class_name => $is_enabled ) {
			if ( $is_enabled ) {
				/** @noinspection PhpUndefinedMethodInspection */
				$class_name::init();
			}
		}

		do_action( 'wld_init' );
	}

	public static function after_setup_theme() : void {
		static::load_theme_textdomain();
		static::add_theme_supports();

		static::require_file( 'inc/scripts-and-styles.php' );

		if ( static::$woo_enabled ) {
			static::require_file( 'woocommerce/wc-functions.php' );
		}
	}

	public static function get_version() : string {
		static $version;
		if ( null === $version ) {
			$version = wp_get_theme( 'parent-theme' )->get( 'Version' );
		}

		return $version;
	}

	public static function autoloader( string $class_or_trait_name ) : void {
		if ( 0 !== strncmp( $class_or_trait_name, 'WLD_', 4 ) ) {
			return;
		}

		if ( isset( static::$autoload_files_list[ $class_or_trait_name ] ) ) {
			/** @noinspection PhpIncludeInspection, RedundantSuppression */
			require static::$autoload_files_list[ $class_or_trait_name ];

			return;
		}

		$file_name  = str_replace( '_', '-', strtolower( $class_or_trait_name ) ) . '.php';
		$is_woo     = 0 === strncmp( $class_or_trait_name, 'WLD_WC_', 7 );
		$is_base    = str_ends_with( $class_or_trait_name, '_Base' ); // Here I decided to focus on the end, in order to check for mane and woo with one condition
		$root       = $is_woo ? 'woocommerce/classes' : 'classes';
		$class_name = $root . ( $is_base ? '/base/' : '/' ) . 'class-' . $file_name;
		$trait_name = $root . '/traits/trait-' . $file_name;

		foreach ( array( $class_name, $trait_name ) as $theme_file ) {
			if ( isset( static::$theme_files_list[ $theme_file ] ) ) {
				static::$autoload_files_list[ $class_or_trait_name ] = static::$theme_files_list[ $theme_file ];
				/** @noinspection PhpIncludeInspection, RedundantSuppression */
				require static::$theme_files_list[ $theme_file ];
				break;
			}
		}
	}

	protected static function require_file( string $file_path_from_theme ) : void {
		if ( isset( static::$theme_files_list[ $file_path_from_theme ] ) ) {
			/** @noinspection PhpIncludeInspection, RedundantSuppression */
			require static::$theme_files_list[ $file_path_from_theme ];
		}
	}

	protected static function set_files_to_theme_files_list( string $directory_path_from_theme ) : void {
		if ( $directory_path_from_theme ) {
			$directory_path_from_theme .= '/';
		}

		$files_list  = array();
		$scan_paths  = array();
		$parent_path = static::$parent_path . $directory_path_from_theme;
		$child_path  = static::$child_path . $directory_path_from_theme;

		if ( is_dir( $parent_path ) ) {
			$scan_paths[] = $parent_path;
		}

		if ( is_dir( $child_path ) ) {
			$scan_paths[] = $child_path;
		}

		foreach ( $scan_paths as $scan_path ) {
			$file_names = scandir( $scan_path );
			foreach ( $file_names as $file_name ) {
				if ( '.' === $file_name || '..' === $file_name || ! str_contains( $file_name, '.' ) ) {
					continue;
				}

				$files_list[ $directory_path_from_theme . $file_name ] = $scan_path . $file_name;
			}
		}

		if ( $files_list ) {
			static::$theme_files_list = array_merge( static::$theme_files_list, $files_list );
		}
	}

	public static function get_post_id_taking_into_preview() : int {
		$post_id = (int) get_the_ID();
		if ( isset( static::$cache_post_ids_taking_into_preview[ $post_id ] ) ) {
			return static::$cache_post_ids_taking_into_preview[ $post_id ];
		}

		// phpcs:disabled WordPress.Security.NonceVerification
		if (
			isset( $_GET['preview'] ) &&
			(
				( isset( $_GET['preview_id'] ) && $post_id === (int) $_GET['preview_id'] ) ||
				( isset( $_GET['page_id'] ) && $post_id === (int) $_GET['page_id'] ) ||
				( isset( $_GET['p'] ) && $post_id === (int) $_GET['p'] )
			)
		) {
			$revisions = wp_get_post_revisions( $post_id, array( 'numberposts' => 1 ) );
			$revision  = array_shift( $revisions );
			if ( $revision && $revision->post_parent === $post_id ) {
				$post_id = (int) $revision->ID;
			}
		}
		// phpcs:enabled WordPress.Security.NonceVerification

		static::$cache_post_ids_taking_into_preview[ $post_id ] = $post_id;

		return $post_id;
	}

	public static function never() : bool {
		return false;
	}

	public static function load_theme_textdomain() : void {
		if ( is_dir( WLD_File::get_path( 'languages' ) ) ) {
			load_theme_textdomain( 'theme', WLD_File::get_path( 'languages' ) );
		}
	}

	public static function add_theme_supports() : void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'woocommerce' );
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);
		add_theme_support(
			'post-thumbnails',
			apply_filters( 'wld_thumbnails_support', array( 'post' ) )
		);
	}

	public static function removed_wptexturize() : void {
		remove_filter( 'the_title', 'wptexturize' );
		remove_filter( 'the_content', 'wptexturize' );
		remove_filter( 'the_excerpt', 'wptexturize' );
	}

	public static function fixed_shortcode_wpautop() : void {
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 12 );
	}

	public static function removed_sticky_for_blog( WP_Query $wp_query ) : void {
		if ( $wp_query->is_home() && $wp_query->is_main_query() ) {
			$wp_query->set( 'post__not_in', get_option( 'sticky_posts' ) );
		}
	}
}
