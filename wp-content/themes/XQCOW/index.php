<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package xqcow
 */

get_header();
?>
	<?php do_action( 'xqcow_loop_start' ); ?>
	<main id="primary" class="site-main container">
		
		<?php
		if ( have_posts() ) :
			?>
			<div class="row">
				
				<div class="col-12">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'thumb' );

					endwhile;
					?>	
				</div>
								
				<div class="col-12 col-md-4 col-lg-3">
					<?php dynamic_sidebar( 'sidebar-2' ); ?>
					<?php dynamic_sidebar( 'sidebar-3' ); ?>
				</div>
			</div>
			<?php

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		
		the_posts_pagination( array(
			'prev_text'		=> '<i class="fa fa-angle-left"></i>',
			'next_text'		=> '<i class="fa fa-angle-right"></i>',
		) );
		?>

	</main><!-- #main -->

<?php
get_footer();
