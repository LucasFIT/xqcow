<?php

/**
 * xqcow - XQCOW shortcodes for this theme
 *
 * @package xqcow
 */

if (!function_exists('xqcow_contact_list_shortcode')) :
    /**
     * Display a contact list 
     */
    function xqcow_contact_list_shortcode($atts)
    {
        extract(shortcode_atts(array(
            'address'           => 'yes',
            'whatsapp'          => 'yes',
            'phone'             => 'no',
            'email'             => 'yes',
            'instagram'         => 'yes',
            'instagram_label'   => __('Nome da conta', 'xqcow'),
            'facebook'          => 'yes',
            'facebook_label'    => __('Nome da página', 'xqcow')
        ), $atts));

        $list = '';

        $list .= '<ul class="xqcow-contact-list">';

        // Address
        if (get_theme_mod('set_copyright_address') && $address == 'yes') {
            $list .= '<li><i class="fa fa-map-marker" aria-hidden="true"></i>';

            $list .= get_theme_mod('set_copyright_address');

            $list .= '</li>';
        }

        // WhatsApp    
        if (get_theme_mod('set_whatsapp_number') && $whatsapp == 'yes') {
            $list .= '<li><i class="fab fa-whatsapp" aria-hidden="true"></i>';

            $list .= '<a target="_blank" href="https://api.whatsapp.com/send?phone=55';
            $list .= get_theme_mod('set_whatsapp_number', '#') . '">';
            $list .= xqcow_phone_formatter(get_theme_mod('set_whatsapp_number')) . '</a>';

            $list .= '</li>';
        }

        // Phone Number
        if (get_theme_mod('set_copyright_phone') && $phone == 'yes') {
            $list .= '<li><i class="fa fa-phone" aria-hidden="true"></i>';

            $list .= '<a href="tel:55' . get_theme_mod('set_copyright_phone') . '">';
            $list .= xqcow_phone_formatter(get_theme_mod('set_copyright_phone'));
            $list .= '</a>';

            $list .= '</li>';
        }

        // E-mail
        if (get_theme_mod('set_copyright_email') && $email == 'yes') {
            $list .= '<li><i class="fa fa-envelope" aria-hidden="true"></i>';
            $list .= '<a href="mailto:' . get_theme_mod('set_copyright_email') . '">';
            $list .= get_theme_mod('set_copyright_email') . '</a></li>';
        }

        // Instagram
        if (get_theme_mod('set_instagram_url') && $instagram == 'yes') {
            $list .= '<li><i class="fab fa-instagram" aria-hidden="true"></i>';
            $list .= '<a target="_blank" href="' . get_theme_mod('set_instagram_url') . '">';
            $list .= $instagram_label . '</a></li>';
        }

        // Facebook
        if (get_theme_mod('set_facebook_url') && $facebook == 'yes') {
            $list .= '<li><i class="fab fa-facebook-square" aria-hidden="true"></i>';
            $list .= '<a target="_blank" href="' . get_theme_mod('set_facebook_url') . '">';
            $list .= $facebook_label . '</a></li>';
        }

        $list .= '</ul>';

        return $list;
    }
    add_shortcode('xqcow_contact_list', 'xqcow_contact_list_shortcode');
endif;

if (!function_exists('xqcow_store_info_shortcode')) :
    /**
     * Store info
     */
    function xqcow_store_info_shortcode()
    {
        $section = '';

        $section .= '<section class="row home-store-info justify-content-between">';
        $section .=     '<div class="col-12 col-md-4">';
        $section .=         '<div class="info-item">';
        $section .=             '<i class="fa fa-truck fa-3x" aria-hidden="true"></i>';
        $section .=             '<span>Enviamos até você, no conforto da sua casa</span>';
        $section .=         '</div>';
        $section .=     '</div>';
        $section .=     '<div class="col-12 col-md-4">';
        $section .=         '<div class="info-item">';
        $section .=             '<i class="fa fa-percent fa-3x" aria-hidden="true"></i>';
        $section .=             '<span>Aproveite os nossos preços promocionais</span>';
        $section .=         '</div>';
        $section .=     '</div>';
        $section .=     '<div class="col-12 col-md-4">';
        $section .=         '<div class="info-item">';
        $section .=             '<i class="fa fa-credit-card-alt fa-3x" aria-hidden="true"></i>';
        $section .=             '<span>Disponibilidade de parcelamento</span>';
        $section .=         '</div>';
        $section .=     '</div>';
        $section .= '</section>';

        return $section;
    }
    add_shortcode('xqcow_store_info', 'xqcow_store_info_shortcode');
