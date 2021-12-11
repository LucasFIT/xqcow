<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package xqcow
 */

get_header();
?>
	<?php do_action( 'xqcow_loop_start' ); ?>
	<main id="primary" class="site-main xqcow-single-page">

		<?php
		while ( have_posts() ) :
			the_post();
			
			get_template_part( 'template-parts/content', get_post_type() );

			echo '<div class="container">';
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( '&#xf053;', 'xqcow' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '</span> <span class="nav-title">%title</span> <span class="nav-subtitle">' . esc_html__( '&#xf054;', 'xqcow' ),
				)
			);

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			echo '</div>';

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
