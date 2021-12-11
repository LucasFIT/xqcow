<?php

/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package xqcow
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function xqcow_woocommerce_setup()
{
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'xqcow_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function xqcow_woocommerce_scripts()
{
	wp_enqueue_style('xqcow-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), _S_VERSION);

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style('xqcow-woocommerce-style', $inline_font);
}
add_action('wp_enqueue_scripts', 'xqcow_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function xqcow_woocommerce_active_body_class($classes)
{
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter('body_class', 'xqcow_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function xqcow_woocommerce_related_products_args($args)
{
	$defaults = array(
		'posts_per_page' => 4,
		'columns'        => 4,
	);

	$args = wp_parse_args($defaults, $args);

	return $args;
}
add_filter('woocommerce_output_related_products_args', 'xqcow_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('xqcow_woocommerce_wrapper_before')) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function xqcow_woocommerce_wrapper_before()
	{
?>
		<main id="primary" class="site-main container pt-5">
			<div class="row">
				<?php if (is_shop()) { ?>
					<div style="width: 100%;">
						<?php woocommerce_breadcrumb(); ?>
					</div>
					<div class="d-md-flex justify-content-between xqcow-main-category-filter w-100">
						<?php
							dynamic_sidebar('sidebar-1');
							do_action("xqcow_woocommerce_catalog_ordering");
						?>
					</div>
				<?php
				} ?>
				<div class="col-12">
				<?php
			}
		}
		add_action('woocommerce_before_main_content', 'xqcow_woocommerce_wrapper_before');

		if (!function_exists('xqcow_woocommerce_wrapper_after')) {
			/**
			 * After Content.
			 *
			 * Closes the wrapping divs.
			 *
			 * @return void
			 */
			function xqcow_woocommerce_wrapper_after()
			{
				?>
				</div>
			</div>
		</main><!-- #main -->
	<?php
			}
		}
		add_action('woocommerce_after_main_content', 'xqcow_woocommerce_wrapper_after');

		/**
		 * Remove default breadcrumb
		 */
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
		/**
		 * Remove the page title
		 */
		add_filter('woocommerce_show_page_title', '__return_false');
		/**
		 * Remove the product count results
		 */
		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
		add_action('xqcow_woocommerce_catalog_ordering', 'woocommerce_catalog_ordering', 30);
		/**
		 * Change single product button text
		 */
		function xqcow_single_add_to_cart_text()
		{
			return '&#xf155; Comprar';
		}
		add_filter('woocommerce_product_single_add_to_cart_text', 'xqcow_single_add_to_cart_text');

		/**
		 * Change add to cart button 
		 */
		function xqcow_add_to_cart_text()
		{
			return __('Comprar', 'xqcow');
		}
		add_filter('woocommerce_product_add_to_cart_text', 'xqcow_add_to_cart_text', 20);

		/**
		 * Change the sale badge text
		 */
		function xqcow_custom_sale_text($text, $post, $_product)
		{
			return '<span class="onsale">Promoção</span>';
		}
		add_filter('woocommerce_sale_flash', 'xqcow_custom_sale_text', 10, 3);

		/**
		 * Remove single product sale badge
		 */
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

		/**
		 * Add breadcrumb before single product image
		 */
		add_action('woocommerce_before_single_product_summary', 'woocommerce_breadcrumb', 10);

		/**
		 * Add sale badge before single product title
		 */
		add_action('woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 0);



		/**
		 * Rewrite price tags
		 */
		add_filter('woocommerce_get_price_html', 'xqcow_price_html', 100, 2);


		function xqcow_price_html($price, $product)
		{
			$price_tag = "";
			
			if ($product->price) {
				$from = $product->regular_price;
				$to = $product->price;

				// Variable products
				if ($product->is_type('variable')) {
					$variations = $product->get_children();
					$reg_prices = array();
					$sale_prices = array();
					foreach ($variations as $value) {
						$single_variation = new WC_Product_Variation($value);
						array_push($reg_prices, $single_variation->get_regular_price());
						array_push($sale_prices, $single_variation->get_price());
					}

					sort($reg_prices);
					sort($sale_prices);

					$min_price = $sale_prices[0];
					$max_price = $reg_prices[0];

					if ($min_price == $max_price) {
						$price_tag .= '<span class="live-colst">' . wc_price($min_price) . '</span>';
						$price_tag .= '<span class="old-colt"></span>';
					} else {
						$price_tag .= '<span class="live-colst">' . wc_price($min_price) . '</span> ';
						$price_tag .= '<span class="old-colt"><del>' . wc_price($max_price) . '</del></span>';
					}
				} else {
					// Normal products
					if ($product->is_on_sale()) {
						$price_tag .= '<span class="live-colst">' . ((is_numeric($to)) ? wc_price($to) : $to) . '</span> ';
						$price_tag .= '<span class="old-colt"><del>' . ((is_numeric($from)) ? wc_price($from) : $from) . '</del></span>';
					} else {
						$price_tag .= '<span class="live-colst">' . ((is_numeric($to)) ? wc_price($to) : $to) . '</span>';
						$price_tag .= '<span class="old-colt"></span>';
					}
				}
			} else {
				$price_tag .= '<span class="live-colst"></span>';
			}
			return $price_tag;
		}

		/**
		 * Add label for product quantity
		 */
		add_action('woocommerce_before_add_to_cart_quantity', 'xqcow_echo_qty_front_add_cart');

		function xqcow_echo_qty_front_add_cart()
		{
			echo '<div class="xqcow-quantity">' . __('Quantidade', 'xqcow') . ': </div>';
		}

		/**
		 * Add a back to shop button after the cart
		 */
		add_action('woocommerce_before_cart_collaterals', 'xqcow_related', 10);
		function xqcow_related()
		{
	?>
	<a class="xqcow-back-to-shop" href="<?php echo wc_get_page_permalink('shop'); ?>">
		<i class="fa fa-arrow-left" aria-hidden="true"></i> <?php echo __('Voltar para a loja', 'xqcow'); ?>
	</a>
<?php
		}

		/**
		 * Update cart total with Ajax
		 */
		add_filter('woocommerce_add_to_cart_fragments', 'xqcow_header_add_to_cart_fragment');

		function xqcow_header_add_to_cart_fragment($fragments)
		{
			global $woocommerce;

			ob_start();

?>
	<span class="xqcow-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	<?php
			$fragments['span.xqcow-cart-count'] = ob_get_clean();
			return $fragments;
		}
		/**
		 * Sample implementation of the WooCommerce Mini Cart.
		 *
		 * You can add the WooCommerce Mini Cart to header.php like so ...
		 *
	<?php
		if ( function_exists( 'xqcow_woocommerce_header_cart' ) ) {
			xqcow_woocommerce_header_cart();
		}
	?>
		 */

		if (!function_exists('xqcow_woocommerce_cart_link_fragment')) {
			/**
			 * Cart Fragments.
			 *
			 * Ensure cart contents update when products are added to the cart via AJAX.
			 *
			 * @param array $fragments Fragments to refresh via AJAX.
			 * @return array Fragments to refresh via AJAX.
			 */
			function xqcow_woocommerce_cart_link_fragment($fragments)
			{
				ob_start();
				xqcow_woocommerce_cart_link();
				$fragments['a.cart-contents'] = ob_get_clean();

				return $fragments;
			}
		}
		add_filter('woocommerce_add_to_cart_fragments', 'xqcow_woocommerce_cart_link_fragment');

		if (!function_exists('xqcow_woocommerce_cart_link')) {
			/**
			 * Cart Link.
			 *
			 * Displayed a link to the cart including the number of items present and the cart total.
			 *
			 * @return void
			 */
			function xqcow_woocommerce_cart_link()
			{
	?>
		<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'xqcow'); ?>">
			<?php
				$item_count_text = sprintf(
					/* translators: number of items in the mini cart. */
					_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'xqcow'),
					WC()->cart->get_cart_contents_count()
				);
			?>
			<span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span class="count"><?php echo esc_html($item_count_text); ?></span>
		</a>
	<?php
			}
		}

		if (!function_exists('xqcow_woocommerce_header_cart')) {
			/**
			 * Display Header Cart.
			 *
			 * @return void
			 */
			function xqcow_woocommerce_header_cart()
			{
				if (is_cart()) {
					$class = 'current-menu-item';
				} else {
					$class = '';
				}
	?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr($class); ?>">
				<?php xqcow_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget('WC_Widget_Cart', $instance);
				?>
			</li>
		</ul>
	<?php
			}
		}

		/**
		 * Change arrow pagination
		 */
		function xqcow_woo_pagination($args)
		{

			$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
			$args['next_text'] = '<i class="fa fa-angle-right"></i>';

			return $args;
		}
		add_filter('woocommerce_pagination_args', 	'xqcow_woo_pagination');

		/**
		 * Add a empty star rating for products
		 */
		//add_filter('woocommerce_product_get_rating_html', 'xqcow_product_get_rating_html', 20, 3);
		//function xqcow_product_get_rating_html($html, $rating, $count)
		//{
		//	if (0 < $rating || is_product()) {
		//		global $product;
		//		$rating_cnt = array_sum($product->get_rating_counts());
		//		$count_html = ' <div class="count-rating">' . $rating_cnt . '</div>';
		//
		//		$html       = '<div class="container-rating"><div class="star-rating">';
		//		$html      .= wc_get_star_rating_html($rating, $count);
		//		$html      .= '</div></div>';
		//
		//		return $html;
		//	} else {
		//		return '<span class="star-rating"></span>';
		//	}
		///}
		//
		/**
		 * Add a map before site footer
		 */
		//add_action( 'xqcow_before_footer', 'xqcow_before_footer_map', 10 );
		function xqcow_before_footer_map()
		{
			$address = get_theme_mod('set_copyright_address', __('Endereço da empresa', 'xqcow'));
			if (is_page('contato')) {
				echo do_shortcode('[su_gmap width="1440" height="400" responsive="no" address="' . $address . '" zoom="0" title="" class=""]');
			}
		}

		/**
		 * Show product SKU before title on product single page
		 */
		add_filter('wc_product_sku_enabled', '__return_false');
		add_action('woocommerce_single_product_summary', 'xqcow_product_sku', 6);
		function xqcow_product_sku()
		{
			global $product;
	?>
	<span class="sku_wrapper">
		<?php esc_html_e('SKU:', 'woocommerce'); ?> <span class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span>
	</span>
<?php
		}

		/**
		 * Add image header before the site content
		 */
		function xqcow_image_header()
		{
?>
	<div class="xqcow-image-header">
		<img src="<?php echo get_header_image(); ?>" alt="Header image">
	</div>
<?php
		}
//add_action( 'xqcow_loop_start', 'xqcow_image_header', 0 );