endif;

if (!function_exists('xqcow_home_section_shortcode')) :
    /**
     * Home product section
     */
    function xqcow_home_section_shortcode($atts)
    {
        $section = '';

        extract(shortcode_atts(array(
            'title'             => __('Título', 'xqcow'),
            'inner_shortcode'   => __('Informe um shortcode', 'xqcow'),
            'columns'           => 4,
            'link'              => get_permalink(wc_get_page_id('shop')),
            'link_label'        => __('Ver produtos', 'xqcow')
        ), $atts));

        $section .= '<section class="xqcow-home-section">';
        $section .= xqcow_products_section_heading($title, $link, $link_label);
        $section .= do_shortcode('[' . $inner_shortcode . ' limit="' . $columns . '" columns="' . $columns . '"]');
        $section .= '</section>';

        return $section;
    }
    add_shortcode('xqcow_home_section', 'xqcow_home_section_shortcode');
endif;

if (!function_exists('xqcow_featured_categories_shortcode')) :
    /**
     * Display featured categories with images
     */
    function xqcow_featured_categories_shortcode()
    {
        $featured_categories = array();

        for ($i = 0; $i < 4; $i++) {
            $category_id = get_theme_mod('set_featured_category' . $i . '_term');

            $featured_categories[$i] = array(
                'category_id'    => $category_id,
                'category_name'    => get_term_by('id', $category_id, 'product_cat')->name,
                'category_link'    => get_category_link($category_id)
            );
        }

        $section = '<section class="row mt-3 xqcow-overflow">';

        $section .= '<div class="col-12 col-md-6 col-lg-3 mt-3">';
        $section .= '<a href="' . $featured_categories[0]['category_link'] . '">';
        $section .= '<div class="xqcow-featured-cat-half" style="background-color: ' . get_theme_mod('set_featured_background_0') . ';">';
        $section .= '<img src="' . z_taxonomy_image_url($featured_categories[0]['category_id']);
        $section .=  '" alt="' . $featured_categories[0]['category_name'] . '">';
        $section .= '<div class="xqcow-featured-overlay">';
        $section .= '<h2>' . $featured_categories[0]['category_name'] . '</h2>';
        $section .= '</div>';
        $section .= '</div>';
        $section .= '</a>';
        $section .= '</div>';

        $section .= '<div class="col-12 col-md-6 col-lg-3 mt-3">';

        $section .= '<a href="' . $featured_categories[1]['category_link'] . '">';
        $section .= '<div class="xqcow-featured-cat-half" style="background-color: ' . get_theme_mod('set_featured_background_1') . ';">';
        $section .= '<img src="' . z_taxonomy_image_url($featured_categories[1]['category_id']);
        $section .=  '" alt="' . $featured_categories[1]['category_name'] . '">';
        $section .= '<div class="xqcow-featured-overlay">';
        $section .= '<h2>' . $featured_categories[1]['category_name'] . '</h2>';
        $section .= '</div>';
        $section .= '</div>';
        $section .= '</a>';

        $section .= '</div>';

        $section .= '<div class="col-12 col-md-6 col-lg-3 mt-3">';
        $section .= '<a href="' . $featured_categories[2]['category_link'] . '">';
        $section .= '<div class="xqcow-featured-cat-half" style="background-color: ' . get_theme_mod('set_featured_background_2') . ';">';
        $section .= '<img src="' . z_taxonomy_image_url($featured_categories[2]['category_id']);
        $section .=  '" alt="' . $featured_categories[2]['category_name'] . '">';
        $section .= '<div class="xqcow-featured-overlay">';
        $section .= '<h2>' . $featured_categories[2]['category_name'] . '</h2>';
        $section .= '</div>';
        $section .= '</div>';
        $section .= '</a>';

        $section .= '</div>';

        $section .= '<div class="col-12 col-md-6 col-lg-3 mt-3">';
        $section .= '<a href="' . $featured_categories[3]['category_link'] . '">';
        $section .= '<div class="xqcow-featured-cat-half" style="background-color: ' . get_theme_mod('set_featured_background_2') . ';">';
        $section .= '<img src="' . z_taxonomy_image_url($featured_categories[3]['category_id']);
        $section .=  '" alt="' . $featured_categories[3]['category_name'] . '">';
        $section .= '<div class="xqcow-featured-overlay">';
        $section .= '<h2>' . $featured_categories[3]['category_name'] . '</h2>';
        $section .= '</div>';
        $section .= '</div>';
        $section .= '</a>';

        $section .= '</div>';

        $section .= '</section>';

        return $section;
    }
    add_shortcode('xqcow_featured_categories', 'xqcow_featured_categories_shortcode');
