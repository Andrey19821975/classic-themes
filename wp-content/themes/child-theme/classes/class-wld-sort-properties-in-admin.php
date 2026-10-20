<?php
/**
 * @noinspection SqlResolve, SqlNoDataSourceInspection
 * phpcs:disable WordPress.DB.PreparedSQLPlaceholders.UnsupportedIdentifierPlaceholder
 */

final class WLD_Sort_Properties_In_Admin {
	private const POST_TYPE      = 'property';
	private const TAXONOMY_NAMES = array( 'state', 'type', 'status' );

	public static function init() : void {
		if ( ! is_admin() ) {
			return;
		}

		add_action(
			'admin_init',
			array( self::class, 'create_database' )
		);
		add_action(
			'save_post_' . self::POST_TYPE,
			array( self::class, 'update_database' )
		);
		add_filter(
			'manage_edit-' . self::POST_TYPE . '_sortable_columns',
			array( self::class, 'set_sortable_columns' )
		);
		add_filter(
			'posts_clauses',
			array( self::class, 'change_query' ),
			10,
			2
		);
		foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
			add_action(
				'saved_' . self::POST_TYPE . '_' . $taxonomy_name,
				array( self::class, 'update_database_all' )
			);
		}
	}

	public static function create_database() : void {
		if ( WLD_Is::get_request( 'wld_action', 'create_table' ) && current_user_can( 'manage_options' ) ) {
			global $wpdb;

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$table_name = self::get_table_name();
			$columns    = '';
			foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
				$columns .= $taxonomy_name . ' VARCHAR(200) NOT NULL DEFAULT "",';
			}

			$sql = "
				CREATE TABLE $table_name (
					id      BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					post_id BIGINT(20) NOT NULL UNIQUE,
					$columns
					PRIMARY KEY (id)
				)
				DEFAULT CHARACTER SET $wpdb->charset COLLATE $wpdb->collate;
			";

			dbDelta( $sql );

			self::update_database_all();
		}
	}

	public static function update_database( $post_id ) : void {
		global $wpdb;

		if ( self::POST_TYPE === get_post_type( $post_id ) ) {
			$has_row = ! ! $wpdb->get_var(
				$wpdb->prepare(
					'SELECT post_id FROM %i WHERE post_id = %d',
					self::get_table_name(),
					$post_id
				)
			);

			$table_name    = self::get_table_name();
			$where         = array( 'post_id' => $post_id );
			$data          = $where;
			$format_format = array( '%d' );
			$format        = $format_format;
			foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
				$data[ $taxonomy_name ] = get_the_terms( $post_id, self::POST_TYPE . '_' . $taxonomy_name )[0]?->name ?? '';
				$format[]               = '%s';
			}

			if ( $has_row ) {
				$wpdb->update( $table_name, $data, $where, $format, $format_format );
			} else {
				$wpdb->insert( $table_name, $data, $format );
			}
		}
	}

	public static function set_sortable_columns( array $sortable_columns ) : array {
		foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
			$taxonomy_key                      = self::get_taxonomy_prefix( $taxonomy_name );
			$sortable_columns[ $taxonomy_key ] = array( $taxonomy_key, false );
		}

		return $sortable_columns;
	}

	public static function change_query( array $clauses, WP_Query $query ) : array {
		global $wpdb;

		if ( ! is_admin() || ! $query->is_main_query() || get_current_screen()?->id !== 'edit-' . self::POST_TYPE ) {
			return $clauses;
		}

		$taxonomy_match = '';
		foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
			if ( WLD_Is::get_request( 'orderby', self::get_taxonomy_prefix( $taxonomy_name ) ) ) {
				$taxonomy_match = str_replace(
					self::get_taxonomy_prefix(),
					'',
					$query->get( 'orderby' )
				);
				break;
			}
		}

		if ( $taxonomy_match ) {
			$table_name = self::get_table_name();

			$clauses['join'] .= $wpdb->prepare(
				' LEFT JOIN %i ON %i.ID=%i.post_id ',
				$table_name,
				$wpdb->posts,
				$table_name
			);

			if ( 'ASC' === strtoupper( $query->get( 'order' ) ) ) {
				$clauses['orderby'] = $wpdb->prepare( ' %i.%i ASC ', $table_name, $taxonomy_name );
			} else {
				$clauses['orderby'] = $wpdb->prepare( ' %i.%i DESC ', $table_name, $taxonomy_name );
			}
		}

		return $clauses;
	}

	public static function update_database_all() : void {
		global $wpdb;

		$table_name = self::get_table_name();

		$wpdb->query( $wpdb->prepare( 'TRUNCATE TABLE %i', $table_name ) );

		$posts = get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'post_type'      => self::POST_TYPE,
			)
		);

		foreach ( $posts as $post_id ) {
			$data   = array( 'post_id' => $post_id );
			$format = array( '%d' );
			foreach ( self::TAXONOMY_NAMES as $taxonomy_name ) {
				$data[ $taxonomy_name ] = get_the_terms( $post_id, self::POST_TYPE . '_' . $taxonomy_name )[0]?->name ?? '';
				$format[]               = '%s';
			}

			$wpdb->insert( $table_name, $data, $format );
		}
	}

	private static function get_table_name() : string {
		global $wpdb;

		return $wpdb->prefix . self::POST_TYPE . '_lookup';
	}

	private static function get_taxonomy_prefix( string $taxonomy_name = '' ) : string {
		return 'taxonomy-' . self::POST_TYPE . '_' . $taxonomy_name;
	}
}
