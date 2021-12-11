<?php
/**
 * The template for displaying search form
 *
 * @package xqcow
 */
?>
<form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<span class="screen-reader-text"><?php _ex( 'Encontrar produtos', 'label', 'xqcow' ); ?></span>
    <input type="text" class="form-control search-query" placeholder="<?php _e( 'Encontrar produtos', 'xqcow' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
    <button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="Search"><i class="fa fa-search" aria-hidden="true"></i></button>
    <?php if ( class_exists( 'WooCommerce' ) ): ?>
        <input type="hidden" value="product" name="post_type" id="post_type">
    <?php endif; ?>
</form>