endif;

if (!function_exists('z_taxonomy_image_url')) :
    /**
     * Placeholder function for Category Images
     */
    function z_taxonomy_image_url()
    {
        return '';
    }
endif;

if (!function_exists('xqcow_products_with_text_shortcode')) :
    /**
     * Display a shortcode with title and subtitle
     */
    function xqcow_products_with_text_shortcode($attributtes)
    {
        $titles = shortcode_atts(array(
            'title'         => __('Título', 'xqcow'),
            'title_color'   => '#000000',
            'subtitle'         => __('Subtítulo', 'xqcow'),
            'subtitle_color'   => '#000000',
        ), $attributtes);

        $shortcode = "[" . $attributtes["internal_shortcode"];

        if ($attributtes["limit"])
            $shortcode .= " limit=" . $attributtes["limit"];

        if ($attributtes["columns"])
            $shortcode .= " columns=" . $attributtes["columns"];

        if ($attributtes["orderby"])
            $shortcode .= " orderby=" . $attributtes["orderby"];

        $shortcode .= "]";

        $result = '';

        $result .= '<div class="xqcow-composed-shortcode">';
        $result .=   '<div class="card-default">';
        $result .=     '<h2 class="styled-title" style="color : ' . $titles['title_color'] . '">' . $titles['title'] . '</h2>';
        $result .=     '<h3 class="styled-text" style="color : ' . $titles['subtitle_color'] . '">' . $titles['subtitle'] . '</h3>';
        $result .=     '<a class="shortcode-btn" href="' . get_permalink(wc_get_page_id('shop')) . '">Ver Produtos <i class="fa fa-arrow-right"></i></a>';
        $result .=   '</div>';
        $result .=   '<div>' . do_shortcode($shortcode) . '</div>';
        $result .= '</div>';

        return $result;
    }
    add_shortcode('xqcow_products_with_text', 'xqcow_products_with_text_shortcode');
endif;


