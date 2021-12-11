<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xqcow
 */

$has_filter = is_shop() || is_product_category() || is_product_tag();

if ( ! is_active_sidebar( 'sidebar-1' ) || ! $has_filter ) {
	return; 
}
?>
<div class="d-md-none">
	<button class="btn xqcow-filter-btn w-100 mb-4" type="button" data-toggle="collapse" data-target="#shop-filter" role="button" aria-expanded="false" aria-controls="shop-filter">
		Filtros
	</button>

	<aside id="shop-filter" class="collapse">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
</div>

<aside id="secondary" class="widget-area d-none d-md-block">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
