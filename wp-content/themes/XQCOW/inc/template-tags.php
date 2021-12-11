<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package xqcow
 */

if (!function_exists('xqcow_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function xqcow_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x('Postado em %s', 'post date', 'xqcow'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('xqcow_posted_by')) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function xqcow_posted_by()
	{
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x('por %s', 'post author', 'xqcow'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('xqcow_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function xqcow_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'xqcow'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Postado em %1$s', 'xqcow') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'xqcow'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'xqcow') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__('Deixe um comentário<span class="screen-reader-text"> em %s</span>', 'xqcow'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Editar <span class="screen-reader-text">%s</span>', 'xqcow'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if (!function_exists('xqcow_post_thumbnail')) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function xqcow_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
			return;
		}

		if (is_singular()) :
?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>

		<?php
		endif; // End is_singular().
	}
endif;

if (!function_exists('wp_body_open')) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
endif;

if (!function_exists('xqcow_cart_icon')) :
	/**
	 * Cart icon
	 * TODO: Add ajax
	 */
	function xqcow_cart_icon()
	{
		?>
		<span class="xqcow-header-icon xqcow-cart">
			<a href="<?php echo esc_url(wc_get_cart_url()); ?>">
				<span class="xqcow-icon">
				    <i class="fas fa-shopping-cart"></i>
					<span class="xqcow-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
				</span>
				<span class="xqcow-label xqcow-cart-label">Meu carrinho</span>
			</a>
		</span>
	<?php
	}
endif;

if (!function_exists('xqcow_account_icon')) :
	/**
	 * Account icon 
	 */
	function xqcow_account_icon()
	{

	?>
		<span class="xqcow-header-icon">
			<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">
				<span class="xqcow-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
				<span class="xqcow-label">
					<?php echo is_user_logged_in() ? wp_get_current_user()->user_firstname : 'Entre ou cadastre-se'; ?>
				</span>
			</a>
		</span>
	<?php
	}
endif;

if (!function_exists('xqcow_shop_icon')) :
	/**
	 * Shop icon
	 */
	function xqcow_shop_icon()
	{
	?>
		<span class="xqcow-header-icon">
			<a href="<?php echo wc_get_page_permalink('shop'); ?>">
				<span class="xqcow-icon"><i class="fas fa-store-alt"></i></span>
				<span class="xqcow-label">Produtos</span>
			</a>
		</span>
	<?php
	}
endif;

if (!function_exists('xqcow_quote_icon')) :
	/**
	 * Shop icon
	 */
	function xqcow_quote_icon()
	{
	?>
		<span class="xqcow-header-icon">
			<a href="<?php echo esc_url(home_url('/orcamento')); ?>">
				<span class="xqcow-icon"><i class="fas fa-newspaper"></i></span>
				<span class="xqcow-label">Minha lista</span>
			</a>
		</span>
		<?php
	}
endif;

if (!function_exists('xqcow_social_icons')) :
	/**
	 * Render the social icons
	 */
	function xqcow_social_icons($location = "")
	{
		$social = array(
			"facebook"	=> "fab fa-facebook-square",
			"instagram"	=> "fab fa-instagram",
			"pinterest"	=> "fab fa-pinterest-p",
			"youtube"	=> "fab fa-youtube",
			"linkedin"	=> "fab fa-linkedin-in"
		);

		echo "<ul class='xqcow-social-list " . $location . "'>";

		if (get_theme_mod('set_whatsapp_number') && get_theme_mod('set_whatsapp_checkbox')) :
		?>
			<li>
				<a class="whatsapp" target="_blank" <?php xqcow_whatsapp_href(); ?>>
					<i class="fab fa-whatsapp"></i>
				</a>
			</li>
			<?php
		endif;

		foreach ($social as $icon => $icon_class) {
			if (get_theme_mod('set_' . $icon . '_checkbox', false)) :
			?>
				<li>
					<a class="<?php echo $icon; ?>" target="_blank" href="<?php echo get_theme_mod('set_' . $icon . '_url', '#'); ?>">
						<i class="<?php echo $icon_class; ?>"></i>
					</a>
				</li>
		<?php
			endif;
		}

		echo "</ul>";
	}
endif;

if (!function_exists('xqcow_whatsapp_href')) :
	/**
	 * The href for WhatsApp web api
	 */
	function xqcow_whatsapp_href()
	{
		$number = get_theme_mod('set_whatsapp_number', '#');
		$name = is_user_logged_in() ? wp_get_current_user()->user_firstname : 'Visitante';
		$api = "https://api.whatsapp.com/send?phone=55$number&text=Tudo bem? meu nome é $name";
		$href = 'href="' . $api . '"';

		echo $href;
	}
endif;

if (!function_exists('xqcow_copyright_section')) :
	/**
	 * Copyright, address and legal information
	 */
	function xqcow_copyright_section()
	{
		?>
		<div class="xqcow-copyright-section py-1">
			<p>
				<?php echo get_theme_mod('set_copyright_email'); ?><br>
				<?php echo __('CNPJ: ', 'xqcow') . xqcow_cnpj_formatter(get_theme_mod('set_copyright_cnpj')); ?><br>
				<?php echo __('Inscrição estadual: ', 'xqcow') . xqcow_registration_formatter(get_theme_mod('set_copyright_registration')); ?><br>
				<?php echo get_theme_mod('set_copyright_address'); ?>
			</p>
		</div>
	<?php
	}
endif;

if (!function_exists('xqcow_top_message')) :
	/**
	 * Top info message
	 */
	function xqcow_top_message()
	{
	?>
		<div class="xqcow-top-header d-none d-lg-block">
			<div class="container d-flex justify-content-between">

				<span class="d-flex align-items-center">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'top-menu',
							'menu_id'        => 'xqcow-top-menu-header',
							'menu_class'	 =>	'xqcow-top-menu-header',
						)
					);
					?>
				</span>
			</div>
		</div>
		<?php
	}
