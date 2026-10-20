<?php

/**
 * @noinspection PhpUnused, UnknownInspectionInspection, PhpUndefinedFunctionInspection
 *
 * @link    ./wp-content/woocommerce/templates/archive-product.php
 * @link    ./wp-content/woocommerce/templates/content-product.php
 *
 * Hook: woocommerce_before_shop_loop
 *
 * @see     woocommerce_output_all_notices - 10
 * @see     woocommerce_result_count - 20
 * @see     woocommerce_catalog_ordering - 30
 *
 * Hook: woocommerce_shop_loop.
 *
 * Hook: woocommerce_before_shop_loop_item.
 * @see     woocommerce_template_loop_product_link_open - 10
 *
 * Hook: woocommerce_before_shop_loop_item_title
 *
 * @see     woocommerce_show_product_loop_sale_flash - 10
 * @see     woocommerce_template_loop_product_thumbnail - 10
 *
 *
 * Hook: woocommerce_shop_loop_item_title.
 * @see     woocommerce_template_loop_product_title - 10
 *
 * Hook: woocommerce_after_shop_loop_item_title
 * @see     woocommerce_template_loop_rating - 5
 * @see     woocommerce_template_loop_price - 10
 *
 * Hook: woocommerce_after_shop_loop_item
 * @see     woocommerce_template_loop_product_link_close - 5
 * @see     woocommerce_template_loop_add_to_cart - 10
 *
 * Hook: woocommerce_after_shop_loop.
 * @see     woocommerce_pagination - 10
 *
 * Hook: woocommerce_no_products_found
 * @see     wc_no_products_found - 10
 *
 * @version 4.3.0
 */
class WLD_WC_Loop_Base {
	public static function init() : void {
		add_action(
			'woocommerce_before_shop_loop',
			array( static::class, 'wrapper_shop_before' ),
			-1,
		);
		add_action(
			'woocommerce_after_shop_loop',
			array( static::class, 'wrapper_shop_after' ),
			11,
		);
		add_action(
			'woocommerce_after_shop_loop_item_title',
			array( self::class, 'display_product_rating' ),
			5
		);
		add_filter(
			'loop_shop_per_page',
			array( static::class, 'shop_per_page' )
		);
		add_action(
			'woocommerce_pagination_args',
			array( static::class, 'pagination' ),
		);
		add_filter(
			'wc_price',
			array( static::class, 'custom_price_format' ),
			10,
			4,
		);
		add_filter(
			'woocommerce_product_add_to_cart_text',
			array( static::class, 'change_product_add_to_cart_text_for_out_of_stock' ),
		);
	}

	public static function shop_per_page() : int {
		return 12;
	}

	public static function wrapper_shop_before() : void {
		if ( ! is_shop() ) {
			return;
		}
		?>
		<section class="shop">
			<div class="inner">
				<div class="filters">
					<div class="filters-manage">
						<label for="filters-manage">Filter</label>
						<select name="filters-manage" id="filters-manage">
							<option value="collapse_all">Collapse All</option>
							<option value="expand_all" selected>Expand All</option>
							<option value="expand_marked">Expand Marked</option>
						</select>
						<button class="close-filters-manage">Close</button>
					</div>
					<?php /* If necessary, insert the filter shortcode here when using the plugin. */ ?>
				</div>
				<div class="woocommerce-products">
		<?php
	}

	public static function wrapper_shop_after() : void {
		if ( ! is_shop() ) {
			return;
		}
		echo '</div></div></section>';
	}

	public static function pagination( $data ) {
		$data['prev_text'] = esc_html( 'Previous' );
		$data['next_text'] = esc_html( 'Next' );

		return $data;
	}

	public static function display_product_rating(): void {
		self::display_stars_rating_custom( 'reviews' );
	}

	public static function display_stars_rating_custom( $args ):void {
		if ( empty( $args ) ) {
			return;
		}

		global $product;

		$average = $product->get_average_rating();
		$count   = $product->get_review_count();
		?>
		<div class="main-rating">
			<div class="rating_count">
				<?php
				if ( 'all' === $args ) {
					echo '<span>' . esc_html( $average ) . '</span>';
				}
				?>
				<div class="rating-result">
					<?php
					for ( $i = 1; $i <= 5; $i ++ ) {
						$half_or_zero_class = $average > $i - 1 ? 'active half' : '';
						$class              = $i <= $average ? 'active' : $half_or_zero_class;
						echo '<span class="' . esc_attr( $class ) . '"></span>';
					}
					?>
					<?php
					if ( 'all' === $args || 'reviews' === $args ) {
						$text = 1 === $count ? 'review' : 'reviews';
						echo '<div class="rating-count">' . esc_html( $average ) . '</div>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public static function display_stars_rating_reviews_custom( $rating ):void {
		?>
		<div class="main-rating">
			<div class="rating_count">
				<div class="rating-result">
					<?php
					for ( $i = 1; $i <= 5; $i ++ ) {
						$half_or_zero_class = $rating > $i - 1 ? 'active half' : '';
						$class              = $i <= $rating ? 'active' : $half_or_zero_class;
						echo '<span class="' . esc_attr( $class ) . '"></span>';
					}
					?>
					<?php echo '<div class="rating-count">rating</div>'; ?>
				</div>
			</div>
		</div>
		<?php
	}

	public static function change_pagination_start( string $template ) : void {
		if ( 'loop/pagination.php' === $template ) {
			ob_start();
		}
	}

	/** @noinspection HtmlUnknownTarget, DuplicatedCode */
	public static function change_pagination_end( string $template ) : void {
		if ( 'loop/pagination.php' === $template ) {
			$total       = wc_get_loop_prop( 'total_pages' );
			$current     = wc_get_loop_prop( 'current_page' );
			$first_url   = get_pagenum_link();
			$first_text  = esc_html__( 'First Page', 'theme' );
			$first_link  = sprintf( '<a href="%s" class="first-page">%s</a>', $first_url, $first_text );
			$last_url    = get_pagenum_link( $total );
			$last_text   = esc_html__( 'Last Page', 'theme' );
			$last_link   = sprintf( '<a href="%s" class="last-page">%s</a>', $last_url, $last_text );
			$pattern     = array();
			$replacement = array();

			if ( $current > 1 ) {
				$pattern[]     = '/(<ul[^>]*>)/';
				$replacement[] = "$1<li>$first_link</li>";
			}

			if ( $current < $total ) {
				$pattern[]     = '/(<\/ul>)/';
				$replacement[] = "<li>$last_link</li>$1";
			}

			$pagination = preg_replace( $pattern, $replacement, ob_get_clean() );

			// todo: Escape output.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $pagination;
		}
	}

	public static function custom_price_format( $price, $args ) : string {
		$elements = explode( wc_get_price_decimal_separator(), $args );
		if ( is_admin() ) {
			return $price;
		} else {
			return sprintf( '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">%s</span>%s<sup>%s</sup></span>', get_woocommerce_currency_symbol(), $elements[0] . wc_get_price_decimal_separator(), $elements[1] );
		}
	}

	public static function change_product_add_to_cart_text_for_out_of_stock( $text ):string {
		global $product;
		if ( $product && ! $product->is_in_stock() ) {

			return esc_html__( 'View details', 'theme' );
		}

		return $text;
	}
}
