<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package xqcow
 */

get_header();
?>
	<?php do_action( 'xqcow_loop_start' ); ?>
	<main id="primary" class="site-main container">

		<section class="error-404 not-found text-center">
			<h1 class="my-5">Ops! Página não encontrada!!</h1>
			<p class="mb-5">Erro 404</p>
			<p>
				Não encontrou o que procurava? talvez a nossa pesquisa possa ajudar.
				<?php get_search_form(); ?>
			</p>
			<p>
				<h2>Produtos mais vendidos</h2>
				<?php echo do_shortcode( '[best_selling_products limit="4" columns="4"]' ); ?>
				<a href="<?php echo get_permalink( wc_get_page_id( 'shop' )); ?>">Ver produtos</a>
			</p>
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