endif;

if (!function_exists('xqcow_products_section_heading')) :
	/**
	 * The production section heading
	 */
	function xqcow_products_section_heading($title = 'Título', $link = '#', $link_label = 'Link')
	{
		$heading = '';
		$heading .= '<div class="xqcow-section-title"><h2>' . $title . '</h2></div>';
		return $heading;
	}
endif;

if (!function_exists('xqcow_footer_logo')) :
	function xqcow_footer_logo()
	{
		if (get_theme_mod('set_footer_logo')) {
		?>
			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
				<img class="xqcow-footer-logo" src="<?php echo get_theme_mod('set_footer_logo'); ?>" alt="<?php bloginfo('name'); ?>">
			</a>
		<?php
		} else {
		?>
			<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
		<?php
		}
	}
endif;

if (!function_exists('xqcow_footer_contact')) :
	function xqcow_footer_contact()
	{
		?>
		<div class="xqcow-block-main" >
			<div class="xqcow-block-image">
				<img class="" src="<?php echo get_theme_mod('sec_banner_footer_image'); ?>">
			</div>
		
				<div class="xqcow-block-text"  style="background-color: <?php echo get_theme_mod('sec_banner_footer_color'); ?>">
					<h2>
						<?php echo get_theme_mod('sec_banner_footer_title'); ?>
					</h2>
					<h3>
						<?php echo get_theme_mod('sec_banner_footer_subtitle'); ?>
					</h3>
					<a class="xqcow-contact-btn" href="<?php echo get_theme_mod('sec_banner_footer_btn_link'); ?>">
						<?php echo get_theme_mod('sec_banner_footer_btn'); ?>
					</a>
				</div>

		</div>
<?php
	}
endif;

if (!function_exists('xqcow_footer_image')) :
	function xqcow_footer_image()
	{
		?>
		<div class="xqcow-container-footer-image" >
			<div class="col-sm xqcow-images-footer">
				<img class="" src="<?php echo get_theme_mod('set_images_bottom_image_one'); ?>">
			</div>
    		<div class="col-sm xqcow-images-footer">
				<img class="" src="<?php echo get_theme_mod('set_images_bottom_image_two'); ?>">
			</div>
	        <div class="col-sm xqcow-images-footer">
				<img class="" src="<?php echo get_theme_mod('set_images_bottom_image_three'); ?>">
			</div>
			<div class="col-sm xqcow-images-footer">
				<img class="" src="<?php echo get_theme_mod('set_images_bottom_image_four'); ?>">
			</div>
		
		</div>
<?php
	}
endif;





