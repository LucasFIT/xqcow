<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xqcow
 */

?>
	<?php do_action( 'xqcow_before_footer' ); ?>
	<?php xqcow_footer_contact(); ?>		
	<?php if(is_front_page()) xqcow_footer_image(); ?>		
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-4">
					<h3><?php bloginfo( 'name'); ?></h3>
					<?php xqcow_copyright_section(); ?> 
				</div>
				<div class="col-12 col-md-6 col-lg-3 p-sm-0">
					<h3><?php echo __( 'Navegação', 'xqcow' ); ?></h3>
					<?php 
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu-1',
							'menu_id'        => 'footer-map',
							'menu_class'	 => 'xqcow-footer-menu'
						)
					);
					?>
				</div>
				
				<div class="col-12 col-md-6 col-lg-3 mb-3">
					<h3><?php echo __( 'Institucional', 'xqcow' ); ?></h3>
					<?php 
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu-2',
							'menu_id'        => 'footer-about',
							'menu_class'	 => 'xqcow-footer-menu'
						)
					);
					?>
				</div>
				
				<div class="col-12 col-md-6 col-lg-2 p-sm-0 text-center">
					<?php xqcow_footer_logo(); ?>
					<?php xqcow_social_icons(); ?>					
				</div>
			</div>
		</div>	

		<div class="xqcow-footer-copyright">
			<div class="container py-2">
				<div class="row">
					<div class="col-12 col-md-6">
						<p>
							&copy;<?php echo date("Y"); ?> - <?php bloginfo( 'name' ); ?> - <?php echo __( 'Todos os direitos reservados', 'xqcow' ); ?>
						</p>
					</div>
					<div class="col-12 col-md-6 text-lg-right xqcow-footer-copyright">
						<p>
						
						</p>
					</div>
				</div>
			</div>
		</div>

	</footer><!-- #colophon -->
	<!-- <a id="scroll-to-top"></a> -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
