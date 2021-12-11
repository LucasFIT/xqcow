<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xqcow
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'xqcow'); ?></a>
		<header id="masthead" class="site-header xqcow-bg-primary">

			<div class="xqcow-top-menu-header" id="top-menu">
				<div>
				    <!-- Top Menu -->
	
						<?php xqcow_social_icons("top"); ?>

				</div>
				<div>
    				<?php
    				wp_nav_menu(
    					array(
    						'theme_location' => 'top-menu',
    						'menu_id'        => 'top-menu',
    						'menu_class'	 =>	'xqcow-top-menu',
    					)
    				);
    				?>
				</div>
			</div>

			<div class="xqcow-container-header">
				<!-- Menu toggle button -->
				<input type="checkbox" id="xqcow-menu-check">
				<label class="xqcow-menu-toggle" for="xqcow-menu-check">
					<i class="fa fa-bars"></i>
				</label>

				<!-- Logo -->
				<div>
					<?php
					if (has_custom_logo()) {
						the_custom_logo();
					} else {
						if (is_front_page()) {
					?>
							<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
						<?php
						} else {
						?>
							<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
					<?php
						}
					}
					?>
				</div>

				<!-- Search bar -->
				<div class="xqcow-header-part search">
					<?php echo do_shortcode('[wcas-search-form]'); ?>
				</div>
        		
				
				    <div class="xqcow-menu-header-primary xqcow-header-part">
        				<?php
        				wp_nav_menu(
        					array(
        						'theme_location' => 'menu-1',
        						'menu_id'        => 'primary-menu',
        						'menu_class'	 =>	'xqcow-primary-menu',
        					)
        				);
        				?>
        		    </div>
					<!-- Account icon -->
					<div class="xqcow-header-part xqcow-header-part-icon">
						<?php xqcow_account_icon(); ?>
					</div>

					<!-- Cart icon -->
					<div class="xqcow-header-part xqcow-header-part-icon">
						<?php xqcow_cart_icon(); ?>
					</div>
				

			</div>

			<div class="xqcow-secondary-container" id="secondary-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_id'        => 'secondary-menu',
						'menu_class'	 =>	'xqcow-secondary-menu',
					)
				);
				?>
			</div>

			<div class="xqcow-bg-primary" id="xqcow-mobile-menu">
				<a href="javascript:void(0)" id="xqcow-closebtn">&times;</a>
			</div>

		</header><!-- #masthead -->