function banner_principal_shortcode()
{
    ob_start();

    $args = array(
        'post_type'      => 'banner_principal',
        'posts_per_page' => 1
    );
?>
    <div class="d-flex container mb-4 ">
        <div class="row flex-nowrap flex-column flex-md-row mx-auto my-0">
            <?php
            $mypod = new WP_Query($args);
            while ($mypod->have_posts()) {
                $mypod->the_post();
                global $post;
            ?>
                <div class="d-flex flex-column flex-md-row align-items-center my-5">
                    <div class="d-flex flex-column col-md-4 xqcow-shortcode-main-texts">
                        <span class="xqcow-shortcode-main-title"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                        <span class="xqcow-shortcode-main-subtitle"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                        <span class="xqcow-shortcode-main-ctnt"> <?= get_post_meta($post->ID, 'conteudo', true);  ?> </span>
                    </div>
                    <div class="d-flex flex-column col-md-8 xqcow-shortcode-main-img">
                        <img src="<?php the_post_thumbnail_url() ?>" class="">
                    </div>
                </div>
            <?php
            }

            ?>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('banner_principal', 'banner_principal_shortcode');


function banner_triplo_shortcode()
{
    ob_start();

    $args = array(
        'post_type'      => 'banner_triplo',
        'posts_per_page' => 3
    );
?>
    <div class="d-flex container mb-4">
        <div class="row flex-nowrap flex-column flex-md-row col-md-12 mx-0 my-3 justify-content-around">
            <?php
            $mypod = new WP_Query($args);
            $postn = 0;
            while ($mypod->have_posts()) {
                $mypod->the_post();
                global $post;

                if ($postn != 1) {
            ?>
                    <div class="d-flex flex-column xqcow-shortcode-triplo-container">
                        <div class="d-flex flex-column xqcow-shortcode-triplo-img">
                            <img src="<?php the_post_thumbnail_url() ?>" class="xqcow-shortcode-triplo-img">
                        </div>
                        <div class="d-flex flex-column align-items-center my-3">
                            <span class="xqcow-shortcode-triplo-title"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                            <span class="xqcow-shortcode-triplo-subtitle"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="d-flex flex-column xqcow-shortcode-triplo-container-bigger">
                        <div class="d-flex flex-column xqcow-shortcode-triplo-img-bigger">
                            <img src="<?php the_post_thumbnail_url() ?>" class="xqcow-shortcode-triplo-img-bigger">
                        </div>
                        <div class="d-flex flex-column align-items-center my-3">
                            <span class="xqcow-shortcode-triplo-title"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                            <span class="xqcow-shortcode-triplo-subtitle"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                        </div>
                    </div>
            <?php
                }
                $postn++;
            }

            ?>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('banner_triplo', 'banner_triplo_shortcode');


function banner_secundario_shortcode()
{
    ob_start();

    $args = array(
        'post_type'      => 'banner_secundario',
        'posts_per_page' => 2
    );
?>
    <div class="d-flex mb-4 mx-0 xqcow-shortcode-secundario-container ">

        <div class="row flex-nowrap flex-column flex-md-row my-3  px-0 xqcow-teste">
            <?php
            $mypod = new WP_Query($args);
            while ($mypod->have_posts()) {
                $mypod->the_post();
                global $post;
            ?>
                <div class="d-flex flex-column ">
                    <div class="d-flex flex-column xqcow-shortcode-secundario-img">
                        <img src="<?php the_post_thumbnail_url() ?>" class="xqcow-shortcode-secundario-img">

                        <span class="xqcow-shortcode-secundario-title xqcow-shortcode-secundario-texts"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                        <a class="xqcow-shortcode-secundario-btn xqcow-shortcode-secundario-texts" href="<?= get_post_meta($post->ID, 'link_botao', true); ?>"> <?= get_post_meta($post->ID, 'texto_botao', true); ?> </a>
                        </img>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('banner_secundario', 'banner_secundario_shortcode');




function sobre_banner_principal_shortcode()
{
    ob_start();

    $args = array(
        'post_type'      => 'sobre_banner_princip',
        'posts_per_page' => 3
    );
?>

    <?php
    $mypod = new WP_Query($args);
    $postn = 0;
    while ($mypod->have_posts()) {
        $mypod->the_post();
        global $post;
        if ($postn == 0) {
    ?>
            <div class="xqcow-shortcode-sobre-bg">
                <div class="xqcow-shortcode-sobre-bg-container">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="flex-column col-md-8 xqcow-shortcode-sobre-img">
                            <img src="<?php the_post_thumbnail_url() ?>" class="">
                        </div>
                        <div class="d-flex flex-column xqcow-shortcode-sobre-texts">
                            <span class="xqcow-shortcode-sobre-title"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                            <span class="xqcow-shortcode-sobre-subtitle"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                            <span class="xqcow-shortcode-sobre-ctnt"> <?= get_post_meta($post->ID, 'conteudo', true);  ?> </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else  if ($postn == 1) {
        ?>
            <div class="d-flex container mb-4 ">
                <div class="row flex-nowrap flex-column flex-md-row mx-auto my-0">
                    <div class="d-flex flex-column flex-md-row align-items-center xqcow-shortcode-sobre-bg-container1">
                        <div class="d-flex flex-column col-md-4 xqcow-shortcode-sobre-texts1">
                            <span class="xqcow-shortcode-sobre-title1"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                            <span class="xqcow-shortcode-sobre-subtitle1"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                            <span class="xqcow-shortcode-sobre-ctnt1"> <?= get_post_meta($post->ID, 'conteudo', true);  ?> </span>
                        </div>
                        <div class="d-flex flex-column col-md-8 xqcow-shortcode-sobre-img1">
                            <img src="<?php the_post_thumbnail_url() ?>" class="">
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else  if ($postn == 2) {
        ?>

            <div class="xqcow-shortcode-sobre-bg-container2">
                <div class="d-flex flex-column flex-md-row">
                    <div class="flex-column col-md-6 xqcow-shortcode-sobre-img2">
                        <img src="<?php the_post_thumbnail_url() ?>" class="">
                    </div>
                    <div class="d-flex flex-column xqcow-shortcode-sobre-texts2">
                        <span class="xqcow-shortcode-sobre-title"> <?= get_post_meta($post->ID, 'texto_titulo', true); ?> </span>
                        <span class="xqcow-shortcode-sobre-subtitle1"> <?= get_post_meta($post->ID, 'texto_subtitulo', true);  ?> </span>
                        <span class="xqcow-shortcode-sobre-ctnt"> <?= get_post_meta($post->ID, 'conteudo', true);  ?> </span>
                    </div>
                </div>
            </div>

    <?php
        }
        $postn++;
    }
    ?>
<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('sobre_banner_princip', 'sobre_banner_principal_shortcode');


function banner_home_page_shortcode()
{
    ob_start();
    $postn = 0;
    $args = array(
        'post_type'      => 'private_label',
        'posts_per_page' => 1
    );''
?>
   
        <div class="banner-central-xqcow-center flex-wrap"   > 
            <div style="background-color: <?php echo get_theme_mod('sec_banner_central_color'); ?>" class="xqcow-block-text-center" >
                <h2><?php echo get_theme_mod('sec_banner_central_title'); ?></h2>
                <h3><?php echo get_theme_mod('sec_banner_central_subtitle'); ?></h3>
            
                <a href="<?php echo get_theme_mod('sec_banner_central_btn_link'); ?>" class="xqcow-contact-btn"><?php echo get_theme_mod('sec_banner_central_btn'); ?></a>
            </div>
            <div  class="xqcow-block-image-center">
    			<img class="" src="<?php echo get_theme_mod('sec_banner_central_image'); ?>" alt="">
            </div>
        </div>
    
<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('banner_home_page', 'banner_home_page_shortcode');




function xqcow_image_center_shortcode()
{
    ob_start();
    $postn = 0;
    $args = array(
        'post_type'      => 'private_label',
        'posts_per_page' => 1
    );''
?>
     
    		<div class="xqcow-container-center-image " >
    			<div class="col-sm xqcow-images-center">
    				<img class="" src="<?php echo get_theme_mod('set_images_center_image_one'); ?>">
    			</div>
        		<div class="col-sm xqcow-images-center">
    				<img class="" src="<?php echo get_theme_mod('set_images_center_image_two'); ?>">
    			</div>
    		
    		</div>

<?php
    $html = ob_get_clean();
    return $html;
}
add_shortcode('xqcow_image_center', 'xqcow_image_center_shortcode');
?>