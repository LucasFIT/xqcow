<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Banner_Management
 * @subpackage Woo_Banner_Management/admin
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Banner_Management
 * @subpackage Woo_Banner_Management/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class woocommerce_category_banner_management_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets & JavaScript for admin area.
     *
     * @since 2.0.0
     */
    public function enqueue_styles_scripts( $hook )
    {
        global  $typenow ;
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        
        if ( isset( $hook ) && !empty($hook) && "dotstore-plugins_page_banner-setting" === $hook || isset( $typenow ) && !empty($typenow) && 'product' === $typenow || 'page' === $typenow ) {
            //stylesheets.
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/datepicker.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'image-upload-category-css',
                plugin_dir_url( __FILE__ ) . 'css/woo-image-upload.css',
                array( 'wp-jquery-ui-dialog' ),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wbm-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wbm-select2-css',
                plugin_dir_url( __FILE__ ) . 'css/select2' . $suffix . '.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wcbm-css',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->version,
                'all'
            );
            //scripts.
            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-category-banner-management-admin' . $suffix . '.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'wbm-select2',
                plugin_dir_url( __FILE__ ) . 'js/select2' . $suffix . '.js',
                array(),
                $this->version,
                true
            );
            wp_enqueue_script(
                'wbm-admin',
                plugin_dir_url( __FILE__ ) . 'js/wbm-admin' . $suffix . '.js',
                array( 'jquery', 'select2', 'jquery-ui-slider' ),
                $this->version,
                true
            );
            wp_localize_script( 'wbm-admin', 'wbmAdminVars', array(
                'alert'                => __( 'Are you sure you want to delete?', 'woo-banner-management' ),
                'placeholder'          => __( 'Enter banner image link', 'woo-banner-management' ),
                'click'                => __( 'Click here', 'woo-banner-management' ),
                'preview'              => __( 'Preview', 'woo-banner-management' ),
                'can_use_premium_code' => wp_json_encode( wcbm_fs()->can_use_premium_code() ),
            ) );
        }
    
    }
    
    /**
     *  Set custom menu in WooCommerce-banner-management plugin
     */
    public function wcbm_menu_page()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins' ),
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_page' ),
                plugin_dir_url( __FILE__ ) . 'images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            __( 'Banner Management', 'woo-banner-management' ),
            __( 'Banner Management', 'woo-banner-management' ),
            'manage_options',
            'banner-setting',
            array( $this, 'wcbm_options_page' )
        );
    }
    
    /**
     * WooCommerce Product Attachment Option Page HTML
     *
     */
    public function wcbm_options_page()
    {
        include_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-header.php';
        $wcpoa_attachment_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $wcpoa_attachment_tab = ( !empty($wcpoa_attachment_tab) ? $wcpoa_attachment_tab : '' );
        
        if ( !empty($wcpoa_attachment_tab) ) {
            if ( "wcbm-plugin-get-started" === $wcpoa_attachment_tab ) {
                self::wcbm_plugin_get_started();
            }
            if ( "wcbm-plugin-information" === $wcpoa_attachment_tab ) {
                self::wcbm_plugin_quick_info();
            }
        } else {
            self::my_custom_submenu_page_callback();
        }
        
        include_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-sidebar.php';
    }
    
    /*
     * include get started page file.
     */
    public function wcbm_plugin_get_started()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-get-started-page.php';
    }
    
    /**
     * include get information page.
     */
    public function wcbm_plugin_quick_info()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-information-page.php';
    }
    
    /*
     * custom call wbm setting page.
     */
    public function my_custom_submenu_page_callback()
    {
        wp_enqueue_media();
        $wbm_shop_page_stored_results_serialize_banner_select_image = '';
        $wbm_shop_page_stored_results_serialize_banner_target = '';
        $wbm_cart_page_stored_results_serialize_banner_target = '';
        $wbm_shop_page_stored_results_serialize_banner_relation = '';
        $wbm_cart_page_stored_results_serialize_banner_relation = '';
        $wbm_shop_page_stored_results_serialize_banner_src = '';
        $wbm_shop_page_stored_results_serialize_banner_link = '';
        $wbm_shop_page_stored_results_serialize_banner_image_size = '';
        $wbm_shop_page_stored_results_serialize_banner_enable_status = '';
        $wbm_cart_page_stored_results_serialize_banner_select_image = '';
        $wbm_cart_page_stored_results_serialize_banner_src = '';
        $wbm_cart_page_stored_results_serialize_banner_arr = '';
        $wbm_cart_page_stored_results_serialize_banner_link = '';
        $wbm_cart_page_stored_results_serialize_banner_enable_status = '';
        $wbm_checkout_page_stored_results_serialize_banner_select_image = '';
        $wbm_checkout_page_stored_results_serialize_banner_src = '';
        $wbm_checkout_page_stored_results_serialize_banner_arr = '';
        $wbm_checkout_page_stored_results_serialize_banner_link = '';
        $wbm_checkout_page_stored_results_serialize_banner_enable_status = '';
        $wbm_checkout_page_stored_results_serialize_banner_target = '';
        $wbm_checkout_page_stored_results_serialize_banner_relation = '';
        $wbm_thankyou_page_stored_results_serialize_banner_select_image = '';
        $wbm_thankyou_page_stored_results_serialize_banner_src = '';
        $wbm_thankyou_page_stored_results_serialize_banner_arr = '';
        $wbm_thankyou_page_stored_results_serialize_banner_link = '';
        $wbm_thankyou_page_stored_results_serialize_banner_enable_status = '';
        $wbm_thankyou_page_stored_results_serialize_banner_target = '';
        $wbm_thankyou_page_stored_results_serialize_banner_relation = '';
        $wbm_banner_detail_page_stored_results_serialize_banner_select_image = '';
        $wbm_banner_detail_page_stored_results_serialize_banner_src = '';
        $wbm_banner_detail_page_stored_results_serialize_banner_arr = '';
        $wbm_banner_detail_page_stored_results_serialize_banner_link = '';
        $wbm_banner_global_page_stored_results_serialize_banner_target = '';
        $wbm_banner_global_page_stored_results_serialize_banner_relation = '';
        $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
        $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status = '';
        $wbm_other_page_stored_results_serialize_banner_select_image = '';
        $wbm_other_page_stored_results_serialize_banner_target = '';
        $wbm_other_page_stored_results_serialize_banner_relation = '';
        $wbm_other_page_stored_results_serialize_banner_src = '';
        $wbm_other_page_stored_results_serialize_banner_link = '';
        $wbm_other_page_stored_results_serialize_banner_enable_status = '';
        $product_slider_settings_on_sale_val = '';
        $product_slider_settings_featured_prod_val = array();
        $product_slider_price_range_val = '';
        $product_cat_slider_settings_cat_val = array();
        $product_cat_slider_settings_by_title_val = '';
        $product_cat_slider_settings_feature_img_val = '';
        $product_cat_slider_settings_by_desc_val = '';
        
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
            $wbm_cart_page_stored_results = wcbm_get_page_banner_data( 'cart' );
            $wbm_checkout_page_stored_results = wcbm_get_page_banner_data( 'checkout' );
            $wbm_thankyou_page_stored_results = wcbm_get_page_banner_data( 'thankyou' );
            $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
            $wbm_other_page_stored_results = wcbm_get_page_banner_data( 'other_pages' );
            $wbm_prod_slider_data_stored_array = wcbm_get_page_banner_data( 'product_sliders' );
        }
        
        // get shop page stored data
        
        if ( isset( $wbm_shop_page_stored_results ) && !empty($wbm_shop_page_stored_results) ) {
            $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
            
            if ( !empty($wbm_shop_page_stored_results_serialize) ) {
                $wbm_shop_page_stored_results_serialize_banner_select_image = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_image']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_image'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_target = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_target']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_target'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_relation = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_src = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_src'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_link = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_link_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_link_src'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status'] : '' );
            }
        
        }
        
        //get cart setting page stored data
        
        if ( isset( $wbm_cart_page_stored_results ) && !empty($wbm_cart_page_stored_results) ) {
            $wbm_cart_page_stored_results_serialize = $wbm_cart_page_stored_results;
            
            if ( !empty($wbm_cart_page_stored_results_serialize) ) {
                $wbm_cart_page_stored_results_serialize_banner_select_image = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_image']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_image'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_src = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_image_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_image_src'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_link = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_link_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_link_src'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_target = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_target']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_target'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_relation = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation'] : '' );
            }
        
        }
        
        //get checkout setting page stored data
        
        if ( isset( $wbm_checkout_page_stored_results ) && !empty($wbm_checkout_page_stored_results) ) {
            $wbm_checkout_page_stored_results_serialize = $wbm_checkout_page_stored_results;
            
            if ( !empty($wbm_checkout_page_stored_results_serialize) ) {
                $wbm_checkout_page_stored_results_serialize_banner_select_image = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_select_image']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_select_image'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_src = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_link = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_target = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_target']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_target'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_relation = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation'] : '' );
            }
        
        }
        
        //get thank you setting page stored data
        
        if ( isset( $wbm_thankyou_page_stored_results ) && !empty($wbm_thankyou_page_stored_results) ) {
            $wbm_thankyou_page_stored_results_serialize = $wbm_thankyou_page_stored_results;
            
            if ( !empty($wbm_thankyou_page_stored_results_serialize) ) {
                $wbm_thankyou_page_stored_results_serialize_banner_select_image = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_select_image']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_select_image'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_src = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_link = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_target = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_relation = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation'] : '' );
            }
        
        }
        
        // get other page stored data
        
        if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
            $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
            
            if ( !empty($wbm_other_page_stored_results_serialize) ) {
                $wbm_other_page_stored_results_serialize_banner_select_image = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_image']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_image'] : '' );
                $wbm_other_page_stored_results_serialize_banner_target = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_target']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_target'] : '' );
                $wbm_other_page_stored_results_serialize_banner_relation = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_relation']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_relation'] : '' );
                $wbm_other_page_stored_results_serialize_banner_src = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_image_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_image_src'] : '' );
                $wbm_other_page_stored_results_serialize_banner_link = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_link_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_link_src'] : '' );
                $wbm_other_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_enable_status']) ? $wbm_other_page_stored_results_serialize['other_page_banner_enable_status'] : '' );
            }
        
        }
        
        //get global banner setting stored data
        
        if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
            $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
            
            if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                $wbm_banner_detail_page_stored_results_serialize_banner_select_image = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_select_image']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_select_image'] : '' );
                $wbm_banner_detail_page_stored_results_serialize_banner_src = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src'] : '' );
                $wbm_banner_detail_page_stored_results_serialize_banner_link = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src'] : '' );
                $wbm_banner_global_page_stored_results_serialize_banner_target = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target'] : '' );
                $wbm_banner_global_page_stored_results_serialize_banner_relation = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation'] : '' );
                $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
            }
        
        }
        
        // get product sliders settings stored data
        
        if ( isset( $wbm_prod_slider_data_stored_array ) && !empty($wbm_prod_slider_data_stored_array) ) {
            $wbm_prod_slider_data_stored_array_serialize = $wbm_prod_slider_data_stored_array;
            $product_slider_settings_on_sale_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_settings_on_sale']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_settings_on_sale'] : '' );
            $product_slider_settings_featured_prod_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_settings_featured_prod']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_settings_featured_prod'] : array() );
            $product_slider_price_range_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_price_range']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_price_range'] : '' );
            $product_cat_slider_settings_cat_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_cat']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_cat'] : array() );
            $product_cat_slider_settings_by_title_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_title']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_title'] : 'off' );
            $product_cat_slider_settings_feature_img_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_feature_img']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_feature_img'] : 'off' );
            $product_cat_slider_settings_by_desc_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_desc']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_desc'] : 'off' );
        }
        
        ?>
		<div class="wcbm-section-left">
			<div class="notice notice-success is-dismissible" id="succesful_message_wbm">
				<p><?php 
        esc_html_e( 'Banner saved succesfully', 'woo-banner-management' );
        ?></p>
			</div>
			<div class="woocommerce-banner-managment-setting-content">
				<div class="top-save-button">
					<img class="banner-setting-loader"
						src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif' ) ;
        ?>"
						alt="ajax-loader" />
					<input type="button" name="save_wbmshop" id="save_top_wbm_shop_page_setting" class="button button-primary"
						value="<?php 
        esc_attr_e( 'Save Changes', 'woo-banner-management' );
        ?>">
				</div>
				<fieldset class="wbm_global">
					<legend>
						<div class="wbm-setting-header">
							<h2><?php 
        esc_html_e( 'Checkout Page Specific Banner Settings', 'woo-banner-management' );
        ?></h2>
						</div>
					</legend>
					<p><?php 
        esc_html_e( 'You can upload custom banner on page specific. Easily update the image and redirect page URL from below setting option.', 'woo-banner-management' );
        ?>
					</p>
					<div class="accordion">
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not = " ( Enable ) ";
            $setting_enable_or_color = "green";
        } else {
            $setting_enable_or_not = " ( Disable ) ";
            $setting_enable_or_color = "red";
        }
        
        ?>
							<a class="accordion-section-title" href="#wbm-enable-banner-for-shpe-page">
								<?php 
        esc_html_e( 'Banner for shop page ', 'woo-banner-management' );
        ?>
								<span id="shop_page_status_enable_or_disable"
									class="shop_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color ) ;
        ?>">
									<?php 
        esc_html_e( $setting_enable_or_not, 'woo-banner-management' );
        ?>
								</span>
							</a>
							<div id="wbm-enable-banner-for-shpe-page" class="accordion-section-content">

								<table class="form-table" id="form-table-wbm-shop-page">
									<tbody>
										<tr>
											<th scope="row"><label class="wbm_leble_setting_css"
													for="wbm_shop_setting_enable"><?php 
        esc_html_e( 'Enable/Disable', 'woo-banner-management' );
        ?>
												</label>
											</th>
											<td><input type="checkbox" value="on" id="wbm_shop_setting_enable"
													class="wbm_shop_setting_enable_or_not" <?php 
        checked( $wbm_shop_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>>
												</td>
											<?php 
        $shop_page_url_results = "#";
        $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
        if ( !empty($shop_page_url) ) {
            $shop_page_url_results = $shop_page_url;
        }
        
        if ( $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $shop_page_preview_content = '<strong>' . __( 'Preview', 'woo-banner-management' ) . ':</strong> <a href="' . $shop_page_url_results . '" target="_blank">' . __( 'Click here', 'woo-banner-management' ) . '</a>';
        } else {
            $shop_page_preview_content = '';
        }
        
        ?>
											<input type="hidden" id="shop_page_hidden_url"
												value="<?php 
        echo  esc_url( $shop_page_url_results ) ;
        ?>">
											<td>
												<span
													class="Preview_link_for_shop_page"><?php 
        echo  wp_kses_post( $shop_page_preview_content ) ;
        ?></span>
											</td>
										</tr>
									</tbody>
								</table>
								<?php 
        $display_option = 'block';
        if ( 'on' !== $wbm_shop_page_stored_results_serialize_banner_enable_status ) {
            $display_option = 'none';
        }
        ?>
								<div class="wbm_shop_page_enable_open_div <?php 
        echo  esc_attr( $display_option ) ;
        ?>">
									<fieldset class="innerbanner">
										<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
										<?php 
        ?>
											<table class="form-table" id="form-table">
												<tbody>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_shop_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
														</th>
														<td><a class='wbm_shop_page_single_upload_file_button button'
																id="wbm_shop_page_single_upload_file_button"
																uploader_title='Select File'
																uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
															<a
																class='wbm_shop_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
														</td>
													</tr>
													<tr>
														<th scope="row"></th>
														<?php 
        
        if ( '' === $wbm_shop_page_stored_results_serialize_banner_src ) {
            $shop_page_benner_css = "no-image";
        } else {
            $shop_page_benner_css = "yes-image";
        }
        
        ?>
														<td>
															<img class="wbm_shop_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $shop_page_benner_css ) ;
        ?>"
																src="<?php 
        echo  esc_url( $wbm_shop_page_stored_results_serialize_banner_src ) ;
        ?>" />
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="shop_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link', 'woo-banner-management' );
        ?></label>
														</th>
														<td><input type="url" id="shop_page_banner_single_image_link"
																title="Example: https://multidots.com"
																name='term_meta[banner_link]'
																value='<?php 
        echo  esc_attr( $wbm_shop_page_stored_results_serialize_banner_link ) ;
        ?>' />
															<p><label class="banner_link_label"
																	for="shop_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
															</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_shop_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_shop_page_select_banner_target"
																id="wbm_shop_page_select_banner_target" class="">
																<option value="self"
																	<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																	<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																</option>
																<option value="blank"
																	<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																	<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_shop_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_shop_page_select_banner_relation"
																id="wbm_shop_page_select_banner_relation" class="">
																<option value="follow"
																	<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																	<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																</option>
																<option value="nofollow"
																	<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																	<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
											<?php 
        ?>
									</fieldset>

								</div>
                    		</div>
							<!--end .accordion-section-content-->
						</div>
						<!--end .accordion-section-->
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not_cart = " ( Enable ) ";
            $setting_enable_or_color_cart = "green";
        } else {
            $setting_enable_or_not_cart = " ( Disable ) ";
            $setting_enable_or_color_cart = "red";
        }
        
        ?>
							<a class="accordion-section-title"
								href="#wbm-enable-banner-for-cart-page"><?php 
        esc_html_e( 'Banner for cart page', 'woo-banner-management' );
        ?>
								<span id="cart_page_status_enable_or_disable"
									class="cart_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color_cart ) ;
        ?>">
									<?php 
        esc_html_e( $setting_enable_or_not_cart, 'woo-banner-management' );
        ?></span></a>
							<div id="wbm-enable-banner-for-cart-page" class="accordion-section-content">
								<div class="woocommerce-banner-managment-cart-setting-admin">

									<table class="form-table" id="form-table-wbm-cart-page">
										<tbody>
											<tr>
												<th scope="row"><label class="wbm_leble_setting_css"
														for="wbm_shop_setting_cart_enable"><?php 
        esc_html_e( 'Enable/Disable', 'woo-banner-management' );
        ?></label>
												</th>
												<td><input type="checkbox" value="on" id="wbm_shop_setting_cart_enable"
														class="wbm_shop_setting_cart_enable_or_not" <?php 
        checked( $wbm_cart_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>></td>
												<?php 
        $cart_url_results = "#";
        $cart_url = wc_get_cart_url();
        if ( !empty($cart_url) ) {
            $cart_url_results = $cart_url;
        }
        
        if ( $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $cart_page_preview_url = '<strong>' . __( 'Preview', 'woo-banner-management' ) . ':</strong> <a href="' . $cart_url_results . '" target="_blank">' . __( 'Click here', 'woo-banner-management' ) . '</a>';
        } else {
            $cart_page_preview_url = "";
        }
        
        ?>
												<input type="hidden" id="cart_page_hidden_url"
													value="<?php 
        echo  esc_url( $cart_url_results ) ;
        ?>">
												<td>
													<span
														class="Preview_link_for_cart_page"><?php 
        echo  wp_kses_post( $cart_page_preview_url ) ;
        ?></span>
												</td>
											</tr>

										</tbody>
									</table>

									<?php 
        $display_option_cart = 'block';
        if ( 'on' !== $wbm_cart_page_stored_results_serialize_banner_enable_status ) {
            $display_option_cart = 'none';
        }
        ?>
									<div class="wbm-cart-upload-image-html <?php 
        echo  esc_attr( $display_option_cart ) ;
        ?>">
										<fieldset class="innerbanner">
											<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
											<?php 
        ?>
												<table class="form-table" id="form-table">
													<tbody>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_cart_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
															</th>
															<td>
																<a class='wbm_cart_page_single_upload_file_button button'
																	id="wbm_cart_page_single_upload_file_button"
																	uploader_title="<?php 
        esc_attr_e( 'Select File', 'woo-banner-management' );
        ?>"
																	uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
																<a
																	class='wbm_cart_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
															</td>
														</tr>
														<tr>
															<th scope="row"></th>
															<?php 
        
        if ( $wbm_cart_page_stored_results_serialize_banner_src === '' ) {
            $cart_page_benner_css = "no-image";
        } else {
            $cart_page_benner_css = "yes-image";
        }
        
        ?>
															<td>
																<img class="wbm_cart_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $cart_page_benner_css ) ;
        ?>"
																	src="<?php 
        echo  esc_url( $wbm_cart_page_stored_results_serialize_banner_src ) ;
        ?>" />
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="cart_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link', 'woo-banner-management' );
        ?></label>
															</th>
															<td><input type="url" id="cart_page_banner_single_image_link"
																	title="<?php 
        esc_attr_e( 'Example: https://multidots.com', 'woo-banner-management' );
        ?>"
																	name='term_meta[banner_link]'
																	value='<?php 
        echo  esc_attr( $wbm_cart_page_stored_results_serialize_banner_link ) ;
        ?>' />
																<p><label class="banner_link_label"
																		for="cart_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
																</p>
															</td>
														</tr>
														<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_cart_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_cart_page_select_banner_target"
																id="wbm_cart_page_select_banner_target" class="">
																<option value="self"
																	<?php 
        selected( $wbm_cart_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																	<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																</option>
																<option value="blank"
																	<?php 
        selected( $wbm_cart_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																	<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_cart_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_cart_page_select_banner_relation"
																id="wbm_cart_page_select_banner_relation" class="">
																<option value="follow"
																	<?php 
        selected( $wbm_cart_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																	<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																</option>
																<option value="nofollow"
																	<?php 
        selected( $wbm_cart_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																	<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>	
													</tbody>
												</table>
												<?php 
        ?>
										</fieldset>
									</div>
								</div>
							</div>
							<!--end .accordion-section-content-->
						</div>
						<!--end .accordion-section-->
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not_checkout = " ( Enable ) ";
            $setting_enable_or_color_checkout = "green";
        } else {
            $setting_enable_or_not_checkout = " ( Disable ) ";
            $setting_enable_or_color_checkout = "red";
        }
        
        ?>
							<a class="accordion-section-title"
								href="#wbm-enable-banner-for-checkout-page"><?php 
        esc_html_e( 'Banner for checkout page', 'woo-banner-management' );
        ?>
								<span id="checkout_page_status_enable_or_disable"
									class="checkout_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color_checkout ) ;
        ?>">
									<?php 
        esc_html_e( $setting_enable_or_not_checkout, 'woo-banner-management' );
        ?>
								</span>
							</a>
							<div id="wbm-enable-banner-for-checkout-page" class="accordion-section-content">
								<div class="woocommerce-banner-managment-checkout-setting-admin">
									<table class="form-table" id="form-table-wbm-checkout-page">

										<tbody>
											<tr>
												<th scope="row"><label class="wbm_leble_setting_css"
														for="wbm_shop_setting_checkout_enable"><?php 
        esc_html_e( 'Enable/Disable', 'woo-banner-management' );
        ?>
													</label>
												</th>
												<td><input type="checkbox" value="on" id="wbm_shop_setting_checkout_enable"
														class="wbm_shop_setting_checkout_enable_or_not" <?php 
        checked( $wbm_checkout_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>></td>
												<?php 
        $CheckOut_url_real = "#";
        $CheckOut_url = wc_get_checkout_url();
        if ( !empty($CheckOut_url) ) {
            $CheckOut_url_real = $CheckOut_url;
        }
        
        if ( $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $check_out_preview_content = '<strong>' . __( "Preview :", "woo-banner-management" ) . '</strong> <a href="' . $CheckOut_url_real . '" target="_blank">' . __( 'Click here', 'woo-banner-management' ) . '</a>';
        } else {
            $check_out_preview_content = "";
        }
        
        ?>

												<input type="hidden" id="checkout_page_hidden_url"
													value="<?php 
        echo  esc_url( $CheckOut_url_real ) ;
        ?>">
												<td>
													<span
														class="Preview_link_for_checkout_page"><?php 
        echo  wp_kses_post( $check_out_preview_content ) ;
        ?></span>
												</td>
											</tr>
										</tbody>
									</table>
									<?php 
        $display_option_checkout = 'block';
        if ( 'on' !== $wbm_checkout_page_stored_results_serialize_banner_enable_status ) {
            $display_option_checkout = 'none';
        }
        ?>
									<div
										class="wbm-checkout-upload-image-html <?php 
        echo  esc_attr( $display_option_checkout ) ;
        ?>">
										<fieldset class="innerbanner">
											<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
											<?php 
        ?>
												<table class="form-table" id="form-table">
													<tbody>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_checkout_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
															</th>
															<td>
																<a class='wbm_checkout_page_single_upload_file_button button'
																	id="wbm_checkout_page_single_upload_file_button"
																	uploader_title="<?php 
        esc_attr_e( 'Select File', 'woo-banner-management' );
        ?>"
																	uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
																<a
																	class='wbm_checkout_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
															</td>
														</tr>
														<tr>
															<th scope="row"></th>
															<?php 
        
        if ( $wbm_checkout_page_stored_results_serialize_banner_src === '' ) {
            $checkout_page_benner_css = "none";
        } else {
            $checkout_page_benner_css = "block";
        }
        
        ?>
															<td>
																<img class="wbm_checkout_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $checkout_page_benner_css ) ;
        ?>"
																	src="<?php 
        echo  esc_url( $wbm_checkout_page_stored_results_serialize_banner_src ) ;
        ?>" />
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="checkout_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link' );
        ?></label>
															</th>
															<td><input type="url" id="checkout_page_banner_single_image_link"
																	title="<?php 
        esc_attr_e( 'Example: https://multidots.com', 'woo-banner-management' );
        ?>"
																	name='term_meta[banner_link]'
																	value='<?php 
        echo  esc_attr( $wbm_checkout_page_stored_results_serialize_banner_link ) ;
        ?>' />
																<p><label class="banner_link_label"
																		for="checkout_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
																</p>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_checkout_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_checkout_page_select_banner_target"
																	id="wbm_checkout_page_select_banner_target" class="">
																	<option value="self"
																		<?php 
        selected( $wbm_checkout_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																		<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="blank"
																		<?php 
        selected( $wbm_checkout_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																		<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_checkout_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_checkout_page_select_banner_relation"
																	id="wbm_checkout_page_select_banner_relation" class="">
																	<option value="follow"
																		<?php 
        selected( $wbm_checkout_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																		<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="nofollow"
																		<?php 
        selected( $wbm_checkout_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																		<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>
												<?php 
        ?>
										</fieldset>
									</div>
								</div>
							</div>
							<!--end .accordion-section-content-->
						</div>
						<!--end .accordion-section-->
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_thankyou_page_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not_thankyou = " ( Enable ) ";
            $setting_enable_or_color_thankyou = "green";
        } else {
            $setting_enable_or_not_thankyou = " ( Disable ) ";
            $setting_enable_or_color_thankyou = "red";
        }
        
        ?>
							<a class="accordion-section-title"
								href="#wbm-enable-banner-for-thankyou-page"><?php 
        esc_html_e( 'Banner for thank you page', 'woo-banner-management' );
        ?>
								<span id="thankyou_page_status_enable_or_disable"
									class="thankyou_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color_thankyou ) ;
        ?>"><?php 
        esc_html_e( $setting_enable_or_not_thankyou, 'woo-banner-management' );
        ?></span></a>
							<div id="wbm-enable-banner-for-thankyou-page" class="accordion-section-content">
								<div class="woocommerce-banner-managment-thank-you-setting-admin">
									<table class="form-table" id="form-table-wbm-thankyou-page">
										<tbody>
											<tr>
												<th scope="row"><label class="wbm_leble_setting_css"
														for="wbm_shop_setting_thank_you_page_enable"><?php 
        esc_html_e( 'Enable/Disable', 'woo-banner-management' );
        ?></label>
												</th>
												<td><input type="checkbox" value="on"
														id="wbm_shop_setting_thank_you_page_enable"
														class="wbm_shop_setting_thank_you_page_enable_or_not" <?php 
        checked( $wbm_thankyou_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>></td>
											</tr>
										</tbody>
									</table>
									<?php 
        $display_option_checkout = 'block';
        if ( 'on' !== $wbm_thankyou_page_stored_results_serialize_banner_enable_status ) {
            $display_option_checkout = 'none';
        }
        ?>
									<div
										class="wbm-thank-you-page-upload-image-html <?php 
        echo  esc_attr( $display_option_checkout ) ;
        ?>">
										<fieldset class="innerbanner">
											<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
											<?php 
        ?>
												<table class="form-table" id="form-table">
													<tbody>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_thankyou_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
															</th>
															<td>
																<a class='wbm_thankyou_page_single_upload_file_button button'
																	id="wbm_thankyou_page_single_upload_file_button"
																	uploader_title="<?php 
        esc_attr_e( 'Select File', 'woo-banner-management' );
        ?>"
																	uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
																<a
																	class='wbm_thankyou_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
															</td>
														</tr>
														<tr>
															<th scope="row"></th>
															<?php 
        
        if ( $wbm_thankyou_page_stored_results_serialize_banner_src === '' ) {
            $thankyou_page_benner_css = "no-image";
        } else {
            $thankyou_page_benner_css = "yes-image";
        }
        
        ?>
															<td>
																<img class="wbm_thankyou_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $thankyou_page_benner_css ) ;
        ?>"
																	src="<?php 
        echo  esc_url( $wbm_thankyou_page_stored_results_serialize_banner_src ) ;
        ?>" />
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="thankyou_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link', 'woo-banner-management' );
        ?></label>
															</th>
															<td><input type="url" id="thankyou_page_banner_single_image_link"
																	title="<?php 
        esc_attr_e( 'Example: https://multidots.com', 'woo-banner-management' );
        ?>"
																	name='term_meta[banner_link]'
																	value='<?php 
        echo  esc_attr( $wbm_thankyou_page_stored_results_serialize_banner_link ) ;
        ?>' />
																<p><label class="banner_link_label"
																		for="thankyou_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
																</p>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_thankyou_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_thankyou_page_select_banner_target"
																	id="wbm_thankyou_page_select_banner_target" class="">
																	<option value="self"
																		<?php 
        selected( $wbm_thankyou_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																		<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="blank"
																		<?php 
        selected( $wbm_thankyou_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																		<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_thankyou_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_thankyou_page_select_banner_relation"
																	id="wbm_thankyou_page_select_banner_relation" class="">
																	<option value="follow"
																		<?php 
        selected( $wbm_thankyou_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																		<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="nofollow"
																		<?php 
        selected( $wbm_thankyou_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																		<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>
												<?php 
        ?>
									</div>
								</div>
							</div>
							<!--end .accordion-section-content-->
						</div>
						<!--end .accordion-section-->
						<!--end .accordion-section-->
					</div>
				</fieldset>
				<fieldset class="wbm_global common_settings">
					<legend>
						<div class="wbm-setting-header">
							<h2><?php 
        esc_html_e( 'Banner Slider Settings', 'woo-banner-management' );
        ?></h2>
						</div>
					</legend>	
					<p><?php 
        esc_html_e( 'You can set slider for Woocommerce products and categories.', 'woo-banner-management' );
        ?></p>
					<div class="accordion">
						<!--start .accordion-section-->
						<div class="accordion-section">
							<a class="accordion-section-title"
								href="#wbm-enable-for-product-slider"><?php 
        esc_html_e( 'WooCommerce product slider settings ', 'woo-banner-management' );
        ?>
							</a>
							<div id="wbm-enable-for-product-slider" class="accordion-section-content">
								<div class="global_settings">
									<div class="wbm-prod-slider-settings-html">
										<fieldset class="innerbanner">
											<legend><?php 
        esc_html_e( 'Select Shortcode Options', 'woo-banner-management' );
        ?></legend>
											<div class="wbm-widgets-cols">
												<div class="wbm-col-left">
													<div class="wbm-widgets-left">
														<table class="form-table" id="form-table-wbm-thankyou-page">
															<tbody>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm_prod_slider_setting_on_sale"><?php 
        esc_html_e( 'On Sale', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Enable this option if you want to showcase on sale products in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<label class="wbm_prod_slider_toggle_switch">
																			<input type="checkbox" value="on" id="wbm_prod_slider_setting_on_sale" class="wbm_prod_slider_setting_on_sale" <?php 
        checked( $product_slider_settings_on_sale_val, 'on' );
        ?>>
																			<span class="wbm_prod_slider_toggle_btn"></span>
																		</label>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm_prod_slider_setting_on_featured_product"><?php 
        esc_html_e( 'Featured Products', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Select Featured Products which you want to display on slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        ?>
																		<select name="featured-product[]" multiple="multiple" id="featured-product" data-placeholder="<?php 
        esc_attr_e( 'Select a Featured Product', 'woo-banner-management' );
        ?>" data-minimum_input_length="3">
																			<?php 
        // query for get featured products list
        $meta_query = WC()->query->get_meta_query();
        $tax_query = WC()->query->get_tax_query();
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        );
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'tax_query'      => $tax_query,
        );
        $featured_query = new WP_Query( $args );
        if ( $featured_query->have_posts() ) {
            while ( $featured_query->have_posts() ) {
                $featured_query->the_post();
                ?>
																				<option value="<?php 
                esc_attr_e( $featured_query->post->ID );
                ?>" <?php 
                echo  ( in_array( $featured_query->post->ID, $product_slider_settings_featured_prod_val ) ? 'selected' : '' ) ;
                ?>><?php 
                the_title();
                ?></option>
																			<?php 
            }
        }
        ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm-price-range"><?php 
        esc_html_e( 'Price Range', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Select Price range for display products by price range.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        //Get all products
        $args = array();
        if ( function_exists( 'wc_get_products' ) ) {
            $products = wc_get_products( $args );
        }
        //get all prices
        $all_prices = array();
        if ( isset( $products ) && !empty($products) ) {
            foreach ( $products as $product ) {
                $all_prices[] = $product->get_price();
            }
        }
        //Get maximum price value from the price array
        $max_price = '';
        
        if ( isset( $all_prices ) && !empty($all_prices) ) {
            array_values( array_filter( $all_prices ) );
            rsort( $all_prices );
            $max_price = $all_prices['0'];
        }
        
        ?>
																		<input type="hidden" id="wbm_max_price" value="<?php 
        esc_attr_e( $product_slider_price_range_val );
        ?>" max-price="<?php 
        esc_attr_e( $max_price );
        ?>">
																		<input type="text" name="wbm_prod_slider_setting_price" id="wbm-price-range" >
																		<div id="wbm-price-range-slider"></div>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<?php 
        ?>
																			<label class="wbm_leble_setting_css wcbm-in-pro"><?php 
        esc_html_e( 'By Categories', 'woo-banner-management' );
        ?><span><?php 
        esc_html_e( ' - Available in pro', 'woo-banner-management' );
        ?></span></label>
																			<?php 
        ?>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Select category if you want to display product by categories in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        ?>
																			<select name="product-categories[]" multiple="multiple" data-placeholder="<?php 
        esc_attr_e( 'Select a Category', 'woo-banner-management' );
        ?>" id="product-categories">
																			</select>
																			<?php 
        ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<div class="wbm-col-right">
													<div class="wbm-widgets-right">
														<div class="wbm-shortcode-content">
															<h2 class="wbm-shortcode-title"><?php 
        esc_html_e( 'Shortcode', 'woo-banner-management' );
        ?></h2>
															<p><?php 
        esc_html_e( 'Copy and paste this shortcode into your posts or pages:', 'woo-banner-management' );
        ?></p>
															<div class="wbm-shortcode-wrap">
																<div class="wbm-code">[wcbm_product_slider<span class="on-sale"></span><span class="featured-prod"></span><span class="price-range"></span><span class="categories"></span>]</div>
																<div class="wbm-after-copy-text" style="margin-top:6px;opacity:0;"><?php 
        esc_html_e( 'Shortcode  Copied to Clipboard!', 'woo-banner-management' );
        ?></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div>
						<!--end .accordion-section-->
						<!--start .accordion-section-->
						<div class="accordion-section">
							<a class="accordion-section-title"
								href="#wbm-enable-for-category-slider"><?php 
        esc_html_e( 'WooCommerce categories slider settings ', 'woo-banner-management' );
        ?>
							</a>
							<div id="wbm-enable-for-category-slider" class="accordion-section-content">
								<div class="global_settings">
									<div class="wbm-cat-slider-settings-html">
										<fieldset class="innerbanner">
											<legend><?php 
        esc_html_e( 'Select Shortcode Options', 'woo-banner-management' );
        ?></legend>
											<div class="wbm-widgets-cols">
												<div class="wbm-col-left">
													<div class="wbm-widgets-left">
														<table class="form-table" id="form-table-wbm-thankyou-page">
															<tbody>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css"><?php 
        esc_html_e( 'Select Categories', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Select categories name which you want to showcase in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<select name="categories-slider[]" multiple="multiple" data-placeholder="<?php 
        esc_attr_e( 'Select a Category', 'woo-banner-management' );
        ?>" id="categories-slider">
																			<?php 
        // get woocommerce products categories
        $cat_slider_terms = get_terms( 'product_cat', array(
            'hide_empty' => false,
        ) );
        if ( !empty($cat_slider_terms) && !is_wp_error( $cat_slider_terms ) ) {
            foreach ( $cat_slider_terms as $product_term ) {
                ?>
															                        <option value="<?php 
                echo  esc_attr( $product_term->term_id ) ;
                ?>" <?php 
                echo  ( in_array( $product_term->term_id, $product_cat_slider_settings_cat_val ) ? 'selected' : '' ) ;
                ?>>
															                        	<?php 
                echo  esc_html( $product_term->name ) ;
                ?></option>
																					<?php 
            }
        }
        ?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_by_title"><?php 
        esc_html_e( 'Title', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Enable this option if you want to showcase the category title in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<label class="wbm_prod_slider_toggle_switch">
																			<input type="checkbox" value="on" id="wbm_cat_slider_setting_by_title" class="wbm_cat_slider_setting_by_title" checked="checked" disabled="disabled">
																			<span class="wbm_prod_slider_toggle_btn"></span>
																		</label>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_feature_img"><?php 
        esc_html_e( 'Feature Image', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Enable this option if you want to showcase the category feature image in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        if ( empty($product_cat_slider_settings_feature_img_val) ) {
            $product_cat_slider_settings_feature_img_val = 'on';
        }
        ?>
																		<label class="wbm_prod_slider_toggle_switch">
																			<input type="checkbox" value="on" id="wbm_cat_slider_setting_feature_img" class="wbm_cat_slider_setting_feature_img" <?php 
        echo  ( isset( $product_cat_slider_settings_feature_img_val ) && $product_cat_slider_settings_feature_img_val === 'on' ? 'checked="checked"' : '' ) ;
        ?>>
																			<span class="wbm_prod_slider_toggle_btn"></span>
																		</label>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_by_desc"><?php 
        esc_html_e( 'Description', 'woo-banner-management' );
        ?></label>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Enable this option if you want to showcase the category description in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        if ( empty($product_cat_slider_settings_by_desc_val) ) {
            $product_cat_slider_settings_by_desc_val = 'on';
        }
        ?>
																		<label class="wbm_prod_slider_toggle_switch">
																			<input type="checkbox" value="on" id="wbm_cat_slider_setting_by_desc" class="wbm_cat_slider_setting_by_desc" <?php 
        echo  ( isset( $product_cat_slider_settings_by_desc_val ) && $product_cat_slider_settings_by_desc_val === 'on' ? 'checked="checked"' : '' ) ;
        ?>>
																			<span class="wbm_prod_slider_toggle_btn"></span>
																		</label>
																	</td>
																</tr>
																<tr>
																	<th scope="row">
																		<?php 
        ?>
																			<label class="wbm_leble_setting_css wcbm-in-pro" for="wbm_cat_slider_setting_by_count"><?php 
        esc_html_e( 'Total Quantity', 'woo-banner-management' );
        ?><span><?php 
        esc_html_e( ' - Available in pro', 'woo-banner-management' );
        ?></span></label>
																			<?php 
        ?>
																		<span class="banner-woocommerce-help-tip">
																			<div class="alert-desc"><?php 
        esc_html_e( 'Enable this option if you want to showcase the category\'s product count in slider.', 'woo-banner-management' );
        ?></div>
																	</span>
																	</th>
																	<td>
																		<?php 
        ?>
																			<label class="wbm_prod_slider_toggle_switch wbm-pro-toggle">
																				<input type="checkbox" value="on" id="wbm_cat_slider_setting_by_count" class="wbm_cat_slider_setting_by_count" disabled="disabled">
																				<span class="wbm_prod_slider_toggle_btn"></span>
																			</label>
																			<?php 
        ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<div class="wbm-col-right">
													<div class="wbm-widgets-right">
														<div class="wbm-shortcode-content">
															<h2 class="wbm-shortcode-title"><?php 
        esc_html_e( 'Shortcode', 'woo-banner-management' );
        ?></h2>
															<p><?php 
        esc_html_e( 'Copy and paste this shortcode into your posts or pages:', 'woo-banner-management' );
        ?></p>
															<div class="wbm-shortcode-wrap">
																<div class="wbm-code">[wcbm_category_slider<span class="cat-slider"></span><span class="by-title"></span><span class="by-feature-img"></span><span class="by-description"></span><span class="by-count"></span>]
																</div>
																<div class="wbm-after-copy-text" style="margin-top:6px;opacity:0;"><?php 
        esc_html_e( 'Shortcode  Copied to Clipboard!', 'woo-banner-management' );
        ?></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div>
						<!--end .accordion-section-->
					</div>	
				</fieldset>
				<fieldset class="wbm_global common_settings">
					<legend>
						<div class="wbm-setting-header">
							<h2><?php 
        esc_html_e( 'Global Settings', 'woo-banner-management' );
        ?></h2>
						</div>
					</legend>	
					<p><?php 
        esc_html_e( 'You can set global options for Woocommerce pages and products.', 'woo-banner-management' );
        ?></p>
					<div class="accordion">
						<!--start .accordion-section-->
						<div class="accordion-section">
							<a class="accordion-section-title"
								href="#wbm-enable-for-banner-width"><?php 
        esc_html_e( 'Default woocommerce page banner settings', 'woo-banner-management' );
        ?>
							</a>
							<div id="wbm-enable-for-banner-width" class="accordion-section-content">
								<div class="global_settings">
									<table class="form-table" id="form-table">
										<tr>
											<th scope="row"><label class="wbm_leble_setting_css"
													for="shop_page_banner_image_size"><?php 
        esc_html_e( 'Select Banner Size', 'woo-banner-management' );
        ?></label>
													<span class="banner-woocommerce-help-tip"><div class="alert-desc">
														<?php 
        esc_html_e( 'Select the size of banner from dropdown which will apply on all the default woocommerce pages.', 'woo-banner-management' );
        ?>
													</div></span>
											</th>
											<td><select name="term_meta[shop_page_select_size]" class="shop-select-size-type"
													id="shop_page_banner_image_size">
													<option value=""
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '' );
        ?>>
														<?php 
        esc_html_e( '-- Select Banner Size --', 'woo-banner-management' );
        ?>
													</option>
													<option value="25"
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '25' );
        ?>>
														<?php 
        esc_html_e( '25%', 'woo-banner-management' );
        ?>
													</option>
													<option value="50"
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '50' );
        ?>>
														<?php 
        esc_html_e( '50%', 'woo-banner-management' );
        ?>
													</option>
													<option value="75"
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '75' );
        ?>>
														<?php 
        esc_html_e( '75%', 'woo-banner-management' );
        ?>
													</option>
													<option value="100"
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '100' );
        ?>>
														<?php 
        esc_html_e( '100%', 'woo-banner-management' );
        ?>
													</option>
													<option value="1080"
														<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '1080' );
        ?>>
														<?php 
        esc_html_e( 'Fixed to container(1080px)', 'woo-banner-management' );
        ?>
													</option>
												</select></td>
										</tr>
										<tr>
									</table>
								</div>
							</div>
						</div>	
						<!--end .accordion-section-->
						<!--start .accordion-section-->
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_banner_detail_page_stored_results_serialize_banner_enable_status === 'on' || $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not_banner_detail = " ( Enable ) ";
            $setting_enable_or_color_banner_detail = "green";
        } else {
            $setting_enable_or_not_banner_detail = " ( Disable ) ";
            $setting_enable_or_color_banner_detail = "red";
        }
        
        ?>
							<a class="accordion-section-title"
								href="#wbm-enable-banner-for-product-detail-page"><?php 
        esc_html_e( 'Product page banner settings', 'woo-banner-management' );
        ?>
								<span id="banner_detail_page_status_enable_or_disable"
									class="banner_detail_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color_banner_detail ) ;
        ?>"><?php 
        esc_html_e( $setting_enable_or_not_banner_detail, 'woo-banner-management' );
        ?></span></a>
							<div id="wbm-enable-banner-for-product-detail-page" class="accordion-section-content">
								<div class="woocommerce-banner-managment-thank-you-setting-admin">
									<table class="form-table" id="form-table-wbm-banner_detail-page">
										<tbody>
										<tr>
												<th scope="row"><label class="wbm_leble_setting_css"
														for="wbm_shop_setting_banner_detail_page_enable"><?php 
        esc_html_e( 'Enable product page banner settings', 'woo-banner-management' );
        ?></label>
														<span class="banner-woocommerce-help-tip"><div class="alert-desc">
														<?php 
        esc_html_e( 'If selected then, enable the product page banner setting meta box section for each product.', 'woo-banner-management' );
        ?>
													</div></span>
												</th>
												<td><input type="checkbox" value="on"
														id="wbm_shop_setting_banner_detail_page_section_enable"
														class="wbm_shop_setting_banner_detail_page_section_enable_or_not" <?php 
        checked( $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status, 'on' );
        ?>></td>
											</tr>
											<tr>
												<th scope="row"><label class="wbm_leble_setting_css"
														for="wbm_shop_setting_banner_detail_page_enable"><?php 
        esc_html_e( 'Set common banner for all product', 'woo-banner-management' );
        ?></label>
														<span class="banner-woocommerce-help-tip"><div class="alert-desc">
														<?php 
        esc_html_e( 'If selected then, override all the product page banner se
														ttings and set these updated settings globally for all products.', 'woo-banner-management' );
        ?>
													</div></span>
												</th>
												<td><input type="checkbox" value="on"
														id="wbm_shop_setting_banner_detail_page_enable"
														class="wbm_shop_setting_banner_detail_page_enable_or_not" <?php 
        checked( $wbm_banner_detail_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>></td>
											</tr>
										</tbody>
									</table>
								<?php 
        $display_option_checkout = 'block';
        if ( 'on' !== $wbm_banner_detail_page_stored_results_serialize_banner_enable_status ) {
            $display_option_checkout = 'none';
        }
        ?>
								<div
									class="wbm-banner_detail-page-upload-image-html <?php 
        echo  esc_attr( $display_option_checkout ) ;
        ?>">
									<fieldset class="innerbanner">
										<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
										<?php 
        ?>
												<table class="form-table" id="form-table">
													<tbody>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_banner_detail_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
															</th>
															<td>
																<a class='wbm_banner_detail_page_single_upload_file_button button'
																	id="wbm_banner_detail_page_single_upload_file_button"
																	uploader_title="<?php 
        esc_attr_e( 'Select File', 'woo-banner-management' );
        ?>"
																	uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
																<a
																	class='wbm_banner_detail_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
															</td>
														</tr>
														<tr>
															<th scope="row"></th>
															<?php 
        
        if ( $wbm_banner_detail_page_stored_results_serialize_banner_src === '' ) {
            $banner_detail_page_benner_css = "no-image";
        } else {
            $banner_detail_page_benner_css = "yes-image";
        }
        
        ?>
															<td>
																<img class="wbm_banner_detail_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $banner_detail_page_benner_css ) ;
        ?>"
																	src="<?php 
        echo  esc_url( $wbm_banner_detail_page_stored_results_serialize_banner_src ) ;
        ?>" />
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="banner_detail_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link', 'woo-banner-management' );
        ?></label>
															</th>
															<td><input type="url" id="banner_detail_page_banner_single_image_link"
																	title="<?php 
        esc_attr_e( 'Example: https://multidots.com', 'woo-banner-management' );
        ?>"
																	name='term_meta[banner_link]'
																	value='<?php 
        echo  esc_attr( $wbm_banner_detail_page_stored_results_serialize_banner_link ) ;
        ?>' />
																<p><label class="banner_link_label"
																		for="banner_detail_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
																</p>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_banner_global_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_banner_global_page_select_banner_target"
																	id="wbm_banner_global_page_select_banner_target" class="">
																	<option value="self"
																		<?php 
        selected( $wbm_banner_global_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																		<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="blank"
																		<?php 
        selected( $wbm_banner_global_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																		<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
														<tr>
															<th scope="row"><label class="wbm_leble_setting_css"
																	for="wbm_banner_global_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
															</th>
															<td><select name="wbm_banner_global_page_select_banner_relation"
																	id="wbm_banner_global_page_select_banner_relation" class="">
																	<option value="follow"
																		<?php 
        selected( $wbm_banner_global_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																		<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																	</option>
																	<option value="nofollow"
																		<?php 
        selected( $wbm_banner_global_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																		<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																	</option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>
											<?php 
        ?>
									</div>
								</div>
							</div>
						</div>
						<!-- banner for other pages -->
						<div class="accordion-section">
							<?php 
        
        if ( $wbm_other_page_stored_results_serialize_banner_enable_status === 'on' || isset( $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) && $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status === 'on' ) {
            $setting_enable_or_not = " ( Enable ) ";
            $setting_enable_or_color = "green";
        } else {
            $setting_enable_or_not = " ( Disable ) ";
            $setting_enable_or_color = "red";
        }
        
        ?>
							<a class="accordion-section-title" href="#wbm-enable-banner-for-other-pages">
								<?php 
        esc_html_e( 'Other new page banner settings', 'woo-banner-management' );
        ?>
								<span id="other_page_status_enable_or_disable"
									class="other_page_status_enable_or_disable <?php 
        echo  esc_attr( $setting_enable_or_color ) ;
        ?>">
									<?php 
        esc_html_e( $setting_enable_or_not, 'woo-banner-management' );
        ?>
								</span>
							</a>
							<div id="wbm-enable-banner-for-other-pages" class="accordion-section-content">

								<table class="form-table" id="form-table-wbm-banner_detail-page">
									<tbody>
										<?php 
        ?>
												<tr class="wbm_other_page_setting_banner_detail_in_pro">
													<th scope="row"><label class="wbm_leble_setting_css"
															for="wbm_other_page_setting_banner_detail_enable"><?php 
        esc_html_e( 'Enable other page banner settings', 'woo-banner-management' );
        ?><span><?php 
        esc_html_e( ' - Available in pro', 'woo-banner-management' );
        ?></span></label>
															<span class="banner-woocommerce-help-tip"><div class="alert-desc">
															<?php 
        esc_html_e( 'If selected then, enable website other pages banner setting meta box section for each page.', 'woo-banner-management' );
        ?>
														</div></span>
													</th>
													<td><input type="checkbox" value="on"
															id="wbm_other_page_setting_banner_detail_enable"
															class="wbm_other_setting_banner_detail_page_section_enable_or_not"></td>
												</tr>
												<?php 
        ?>
										<tr>
											<th scope="row"><label class="wbm_leble_setting_css"
													for="wbm_other_page_setting_enable"><?php 
        esc_html_e( 'Set global banner for pages', 'woo-banner-management' );
        ?>
												</label>
												<span class="banner-woocommerce-help-tip">
													<div class="alert-desc">
														<?php 
        esc_html_e( 'If selected then, override all the pages banner settings and set these updated settings globally for all pages.', 'woo-banner-management' );
        ?>
													</div>
												</span>
											</th>
											<td><input type="checkbox" value="on" id="wbm_other_page_setting_enable"
													class="wbm_other_page_setting_enable_or_not" <?php 
        checked( $wbm_other_page_stored_results_serialize_banner_enable_status, 'on' );
        ?>>
											</td>
										</tr>
									</tbody>
								</table>
								<?php 
        $display_option = 'block';
        if ( 'on' !== $wbm_other_page_stored_results_serialize_banner_enable_status ) {
            $display_option = 'none';
        }
        ?>
								<div class="wbm_other_page_enable_open_div <?php 
        echo  esc_attr( $display_option ) ;
        ?>">
									<fieldset class="innerbanner">
										<legend><?php 
        esc_html_e( 'General Settings', 'woo-banner-management' );
        ?></legend>
										<?php 
        ?>
											<table class="form-table" id="form-table">
												<tbody>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_other_page_single_upload_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
														</th>
														<td><a class='wbm_other_page_single_upload_file_button button'
																id="wbm_other_page_single_upload_file_button"
																uploader_title='Select File'
																uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
															<a
																class='wbm_other_page_remove_single_file button'><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
														</td>
													</tr>
													<tr>
														<th scope="row"></th>
														<?php 
        
        if ( '' === $wbm_other_page_stored_results_serialize_banner_src ) {
            $other_page_benner_css = "no-image";
        } else {
            $other_page_benner_css = "yes-image";
        }
        
        ?>
														<td>
															<img class="wbm_other_page_cat_banner_img_admin_single <?php 
        echo  esc_attr( $other_page_benner_css ) ;
        ?>"
																src="<?php 
        echo  esc_url( $wbm_other_page_stored_results_serialize_banner_src ) ;
        ?>" />
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="other_page_banner_single_image_link"><?php 
        esc_html_e( 'Banner Image Link', 'woo-banner-management' );
        ?></label>
														</th>
														<td><input type="url" id="other_page_banner_single_image_link"
																title="Example: https://multidots.com"
																name='term_meta[banner_link]'
																value='<?php 
        echo  esc_attr( $wbm_other_page_stored_results_serialize_banner_link ) ;
        ?>' />
															<p><label class="banner_link_label"
																	for="other_page_banner_single_image_link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
															</p>
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_other_page_select_banner_target"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_other_page_select_banner_target"
																id="wbm_other_page_select_banner_target" class="">
																<option value="self"
																	<?php 
        selected( $wbm_other_page_stored_results_serialize_banner_target, 'self' );
        ?>>
																	<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
																</option>
																<option value="blank"
																	<?php 
        selected( $wbm_other_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
																	<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>
													<tr>
														<th scope="row"><label class="wbm_leble_setting_css"
																for="wbm_other_page_select_banner_relation"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
														</th>
														<td><select name="wbm_other_page_select_banner_relation"
																id="wbm_other_page_select_banner_relation" class="">
																<option value="follow"
																	<?php 
        selected( $wbm_other_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
																	<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
																</option>
																<option value="nofollow"
																	<?php 
        selected( $wbm_other_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
																	<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
																</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
											<?php 
        ?>
									</fieldset>

								</div>
                    		</div>
							<!--end .accordion-section-content-->
						</div>
					</div>
					<!--end .accordion-section-->
				</fieldset>
				<input type="button" name="save_wbmshop" id="save_wbm_shop_page_setting" class="button button-primary"
					value="<?php 
        echo  esc_attr( 'Save Changes', 'woo-banner-management' ) ;
        ?>">
				<img class="banner-setting-loader"
					src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif' ) ;
        ?>" alt="ajax-loader" />
				<fieldset id="product_banner_previou_section" class="wbm_global">
					<legend>
						<div class="wbm-setting-header">
							<h2><?php 
        esc_html_e( 'Products, Common pages, Categories, and Tags preview and settings', 'woo-banner-management' );
        ?></h2>
						</div>
					</legend>
					<div class="accordion">
						<div class="accordion-section">
							<a class="accordion-section-title" href="#wbm-enable-product-banner-settings-ss"><?php 
        esc_html_e( 'Product specific banner settings', 'woo-banner-management' );
        ?></a>
							<div id="wbm-enable-product-banner-settings-ss" class="accordion-section-content">
								<p><?php 
        esc_html_e( 'You can upload custom banner at the top of your product pages. Easily update the image through your product edit page.', 'woo-banner-management' );
        ?>
								</p>
								<p>
									<a href="javascript:void(0);" class="wbm-open-popup"><?php 
        esc_html_e( 'Click here', 'woo-banner-management' );
        ?></a><?php 
        esc_html_e( ' to preview product page banner settings.', 'woo-banner-management' );
        ?>
								</p>
								<div class="wbm-popup-image">
									<div class="wbm-popup-outer">
										<div class="wbm-popup-inner">
											<i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
											<img class="preview_category_page_image" src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/product_banner_metabox_setting_image.png' ) ;
        ?>">
										</div>
									</div>
								</div>
								<p>
									<strong><?php 
        esc_html_e( 'Go to product page', 'woo-banner-management' );
        ?></strong>
									<a target="_blank"
										href="<?php 
        echo  esc_url( site_url() . '/wp-admin/edit.php?post_type=product' ) ;
        ?>"><?php 
        esc_html_e( 'click here', 'woo-banner-management' );
        ?></a>
								</p>
							</div>
						</div>
						<div class="accordion-section">
							<a class="accordion-section-title" href="#wbm-enable-other-page-banner-settings-ss"><?php 
        esc_html_e( 'Other pages specific banner settings', 'woo-banner-management' );
        ?></a>
							<div id="wbm-enable-other-page-banner-settings-ss" class="accordion-section-content">
								<p><?php 
        esc_html_e( 'You can upload custom banner at the top of your website other pages. Easily update the image through your website other pages edit page.', 'woo-banner-management' );
        ?>
								</p>
								<p>
									<a href="javascript:void(0);" class="wbm-open-popup"><?php 
        esc_html_e( 'Click here', 'woo-banner-management' );
        ?></a><?php 
        esc_html_e( ' to preview common page banner settings.', 'woo-banner-management' );
        ?>
								</p>
								<div class="wbm-popup-image">
									<div class="wbm-popup-outer">
										<div class="wbm-popup-inner">
											<i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
											<img class="preview_category_page_image" src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/page_banner_metabox_setting_image.png' ) ;
        ?>">
										</div>
									</div>
								</div>
								<p>
									<strong><?php 
        esc_html_e( 'Go to website other page', 'woo-banner-management' );
        ?></strong>
									<a target="_blank"
										href="<?php 
        echo  esc_url( site_url() . '/wp-admin/edit.php?post_type=page' ) ;
        ?>"><?php 
        esc_html_e( 'click here', 'woo-banner-management' );
        ?></a>
								</p>
							</div>
						</div>
						<div class="accordion-section">
							<a class="accordion-section-title" href="#wbm-enable-category-banner-settings-ss"><?php 
        esc_html_e( 'Category specific banner settings', 'woo-banner-management' );
        ?></a>
							<div id="wbm-enable-category-banner-settings-ss" class="accordion-section-content">
								<p><?php 
        esc_html_e( 'You can upload custom banner at the top of your product category pages. Easily update the image through your product category edit page.', 'woo-banner-management' );
        ?>
								</p>
								<p>
									<a href="javascript:void(0);" class="wbm-open-popup"><?php 
        esc_html_e( 'Click here', 'woo-banner-management' );
        ?></a><?php 
        esc_html_e( ' to preview product category page banner settings.', 'woo-banner-management' );
        ?>
								</p>
								<div class="wbm-popup-image">
									<div class="wbm-popup-outer">
										<div class="wbm-popup-inner">
											<i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
											<?php 
        ?>
													<img class="preview_category_page_image" src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/category_setting_image.png' ) ;
        ?>">
											<?php 
        ?>
										</div>
									</div>
								</div>
								<p>
									<strong><?php 
        esc_html_e( 'Go to category page', 'woo-banner-management' );
        ?></strong>
									<a target="_blank"
										href="<?php 
        echo  esc_url( site_url() . '/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product' ) ;
        ?>"><?php 
        esc_html_e( 'click here', 'woo-banner-management' );
        ?></a>
								</p>
							</div>
						</div>
						<div class="accordion-section">
							<a class="accordion-section-title" href="#wbm-enable-tag-banner-settings-ss"><?php 
        esc_html_e( 'Tag specific banner settings', 'woo-banner-management' );
        ?></a>
							<div id="wbm-enable-tag-banner-settings-ss" class="accordion-section-content">
								<p><?php 
        esc_html_e( 'You can upload custom banner at the top of your product tag pages. Easily update the image through your product tag edit page.', 'woo-banner-management' );
        ?>
								</p>
								<p>
									<a href="javascript:void(0);" class="wbm-open-popup"><?php 
        esc_html_e( 'Click here', 'woo-banner-management' );
        ?></a><?php 
        esc_html_e( ' to preview product tag page banner settings.', 'woo-banner-management' );
        ?>
								</p>
								<div class="wbm-popup-image">
									<div class="wbm-popup-outer">
										<div class="wbm-popup-inner">
											<i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
											<?php 
        ?>
													<img class="preview_category_page_image" src="<?php 
        echo  esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/tag_setting_image.png' ) ;
        ?>">
											<?php 
        ?>
										</div>
									</div>
								</div>
								<p>
									<strong><?php 
        esc_html_e( 'Go to Tag page', 'woo-banner-management' );
        ?></strong>
									<a target="_blank"
										href="<?php 
        echo  esc_url( site_url() . '/wp-admin/edit-tags.php?taxonomy=product_tag&post_type=product' ) ;
        ?>"><?php 
        esc_html_e( 'click here', 'woo-banner-management' );
        ?></a>
								</p>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<?php 
    }
    
    /**
     *    Set the custom html for category edit field
     *
     */
    function wcbm_product_cat_taxonomy_custom_fields( $tag )
    {
        $t_id = $tag->term_id;
        $term_meta = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $t_id ) : '' );
        
        if ( isset( $term_meta['banner_url_id'] ) && '' !== $term_meta['banner_url_id'] ) {
            $banner_url = $term_meta['banner_url_id'];
        } else {
            $banner_url = '';
        }
        
        // Get banner link
        
        if ( isset( $term_meta['banner_link'] ) and '' !== $term_meta['banner_link'] ) {
            $banner_link = $term_meta['banner_link'];
        } else {
            $banner_link = '';
        }
        
        
        if ( isset( $term_meta['auto_display_banner'] ) && 'on' === $term_meta['auto_display_banner'] || !isset( $term_meta['auto_display_banner'] ) ) {
            $auto_display_banner = true;
        } else {
            $auto_display_banner = false;
        }
        
        
        if ( isset( $term_meta['cat_page_select_image'] ) && '' !== $term_meta['cat_page_select_image'] ) {
            $cat_page_select_image = $term_meta['cat_page_select_image'];
        } else {
            $cat_page_select_image = 'cat-single-image';
        }
        
        
        if ( isset( $term_meta['cat_page_select_target'] ) && '' !== $term_meta['cat_page_select_target'] ) {
            $wbm_shop_page_stored_results_serialize_banner_target = $term_meta['cat_page_select_target'];
        } else {
            $wbm_shop_page_stored_results_serialize_banner_target = 'blank';
        }
        
        
        if ( isset( $term_meta['cat_page_select_relation'] ) && '' !== $term_meta['cat_page_select_relation'] ) {
            $wbm_shop_page_stored_results_serialize_banner_relation = $term_meta['cat_page_select_relation'];
        } else {
            $wbm_shop_page_stored_results_serialize_banner_relation = 'follow';
        }
        
        
        if ( isset( $term_meta['cat_page_select_size'] ) && '' !== $term_meta['cat_page_select_size'] ) {
            $cat_page_select_size = $term_meta['cat_page_select_size'];
        } else {
            $cat_page_select_size = '';
        }
        
        
        if ( isset( $term_meta['cat_page_banner_title_color'] ) && '' !== $term_meta['cat_page_banner_title_color'] ) {
            $cat_page_banner_title_color = $term_meta['cat_page_banner_title_color'];
        } else {
            $cat_page_banner_title_color = '';
        }
        
        
        if ( isset( $term_meta['cat_page_banner_button_text_color'] ) && '' !== $term_meta['cat_page_banner_button_text_color'] ) {
            $cat_page_banner_button_text_color = $term_meta['cat_page_banner_button_text_color'];
        } else {
            $cat_page_banner_button_text_color = '';
        }
        
        
        if ( isset( $term_meta['cat_page_banner_button_bg_color'] ) && '' !== $term_meta['cat_page_banner_button_bg_color'] ) {
            $cat_page_banner_button_bg_color = $term_meta['cat_page_banner_button_bg_color'];
        } else {
            $cat_page_banner_button_bg_color = '';
        }
        
        
        if ( isset( $term_meta['cat_banner_title_font_size'] ) && '' !== $term_meta['cat_banner_title_font_size'] ) {
            $cat_banner_title_font_size = $term_meta['cat_banner_title_font_size'];
        } else {
            $cat_banner_title_font_size = '';
        }
        
        
        if ( isset( $term_meta['display_cate_title_flag'] ) && 'on' === $term_meta['display_cate_title_flag'] ) {
            $display_cate_title_flag = true;
        } else {
            $display_cate_title_flag = false;
        }
        
        
        if ( isset( $term_meta['cat_page_banner_button_text'] ) ) {
            $cat_page_banner_button_text = $term_meta['cat_page_banner_button_text'];
        } else {
            $cat_page_banner_button_text = '';
        }
        
        
        if ( isset( $term_meta['cat_page_banner_button_link'] ) ) {
            $cat_page_banner_button_link = $term_meta['cat_page_banner_button_link'];
        } else {
            $cat_page_banner_button_link = '';
        }
        
        
        if ( isset( $term_meta['cat_page_banner_description'] ) ) {
            $cat_page_banner_description = $term_meta['cat_page_banner_description'];
        } else {
            $cat_page_banner_description = '';
        }
        
        ?>
		<tr class="form-field wbm-spacing"><th></th></tr>
		<tr class="form-field wbm-settings-title">
			<th scope="row" valign="top">
				<?php 
        
        if ( $tag->taxonomy === 'product_cat' ) {
            ?>
					<h2><?php 
            esc_html_e( 'Category Based Banner Settings', 'woo-banner-management' );
            ?></h2>
				<?php 
        } elseif ( $tag->taxonomy === 'product_tag' ) {
            ?>
					<h2><?php 
            esc_html_e( 'Tag Based Banner Settings', 'woo-banner-management' );
            ?></h2>
				<?php 
        }
        
        ?>
			</th>
		</tr>
		<tr class="form-field wbm-spacing"><th></th></tr>
		<tr class="form-field auto_display_banner">
			<th scope="row" valign="top">
				<label for="auto_display_banner"><?php 
        esc_html_e( 'Enable/Disable', 'woo-banner-management' );
        ?></label>
			</th>
			<td class="auto_display">
				<fieldset>
					<input id="auto_display_banner" name="term_meta[auto_display_banner]" type="checkbox" value="on"
						class="auto_display_banner" <?php 
        checked( $auto_display_banner, true );
        ?> />
					<label class="auto_display_banner_label" for="auto_display_banner"><em></em></label>
				</fieldset>
			</td>
			<td><?php 
        
        if ( $auto_display_banner ) {
            esc_html_e( 'Preview', 'woo-banner-management' );
            ?>:
				<a href="<?php 
            echo  esc_url( get_category_link( $t_id ) ) ;
            ?>"
					target="_blank"><?php 
            esc_html_e( 'Click here', 'woo-banner-management' );
            ?></a><?php 
        }
        
        ?>
			</td>
		</tr>
		<?php 
        ?>
		<tr class="form-field mdwbm_banner_url_form_field hide_cat_single_banner_upload <?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'none' ) ;
        ?>"
			id="cat-single-banner-upload">
			<th scope="row" valign="top">
				<label
					for="mdwbm_upload_single_file_button"><?php 
        esc_html_e( 'Banner Image', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<a class='mdwbm_upload_single_file_button button' id="mdwbm_upload_single_file_button"
					uploader_title="<?php 
        esc_attr_e( 'Select File', 'woo-banner-management' );
        ?>"
					uploader_button_text="<?php 
        esc_attr_e( 'Include File', 'woo-banner-management' );
        ?>"><?php 
        esc_html_e( 'Upload File', 'woo-banner-management' );
        ?></a>
				<a class='mdwbm_remove_file button'
					id="mdwbm_remove_file_id"><?php 
        esc_html_e( 'Remove File', 'woo-banner-management' );
        ?></a>
			</td>
		</tr>
		<?php 
        if ( is_numeric( $banner_url ) ) {
            $banner_url = wp_get_attachment_url( $banner_url );
        }
        ?>
		<tr class="111 form-field mdwbm_banner_image_form_field hide_cat_single_banner_image <?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image && '' !== $banner_url ? 'block' : 'none' ) ;
        ?>"
			id="cat-single-banner-image">
			<th scope="row"></th>
			<td id="display_image_id">
				<img class="cat_banner_single_img_admin <?php 
        echo  ( '' === $banner_url ? 'none' : 'block' ) ;
        ?>"
					src="<?php 
        echo  esc_url( $banner_url ) ;
        ?>" id="cat_banner_single_img_admin_id" />
				<input type="hidden" class='mdwbm_image' name='term_meta[banner_url_id]'
					value='<?php 
        echo  esc_attr( $banner_url ) ;
        ?>' id="mdwbm_image_id" />
			</td>
		</tr>
		<tr class="form-field banner_link_form_field hide_banner_link_form_field <?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'none' ) ;
        ?>"
			id="cat-single-image-link">
			<th scope="row" valign="top">
				<label for="cat-single-banner-link"><?php 
        esc_html_e( 'Banner image link', 'woo-banner-management' );
        ?></label>
			</th>
			<td>

				<input type="url" id="cat-single-banner-link" name='term_meta[banner_link]'
					value='<?php 
        echo  esc_attr( $banner_link ) ;
        ?>' />
				<label class="banner_link_label"
					for="cat-single-banner-link"><em><?php 
        esc_html_e( 'Where users will be directed if they click on the banner.', 'woo-banner-management' );
        ?></em></label>
			</td>
		</tr>
		<tr id="select_banner">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_select_target_type"><?php 
        esc_html_e( 'Select Link Target', 'woo-banner-management' );
        ?></label>
			</th>
			<td><select name="term_meta[cat_page_select_target]" class="cat-select-target-type" id="cat_select_target_type">
					<option value="self"
						<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_target, 'self' );
        ?>>
						<?php 
        esc_html_e( 'Self window', 'woo-banner-management' );
        ?>
					</option>
					<option value="blank"
						<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_target, 'blank' );
        ?>>
						<?php 
        esc_html_e( 'New window', 'woo-banner-management' );
        ?>
					</option>
				</select>
			</td>
		</tr>
		<tr id="select_banner">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_select_realtion_type"><?php 
        esc_html_e( 'Select Link Relation', 'woo-banner-management' );
        ?></label>
			</th>
			<td><select name="term_meta[cat_page_select_relation]" class="cat-select-realtion-type" id="cat_select_realtion_type">
					<option value="follow"
						<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'follow' );
        ?>>
						<?php 
        esc_html_e( 'Follow', 'woo-banner-management' );
        ?>
					</option>
					<option value="nofollow"
						<?php 
        selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'nofollow' );
        ?>>
						<?php 
        esc_html_e( 'No follow', 'woo-banner-management' );
        ?>
					</option>
				</select>
			</td>
		</tr>
		<tr id="cat_select_size_type_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_select_size_type"><?php 
        esc_html_e( 'Select Banner Size', 'woo-banner-management' );
        ?></label>
			</th>
			<td><select name="term_meta[cat_page_select_size]" class="cat-select-size-type" id="cat_select_size_type">
					<option value="" <?php 
        selected( $cat_page_select_size, '' );
        ?>>
						<?php 
        esc_html_e( '-- Select Banner Size --', 'woo-banner-management' );
        ?>
					</option>
					<option value="25" <?php 
        selected( $cat_page_select_size, '25' );
        ?>>
						<?php 
        esc_html_e( '25%', 'woo-banner-management' );
        ?>
					</option>
					<option value="50" <?php 
        selected( $cat_page_select_size, '50' );
        ?>>
						<?php 
        esc_html_e( '50%', 'woo-banner-management' );
        ?>
					</option>
					<option value="75" <?php 
        selected( $cat_page_select_size, '75' );
        ?>>
						<?php 
        esc_html_e( '75%', 'woo-banner-management' );
        ?>
					</option>
					<option value="100" <?php 
        selected( $cat_page_select_size, '100' );
        ?>>
						<?php 
        esc_html_e( '100%', 'woo-banner-management' );
        ?>
					</option>
					<option value="1080" <?php 
        selected( $cat_page_select_size, '1080' );
        ?>>
						<?php 
        esc_html_e( 'Fixed to container(1080px)', 'woo-banner-management' );
        ?>
					</option>
				</select>
			</td>
		</tr>
		<tr id="display_cate_title_flag_row"
			class="form-field <?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row" valign="top">
				<label
					for="display_cate_title_flag"><?php 
        esc_html_e( 'Show category default title?', 'woo-banner-management' );
        ?></label>
				<span class="banner-woocommerce-help-tip">
					<div class="alert-desc">
						<?php 
        esc_html_e( 'If selected then it will show the default category title on banner with center position.', 'woo-banner-management' );
        ?>
					</div>
				</span>
			</th>
			<td class="top_display">
				<fieldset>
					<input id="display_cate_title_flag" name="term_meta[display_cate_title_flag]" type="checkbox" value="on"
						class="display_cate_title_flag" <?php 
        checked( $display_cate_title_flag, true );
        ?> />
					<label class="display_cate_title_flag_label" for="display_cate_title_flag"><em></em></label>
				</fieldset>
			</td>
		</tr>
		<tr id="cat_page_banner_title_color_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_page_banner_title_color"><?php 
        esc_html_e( 'Select Category title color', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_title_color" name="term_meta[cat_page_banner_title_color]" type="text"
					value="<?php 
        echo  esc_attr( $cat_page_banner_title_color ) ;
        ?>" class="cat_banner_title_color" data-default-color="#effeff" />
			</td>
		</tr>
		<tr id="cat_page_banner_title_size_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row">
				<label class="wbm_leble_setting_css"
					for="cat_page_banner_title_color"><?php 
        esc_html_e( 'Select Category title size.', 'woo-banner-management' );
        ?></label>
				<span class="banner-woocommerce-help-tip">
					<div class="alert-desc">
						<?php 
        esc_html_e( 'Configure the font size of default banner title.', 'woo-banner-management' );
        ?>
					</div>
				</span>
			</th>
			<td>
				<input id="cat_banner_title_font_size" name="term_meta[cat_banner_title_font_size]" type="range" min="0"
					max="100" step='1' value="<?php 
        echo  esc_attr( $cat_banner_title_font_size ) ;
        ?>">
				<div class="counter_total"><?php 
        esc_html_e( $cat_banner_title_font_size, 'woo-banner-management' );
        ?></div>
			</td>
		</tr>
		<tr id="cat_page_banner_button_text_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row">
				<label class="wbm_leble_setting_css"
					for="cat_page_banner_title_color"><?php 
        esc_html_e( 'Button Text', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_button_text" name="term_meta[cat_page_banner_button_text]" type="text"
					value="<?php 
        echo  esc_attr( $cat_page_banner_button_text ) ;
        ?>">

			</td>
		</tr>
		<tr id="cat_page_banner_button_link_row"
			class="form-field <?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row">
				<label class="wbm_leble_setting_css"
					for="cat_page_banner_button_link"><?php 
        esc_html_e( 'Button Link', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_button_link" name="term_meta[cat_page_banner_button_link]" type="url"
					value="<?php 
        echo  esc_attr( $cat_page_banner_button_link ) ;
        ?>">

			</td>
		</tr>
		<tr id="cat_page_banner_button_text_color_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_page_banner_button_text_color"><?php 
        esc_html_e( 'Select button text color', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_button_text_color" name="term_meta[cat_page_banner_button_text_color]" type="text"
					value="<?php 
        echo  esc_attr( $cat_page_banner_button_text_color ) ;
        ?>" class="cat_page_banner_button_text_color"
					data-default-color="#effeff" />
			</td>
		</tr>
		<tr id="cat_page_banner_button_bg_color_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_page_banner_titlcat_page_banner_button_bg_colore_color"><?php 
        esc_html_e( 'Select button background color', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_button_bg_color" name="term_meta[cat_page_banner_button_bg_color]" type="text"
					value="<?php 
        echo  esc_attr( $cat_page_banner_button_bg_color ) ;
        ?>" class="cat_page_banner_button_bg_color"
					data-default-color="#effeff" />
			</td>
		</tr>
		<tr id="cat_page_banner_description_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row">
				<label class="wbm_leble_setting_css"
					for="cat_page_banner_description"><?php 
        esc_html_e( 'Button Description', 'woo-banner-management' );
        ?></label>
			</th>
			<td>
				<textarea name="term_meta[cat_page_banner_description]" id="cat_page_banner_description" rows="5" cols="50"
					class="large-text"><?php 
        esc_html_e( $cat_page_banner_description, 'woo-banner-management' );
        ?></textarea>
			</td>
		</tr>

		<?php 
        ?>
		</fieldset>
		<?php 
    }
    
    /**
     * Save the Woocommerce-Banner-Managment Category Data
     *
     * @param  $term_id
     */
    function wcbm_product_cat_save_taxonomy_custom_fields( $term_id )
    {
        $args = array(
            'term_meta' => array(
            'filter' => FILTER_SANITIZE_STRING,
            'flags'  => FILTER_REQUIRE_ARRAY,
        ),
        );
        $post_term_meta = filter_input_array( INPUT_POST, $args );
        
        if ( isset( $post_term_meta['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = wcbm_get_category_banner_data( $t_id );
            if ( empty($term_meta) || !is_array( $term_meta ) ) {
                $term_meta = array();
            }
            $posted_term_meta_image = array();
            $posted_term_meta = $post_term_meta['term_meta'];
            if ( !isset( $posted_term_meta['auto_display_banner'] ) ) {
                $posted_term_meta['auto_display_banner'] = 'off';
            }
            if ( !isset( $posted_term_meta['display_cate_title_flag'] ) ) {
                $posted_term_meta['display_cate_title_flag'] = 'off';
            }
            $posted_term_meta['images'] = $posted_term_meta_image;
            $cat_keys = array_keys( $posted_term_meta );
            if ( !empty($cat_keys) && is_array( $cat_keys ) ) {
                foreach ( $cat_keys as $key ) {
                    if ( isset( $posted_term_meta[$key] ) ) {
                        $term_meta[$key] = $posted_term_meta[$key];
                    }
                }
            }
            //save the option array
            if ( function_exists( 'wcbm_save_cat_banner_data' ) ) {
                wcbm_save_cat_banner_data( $t_id, $term_meta );
            }
        }
    
    }
    
    /**
     * Save WCBM shop page setting
     *
     */
    public function wcbm_save_shop_page_banner_data()
    {
        $shop_page_banner_image_results = filter_input( INPUT_POST, 'shop_page_banner_image_results', FILTER_SANITIZE_STRING );
        $shop_page_banner_image_results = ( !empty($shop_page_banner_image_results) ? $shop_page_banner_image_results : '' );
        $shop_page_banner_link_results = filter_input( INPUT_POST, 'shop_page_banner_link_results', FILTER_SANITIZE_STRING );
        $shop_page_banner_image_size = filter_input( INPUT_POST, 'shop_page_banner_image_size', FILTER_SANITIZE_STRING );
        $shop_page_banner_link_results = ( !empty($shop_page_banner_link_results) ? $shop_page_banner_link_results : '' );
        $shop_page_banner_image_size = ( !empty($shop_page_banner_image_size) ? $shop_page_banner_image_size : '' );
        $shop_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'shop_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $shop_page_banner_enable_or_not_results = ( !empty($shop_page_banner_enable_or_not_results) ? $shop_page_banner_enable_or_not_results : '' );
        $shop_page_banner_select_target_results = filter_input( INPUT_POST, 'shop_page_banner_select_target_results', FILTER_SANITIZE_STRING );
        $shop_page_banner_select_target_results = ( !empty($shop_page_banner_select_target_results) ? $shop_page_banner_select_target_results : '' );
        $shop_page_banner_select_relation_results = filter_input( INPUT_POST, 'shop_page_banner_select_relation_results', FILTER_SANITIZE_STRING );
        $shop_page_banner_select_relation_results = ( !empty($shop_page_banner_select_relation_results) ? $shop_page_banner_select_relation_results : '' );
        $cart_page_banner_image_results = filter_input( INPUT_POST, 'cart_page_banner_image_results', FILTER_SANITIZE_STRING );
        $cart_page_banner_image_results = ( !empty($cart_page_banner_image_results) ? $cart_page_banner_image_results : '' );
        $cart_page_banner_link_results = filter_input( INPUT_POST, 'cart_page_banner_link_results', FILTER_SANITIZE_STRING );
        $cart_page_banner_link_results = ( !empty($cart_page_banner_link_results) ? $cart_page_banner_link_results : '' );
        $cart_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'cart_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $cart_page_banner_enable_or_not_results = ( !empty($cart_page_banner_enable_or_not_results) ? $cart_page_banner_enable_or_not_results : '' );
        $cart_page_banner_select_target_results = filter_input( INPUT_POST, 'cart_page_banner_select_target_results', FILTER_SANITIZE_STRING );
        $cart_page_banner_select_target_results = ( !empty($cart_page_banner_select_target_results) ? $cart_page_banner_select_target_results : '' );
        $cart_page_banner_select_relation_results = filter_input( INPUT_POST, 'cart_page_banner_select_relation_results', FILTER_SANITIZE_STRING );
        $cart_page_banner_select_relation_results = ( !empty($cart_page_banner_select_relation_results) ? $cart_page_banner_select_relation_results : '' );
        $checkout_page_banner_image_results = filter_input( INPUT_POST, 'checkout_page_banner_image_results', FILTER_SANITIZE_STRING );
        $checkout_page_banner_image_results = ( !empty($checkout_page_banner_image_results) ? $checkout_page_banner_image_results : '' );
        $checkout_page_banner_link_results = filter_input( INPUT_POST, 'checkout_page_banner_link_results', FILTER_SANITIZE_STRING );
        $checkout_page_banner_link_results = ( !empty($checkout_page_banner_link_results) ? $checkout_page_banner_link_results : '' );
        $checkout_page_banner_target_results = filter_input( INPUT_POST, 'checkout_page_banner_target_results', FILTER_SANITIZE_STRING );
        $checkout_page_banner_target_results = ( !empty($checkout_page_banner_target_results) ? $checkout_page_banner_target_results : '' );
        $checkout_page_banner_relation_results = filter_input( INPUT_POST, 'checkout_page_banner_relation_results', FILTER_SANITIZE_STRING );
        $checkout_page_banner_relation_results = ( !empty($checkout_page_banner_relation_results) ? $checkout_page_banner_relation_results : '' );
        $checkout_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'checkout_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $checkout_page_banner_enable_or_not_results = ( !empty($checkout_page_banner_enable_or_not_results) ? $checkout_page_banner_enable_or_not_results : '' );
        $thankyou_page_banner_image_results = filter_input( INPUT_POST, 'thankyou_page_banner_image_results', FILTER_SANITIZE_STRING );
        $thankyou_page_banner_image_results = ( !empty($thankyou_page_banner_image_results) ? $thankyou_page_banner_image_results : '' );
        $thankyou_page_banner_target_results = filter_input( INPUT_POST, 'thankyou_page_banner_target_results', FILTER_SANITIZE_STRING );
        $thankyou_page_banner_target_results = ( !empty($thankyou_page_banner_target_results) ? $thankyou_page_banner_target_results : '' );
        $thankyou_page_banner_relation_results = filter_input( INPUT_POST, 'thankyou_page_banner_relation_results', FILTER_SANITIZE_STRING );
        $thankyou_page_banner_relation_results = ( !empty($thankyou_page_banner_relation_results) ? $thankyou_page_banner_relation_results : '' );
        $thankyou_page_banner_link_results = filter_input( INPUT_POST, 'thankyou_page_banner_link_results', FILTER_SANITIZE_STRING );
        $thankyou_page_banner_link_results = ( !empty($thankyou_page_banner_link_results) ? $thankyou_page_banner_link_results : '' );
        $thankyou_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'thankyou_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $thankyou_page_banner_enable_or_not_results = ( !empty($thankyou_page_banner_enable_or_not_results) ? $thankyou_page_banner_enable_or_not_results : '' );
        $banner_detail_page_banner_image_results = filter_input( INPUT_POST, 'banner_detail_page_banner_image_results', FILTER_SANITIZE_STRING );
        $banner_detail_page_banner_image_results = ( !empty($banner_detail_page_banner_image_results) ? $banner_detail_page_banner_image_results : '' );
        $banner_detail_page_banner_link_results = filter_input( INPUT_POST, 'banner_detail_page_banner_link_results', FILTER_SANITIZE_STRING );
        $banner_detail_page_banner_link_results = ( !empty($banner_detail_page_banner_link_results) ? $banner_detail_page_banner_link_results : '' );
        $banner_detail_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'banner_detail_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $banner_detail_page_banner_enable_or_not_results = ( !empty($banner_detail_page_banner_enable_or_not_results) ? $banner_detail_page_banner_enable_or_not_results : '' );
        $banner_detail_page_section_banner_enable_or_not_results = filter_input( INPUT_POST, 'banner_detail_page_section_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $banner_detail_page_section_banner_enable_or_not_results = ( !empty($banner_detail_page_section_banner_enable_or_not_results) ? $banner_detail_page_section_banner_enable_or_not_results : '' );
        $other_page_banner_image_results = filter_input( INPUT_POST, 'other_page_banner_image_results', FILTER_SANITIZE_STRING );
        $other_page_banner_image_results = ( !empty($other_page_banner_image_results) ? $other_page_banner_image_results : '' );
        $other_page_banner_link_results = filter_input( INPUT_POST, 'other_page_banner_link_results', FILTER_SANITIZE_STRING );
        $other_page_banner_image_size = filter_input( INPUT_POST, 'other_page_banner_image_size', FILTER_SANITIZE_STRING );
        $other_page_banner_link_results = ( !empty($other_page_banner_link_results) ? $other_page_banner_link_results : '' );
        $other_page_banner_image_size = ( !empty($other_page_banner_image_size) ? $other_page_banner_image_size : '' );
        $other_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'other_page_banner_enable_or_not_results', FILTER_SANITIZE_STRING );
        $other_page_banner_enable_or_not_results = ( !empty($other_page_banner_enable_or_not_results) ? $other_page_banner_enable_or_not_results : '' );
        $other_page_banner_select_target_results = filter_input( INPUT_POST, 'other_page_banner_select_target_results', FILTER_SANITIZE_STRING );
        $other_page_banner_select_target_results = ( !empty($other_page_banner_select_target_results) ? $other_page_banner_select_target_results : '' );
        $other_page_banner_select_relation_results = filter_input( INPUT_POST, 'other_page_banner_select_relation_results', FILTER_SANITIZE_STRING );
        $other_page_banner_select_relation_results = ( !empty($other_page_banner_select_relation_results) ? $other_page_banner_select_relation_results : '' );
        $product_slider_settings_on_sale_results = filter_input( INPUT_POST, 'product_slider_settings_on_sale_results', FILTER_SANITIZE_STRING );
        $product_slider_settings_on_sale_results = ( !empty($product_slider_settings_on_sale_results) ? $product_slider_settings_on_sale_results : '' );
        $product_slider_price_range_results = filter_input( INPUT_POST, 'product_slider_price_range_results', FILTER_SANITIZE_STRING );
        $product_slider_price_range_results = ( !empty($product_slider_price_range_results) ? $product_slider_price_range_results : '' );
        $product_cat_slider_settings_by_title_results = filter_input( INPUT_POST, 'product_cat_slider_settings_by_title_results', FILTER_SANITIZE_STRING );
        $product_cat_slider_settings_by_title_results = ( !empty($product_cat_slider_settings_by_title_results) ? $product_cat_slider_settings_by_title_results : '' );
        $product_cat_slider_settings_feature_img_results = filter_input( INPUT_POST, 'product_cat_slider_settings_feature_img_results', FILTER_SANITIZE_STRING );
        $product_cat_slider_settings_feature_img_results = ( !empty($product_cat_slider_settings_feature_img_results) ? $product_cat_slider_settings_feature_img_results : '' );
        $product_cat_slider_settings_by_desc_results = filter_input( INPUT_POST, 'product_cat_slider_settings_by_desc_results', FILTER_SANITIZE_STRING );
        $product_cat_slider_settings_by_desc_results = ( !empty($product_cat_slider_settings_by_desc_results) ? $product_cat_slider_settings_by_desc_results : '' );
        $shop_page_data_stored_array = array(
            'shop_page_banner_image_src'       => $shop_page_banner_image_results,
            'shop_page_banner_link_src'        => $shop_page_banner_link_results,
            'shop_page_banner_image_size'      => $shop_page_banner_image_size,
            'shop_page_banner_enable_status'   => $shop_page_banner_enable_or_not_results,
            'shop_page_banner_select_target'   => $shop_page_banner_select_target_results,
            'shop_page_banner_select_relation' => $shop_page_banner_select_relation_results,
        );
        $cart_page_data_stored_array = array(
            'cart_page_banner_image_src'       => $cart_page_banner_image_results,
            'cart_page_banner_link_src'        => $cart_page_banner_link_results,
            'cart_page_banner_enable_status'   => $cart_page_banner_enable_or_not_results,
            'cart_page_banner_select_target'   => $cart_page_banner_select_target_results,
            'cart_page_banner_select_relation' => $cart_page_banner_select_relation_results,
        );
        $checkout_page_data_stored_array = array(
            'checkout_page_banner_image_src'     => $checkout_page_banner_image_results,
            'checkout_page_banner_link_src'      => $checkout_page_banner_link_results,
            'checkout_page_banner_enable_status' => $checkout_page_banner_enable_or_not_results,
            'checkout_page_banner_target'        => $checkout_page_banner_target_results,
            'checkout_page_banner_relation'      => $checkout_page_banner_relation_results,
        );
        $thankyou_page_data_stored_array = array(
            'thankyou_page_banner_image_src'     => $thankyou_page_banner_image_results,
            'thankyou_page_banner_link_src'      => $thankyou_page_banner_link_results,
            'thankyou_page_banner_enable_status' => $thankyou_page_banner_enable_or_not_results,
            'thankyou_page_banner_target'        => $thankyou_page_banner_target_results,
            'thankyou_page_banner_relation'      => $thankyou_page_banner_relation_results,
        );
        $banner_detail_page_data_stored_array = array(
            'banner_detail_page_banner_image_src'             => $banner_detail_page_banner_image_results,
            'banner_detail_page_banner_link_src'              => $banner_detail_page_banner_link_results,
            'banner_detail_page_banner_enable_status'         => $banner_detail_page_banner_enable_or_not_results,
            'banner_detail_page_section_banner_enable_status' => $banner_detail_page_section_banner_enable_or_not_results,
            'banner_detail_page_banner_target'                => $banner_detail_page_banner_target_dots_results,
            'banner_detail_page_banner_relation'              => $banner_detail_page_banner_relation_dots_results,
        );
        $other_page_data_stored_array = array(
            'other_page_banner_image_src'       => $other_page_banner_image_results,
            'other_page_banner_link_src'        => $other_page_banner_link_results,
            'other_page_banner_image_size'      => $other_page_banner_image_size,
            'other_page_banner_enable_status'   => $other_page_banner_enable_or_not_results,
            'other_page_banner_select_target'   => $other_page_banner_select_target_results,
            'other_page_banner_select_relation' => $other_page_banner_select_relation_results,
        );
        $wbm_prod_slider_data_stored_array = array(
            'product_slider_settings_on_sale'         => $product_slider_settings_on_sale_results,
            'product_slider_settings_featured_prod'   => $product_slider_settings_featured_prod_results,
            'product_slider_price_range'              => $product_slider_price_range_results,
            'product_cat_slider_settings_cat'         => $product_cat_slider_settings_cat_results,
            'product_cat_slider_settings_by_title'    => $product_cat_slider_settings_by_title_results,
            'product_cat_slider_settings_feature_img' => $product_cat_slider_settings_feature_img_results,
            'product_cat_slider_settings_by_desc'     => $product_cat_slider_settings_by_desc_results,
        );
        
        if ( function_exists( 'wcbm_save_page_banner_data' ) ) {
            wcbm_save_page_banner_data( 'shop', $shop_page_data_stored_array );
            wcbm_save_page_banner_data( 'cart', $cart_page_data_stored_array );
            wcbm_save_page_banner_data( 'checkout', $checkout_page_data_stored_array );
            wcbm_save_page_banner_data( 'thankyou', $thankyou_page_data_stored_array );
            wcbm_save_page_banner_data( 'banner_detail', $banner_detail_page_data_stored_array );
            wcbm_save_page_banner_data( 'other_pages', $other_page_data_stored_array );
            wcbm_save_page_banner_data( 'product_sliders', $wbm_prod_slider_data_stored_array );
        }
        
        die;
    }
    
    /**
     * Show Category Banner In Category Page
     *
     */
    public function wcbm_show_category_banner()
    {
        // Make sure this is a product category page
        $this::display_category_banner_html();
    }
    
    /**
     * Show Category Banner In Category Page
     *
     */
    public function wcbm_show_product_banner()
    {
        // Make sure this is a product category page
        $this::display_product_banner_html();
    }
    
    /**
     * Function For display the banner image in shop page
     *
     *
     */
    public function wcbm_show_shop_page_banner()
    {
        $wbm_shop_page_stored_results_serialize_banner_src = '';
        $wbm_shop_page_stored_results_serialize_banner_link = '';
        $wbm_shop_page_stored_results_serialize_banner_enable_status = '';
        $alt_tag_value = '';
        $wbm_shop_page_stored_results_serialize_banner_target = '';
        $wbm_shop_page_stored_results_serialize_banner_relation = '';
        $wbm_shop_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'shop' ) : '' );
        
        if ( isset( $wbm_shop_page_stored_results ) && !empty($wbm_shop_page_stored_results) ) {
            $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
            
            if ( !empty($wbm_shop_page_stored_results_serialize) ) {
                $wbm_shop_page_stored_results_serialize_banner_src = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_src'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_link = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_link_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_link_src'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_target = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_target']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_target'] : '' );
                $wbm_shop_page_stored_results_serialize_banner_relation = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation'] : '' );
            }
        
        }
        
        if ( is_shop() ) {
            
            if ( !empty($wbm_shop_page_stored_results_serialize_banner_enable_status) && $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
                $shop_page_select_size = ( !empty($wbm_shop_page_stored_results['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results['shop_page_banner_image_size'] : '' );
                $shop_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $shop_page_select_size ) : '' );
                ?>
					<div class="wbm_banner_image <?php 
                echo  esc_attr( $shop_page_select_size_class ) ;
                ?>">
						<?php 
                
                if ( '' === $wbm_shop_page_stored_results_serialize_banner_link ) {
                    $alt_tag_css_shop_page_fornt = '';
                } else {
                    
                    if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_shop_page_stored_results_serialize_banner_link ) ) {
                        $image_link = "http://" . $wbm_shop_page_stored_results_serialize_banner_link;
                    } else {
                        $image_link = $wbm_shop_page_stored_results_serialize_banner_link;
                    }
                    
                    
                    if ( 'self' === $wbm_shop_page_stored_results_serialize_banner_target ) {
                        $target_attr = "_self";
                    } else {
                        $target_attr = "_blank";
                    }
                    
                    
                    if ( 'nofollow' === $wbm_shop_page_stored_results_serialize_banner_relation ) {
                        $rel_attr = "noopener noreferrer nofollow";
                    } else {
                        $rel_attr = "noopener noreferrer  follow";
                    }
                    
                    $alt_tag_css_shop_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                }
                
                ?>
						<a <?php 
                echo  wp_kses_post( $alt_tag_css_shop_page_fornt ) ;
                ?>>
							<p>
								<img src="<?php 
                echo  esc_url( $wbm_shop_page_stored_results_serialize_banner_src ) ;
                ?>"
									class="category_banner_image" title="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>"
									alt="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>">
							</p>
						</a>
					</div>
				<?php 
            }
        
        }
    }
    
    /**
     * Function For display the banner image in website other pages
     *
     *
     */
    public static function wcbm_show_other_page_banner()
    {
        $wbm_other_page_stored_results_serialize_banner_enable_status = '';
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_other_page_stored_results = wcbm_get_page_banner_data( 'other_pages' );
        }
        
        if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
            $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
            $wbm_other_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_enable_status']) ? $wbm_other_page_stored_results_serialize['other_page_banner_enable_status'] : '' );
        }
        
        
        if ( is_page() && 'on' !== $wbm_other_page_stored_results_serialize_banner_enable_status && isset( $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) && 'on' === $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) {
            $page_id = get_the_id();
            $heading_text = get_the_title( $page_id );
            $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $page_id ) : '' );
            $alt_tag_value = '';
            $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
            $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
            $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
            $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
            $cat_page_select_target = ( !empty($term_options['cat_page_select_target']) ? $term_options['cat_page_select_target'] : 'blank' );
            $cat_page_select_relation = ( !empty($term_options['cat_page_select_relation']) ? $term_options['cat_page_select_relation'] : 'follow' );
            $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
            
            if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                ?>
				<div class="wbm_banner_image <?php 
                echo  esc_attr( $cat_page_select_size_class ) ;
                ?>">
					<?php 
                if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                    $url = $term_options['banner_url_id'];
                }
                // Exit if the image url doesn't exist
                if ( !isset( $url ) or false === $url ) {
                    return;
                }
                // Get the banner link if it exists
                if ( '' !== $term_options['banner_link'] ) {
                    $link = $term_options['banner_link'];
                }
                // Print Output
                if ( isset( $link ) ) {
                    
                    if ( '' === $link ) {
                        echo  "<a>" ;
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                            $image_link = "http://" . $link;
                        } else {
                            $image_link = $link;
                        }
                        
                        
                        if ( 'self' === $cat_page_select_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $cat_page_select_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer  follow";
                        }
                        
                        echo  '<a href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                    }
                
                }
                if ( is_numeric( $url ) ) {
                    $url = wp_get_attachment_url( $url );
                }
                if ( false !== $url ) {
                    echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                }
                if ( isset( $link ) ) {
                    echo  "</a>" ;
                }
                
                if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                    $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                    $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                    echo  '<div class="banner_default_title_row">' ;
                    echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                    echo  esc_html( $heading_text, 'woo-banner-management' ) ;
                    echo  '</h2>' ;
                    echo  '</div>' ;
                    $no_main_title = '';
                } else {
                    $no_main_title = 'no_main_title';
                }
                
                
                if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                    $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                    $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                    echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                    if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                        echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'woo-banner-management' ) . '</p>' ;
                    }
                    if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                        echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'woo-banner-management' ) . '</a>' ;
                    }
                    echo  '</div>' ;
                }
                
                echo  '</div>' ;
            }
        
        } elseif ( is_page() && (!is_shop() && !is_checkout() && !is_cart()) ) {
            
            if ( !empty($wbm_other_page_stored_results_serialize_banner_enable_status) && $wbm_other_page_stored_results_serialize_banner_enable_status === 'on' ) {
                $wbm_other_page_stored_results_serialize_banner_src = '';
                $wbm_other_page_stored_results_serialize_banner_link = '';
                $alt_tag_value = '';
                $wbm_other_page_stored_results_serialize_banner_target = '';
                $wbm_other_page_stored_results_serialize_banner_relation = '';
                $wbm_other_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'other_pages' ) : '' );
                if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                    $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
                }
                $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
                $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
                $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
                
                if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
                    $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
                    
                    if ( !empty($wbm_other_page_stored_results_serialize) ) {
                        $wbm_other_page_stored_results_serialize_banner_src = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_image_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_image_src'] : '' );
                        $wbm_other_page_stored_results_serialize_banner_link = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_link_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_link_src'] : '' );
                        $wbm_other_page_stored_results_serialize_banner_target = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_target']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_target'] : '' );
                        $wbm_other_page_stored_results_serialize_banner_relation = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_relation']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_relation'] : '' );
                    }
                
                }
                
                ?>
					<div class="wbm_banner_image <?php 
                echo  esc_attr( $banner_global_select_size_class ) ;
                ?>">
						<?php 
                
                if ( '' === $wbm_other_page_stored_results_serialize_banner_link ) {
                    $alt_tag_css_other_page_fornt = '';
                } else {
                    
                    if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_other_page_stored_results_serialize_banner_link ) ) {
                        $image_link = "http://" . $wbm_other_page_stored_results_serialize_banner_link;
                    } else {
                        $image_link = $wbm_other_page_stored_results_serialize_banner_link;
                    }
                    
                    
                    if ( 'self' === $wbm_other_page_stored_results_serialize_banner_target ) {
                        $target_attr = "_self";
                    } else {
                        $target_attr = "_blank";
                    }
                    
                    
                    if ( 'nofollow' === $wbm_other_page_stored_results_serialize_banner_relation ) {
                        $rel_attr = "noopener noreferrer nofollow";
                    } else {
                        $rel_attr = "noopener noreferrer  follow";
                    }
                    
                    $alt_tag_css_other_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                }
                
                ?>
						<a <?php 
                echo  wp_kses_post( $alt_tag_css_other_page_fornt ) ;
                ?>>
							<p>
								<img src="<?php 
                echo  esc_url( $wbm_other_page_stored_results_serialize_banner_src ) ;
                ?>"
									class="category_banner_image" title="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>"
									alt="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>">
							</p>
						</a>
					</div>
				<?php 
            }
        
        }
    
    }
    
    /**
     * Function For display banner image in cart page
     *
     */
    public function wcbm_show_cart_page_banner()
    {
        $wbm_cart_page_stored_results_serialize_banner_src = '';
        $wbm_cart_page_stored_results_serialize_banner_link = '';
        $wbm_cart_page_stored_results_serialize_banner_enable_status = '';
        $alt_tag_value = '';
        $wbm_cart_page_stored_results_serialize_banner_target = '';
        $wbm_cart_page_stored_results_serialize_banner_relation = '';
        $wbm_cart_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'cart' ) : '' );
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
        }
        $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
        $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
        $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
        
        if ( isset( $wbm_cart_page_stored_results ) && !empty($wbm_cart_page_stored_results) ) {
            $wbm_cart_page_stored_results_serialize = $wbm_cart_page_stored_results;
            
            if ( !empty($wbm_cart_page_stored_results_serialize) ) {
                $wbm_cart_page_stored_results_serialize_banner_src = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_image_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_image_src'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_link = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_link_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_link_src'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_target = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_target']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_target'] : '' );
                $wbm_cart_page_stored_results_serialize_banner_relation = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation'] : '' );
            }
        
        }
        
        
        if ( !empty($wbm_cart_page_stored_results_serialize_banner_enable_status) && $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
            ?>
					<div class="wbm_banner_image <?php 
            echo  esc_attr( $banner_global_select_size_class ) ;
            ?>">
						<?php 
            
            if ( $wbm_cart_page_stored_results_serialize_banner_link === '' ) {
                $alt_tag_css_cart_page_fornt = '';
            } else {
                
                if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_cart_page_stored_results_serialize_banner_link ) ) {
                    $image_link = "http://" . $wbm_cart_page_stored_results_serialize_banner_link;
                } else {
                    $image_link = $wbm_cart_page_stored_results_serialize_banner_link;
                }
                
                
                if ( 'self' === $wbm_cart_page_stored_results_serialize_banner_target ) {
                    $target_attr = "_self";
                } else {
                    $target_attr = "_blank";
                }
                
                
                if ( 'nofollow' === $wbm_cart_page_stored_results_serialize_banner_relation ) {
                    $rel_attr = "noopener noreferrer nofollow";
                } else {
                    $rel_attr = "noopener noreferrer  follow";
                }
                
                $alt_tag_css_cart_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">';
            }
            
            ?>
					<a <?php 
            echo  wp_kses_post( $alt_tag_css_cart_page_fornt ) ;
            ?>>
						<p>
							<img src="<?php 
            echo  esc_url( $wbm_cart_page_stored_results_serialize_banner_src ) ;
            ?>"
								class="category_banner_image" title="<?php 
            echo  esc_attr( $alt_tag_value ) ;
            ?> <?php 
            echo  esc_attr( $banner_global_select_size_class ) ;
            ?>"
								alt="<?php 
            echo  esc_attr( $alt_tag_value ) ;
            ?>">
						</p>
					</a>
				</div>
				<?php 
        }
    
    }
    
    /**
     * Function For display banner image in check out page
     *
     */
    public function wcbm_show_checkout_page_banner()
    {
        $wbm_checkout_page_stored_results_serialize_banner_src = '';
        $wbm_checkout_page_stored_results_serialize_banner_link = '';
        $wbm_checkout_page_stored_results_serialize_banner_enable_status = '';
        $alt_tag_value = '';
        $wbm_checkout_page_stored_results_serialize_banner_target = '';
        $wbm_checkout_page_stored_results_serialize_banner_relation = '';
        $wbm_checkout_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'checkout' ) : '' );
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
        }
        $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
        $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
        $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
        
        if ( isset( $wbm_checkout_page_stored_results ) && !empty($wbm_checkout_page_stored_results) ) {
            $wbm_checkout_page_stored_results_serialize = $wbm_checkout_page_stored_results;
            
            if ( !empty($wbm_checkout_page_stored_results_serialize) ) {
                $wbm_checkout_page_stored_results_serialize_banner_src = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_link = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_target = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_target']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_target'] : '' );
                $wbm_checkout_page_stored_results_serialize_banner_relation = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation'] : '' );
            }
        
        }
        
        
        if ( !empty($wbm_checkout_page_stored_results_serialize_banner_enable_status) && $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
            ?>
				<div class="wbm_banner_image <?php 
            echo  esc_attr( $banner_global_select_size_class ) ;
            ?>">
					<?php 
            
            if ( $wbm_checkout_page_stored_results_serialize_banner_link === '' ) {
                $alt_tag_css_checkout_page_fornt = '';
            } else {
                
                if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_checkout_page_stored_results_serialize_banner_link ) ) {
                    $image_link = "http://" . $wbm_checkout_page_stored_results_serialize_banner_link;
                } else {
                    $image_link = $wbm_checkout_page_stored_results_serialize_banner_link;
                }
                
                
                if ( 'self' === $wbm_checkout_page_stored_results_serialize_banner_target ) {
                    $target_attr = "_self";
                } else {
                    $target_attr = "_blank";
                }
                
                
                if ( 'nofollow' === $wbm_checkout_page_stored_results_serialize_banner_relation ) {
                    $rel_attr = "noopener noreferrer nofollow";
                } else {
                    $rel_attr = "noopener noreferrer  follow";
                }
                
                $alt_tag_css_checkout_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
            }
            
            ?>
					<a <?php 
            echo  wp_kses_post( $alt_tag_css_checkout_page_fornt ) ;
            ?>>
						<p>
							<img src="<?php 
            echo  esc_url( $wbm_checkout_page_stored_results_serialize_banner_src ) ;
            ?>"
								class="category_banner_image" title="<?php 
            echo  esc_attr( $alt_tag_value ) ;
            ?>"
								alt="<?php 
            echo  esc_attr( $alt_tag_value ) ;
            ?>">
						</p>
					</a>
				</div>
				<?php 
        }
    
    }
    
    /**
     * Save For Later welcome page
     *
     */
    public function welcome_screen_do_activation_redirect()
    {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_activation_redirect_banner_management' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_activation_redirect_banner_management' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        if ( is_network_admin() || isset( $activate_multi ) && !empty($activate_multi) ) {
            //return;
        }
        wp_safe_redirect( add_query_arg( array(
            'page' => 'banner-setting&tab=wcbm-plugin-get-started',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /** Display category page banner */
    public static function display_category_banner_html()
    {
        $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
        $banner_detail_page_section_banner_enable_status = '';
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
        }
        
        if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
            $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
            
            if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                $banner_detail_page_section_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
            }
        
        }
        
        
        if ( is_product_category() || is_product_tag() ) {
            $category = get_queried_object();
            $cat_id = $category->term_id;
            $heading_text = $category->name;
            $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $cat_id ) : '' );
            $alt_tag_value = '';
            $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
            $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
            $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
            $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
            $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
            
            if ( isset( $term_options['cat_page_select_target'] ) && '' !== $term_options['cat_page_select_target'] ) {
                $wbm_shop_page_stored_results_serialize_banner_target = $term_options['cat_page_select_target'];
            } else {
                $wbm_shop_page_stored_results_serialize_banner_target = 'blank';
            }
            
            
            if ( isset( $term_options['cat_page_select_relation'] ) && '' !== $term_options['cat_page_select_relation'] ) {
                $wbm_shop_page_stored_results_serialize_banner_relation = $term_options['cat_page_select_relation'];
            } else {
                $wbm_shop_page_stored_results_serialize_banner_relation = 'follow';
            }
            
            
            if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                ?>
				<div class="wbm_banner_image <?php 
                echo  esc_attr( $cat_page_select_size_class ) ;
                ?>">
					<?php 
                if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                    $url = $term_options['banner_url_id'];
                }
                // Exit if the image url doesn't exist
                if ( !isset( $url ) or false === $url ) {
                    return;
                }
                // Get the banner link if it exists
                if ( '' !== $term_options['banner_link'] ) {
                    $link = $term_options['banner_link'];
                }
                // Print Output
                if ( isset( $link ) ) {
                    
                    if ( '' === $link ) {
                        echo  "<a>" ;
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                            $image_link = "http://" . $link;
                        } else {
                            $image_link = $link;
                        }
                        
                        
                        if ( 'self' === $wbm_shop_page_stored_results_serialize_banner_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $wbm_shop_page_stored_results_serialize_banner_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer  follow";
                        }
                        
                        echo  '<a class="has-link" href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                    }
                
                }
                if ( is_numeric( $url ) ) {
                    $url = wp_get_attachment_url( $url );
                }
                if ( false !== $url ) {
                    echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                }
                if ( isset( $link ) ) {
                    echo  "</a>" ;
                }
                
                if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                    $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                    $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                    echo  '<div class="banner_default_title_row">' ;
                    echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                    echo  esc_html( $heading_text, 'woo-banner-management' ) ;
                    echo  '</h2>' ;
                    echo  '</div>' ;
                    $no_main_title = '';
                } else {
                    $no_main_title = 'no_main_title';
                }
                
                
                if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                    $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                    $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                    echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                    if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                        echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'woo-banner-management' ) . '</p>' ;
                    }
                    if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                        echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'woo-banner-management' ) . '</a>' ;
                    }
                    echo  '</div>' ;
                }
                
                echo  '</div>' ;
            }
        
        }
    
    }
    
    /** Display category page banner */
    public static function display_product_banner_html()
    {
        $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
        $banner_detail_page_section_banner_enable_status = '';
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
        }
        
        if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
            $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
            
            if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                $banner_detail_page_section_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
            }
        
        }
        
        
        if ( is_product() && 'on' !== $wbm_banner_detail_page_stored_results_serialize_banner_enable_status && 'on' === $banner_detail_page_section_banner_enable_status ) {
            $product_id = get_the_id();
            $heading_text = get_the_title( $product_id );
            $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $product_id ) : '' );
            $alt_tag_value = '';
            $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
            $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
            $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
            $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
            $cat_page_select_target = ( !empty($term_options['cat_page_select_target']) ? $term_options['cat_page_select_target'] : 'blank' );
            $cat_page_select_relation = ( !empty($term_options['cat_page_select_relation']) ? $term_options['cat_page_select_relation'] : 'follow' );
            $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
            
            if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                ?>
				<div class="wbm_banner_image <?php 
                echo  esc_attr( $cat_page_select_size_class ) ;
                ?>">
					<?php 
                if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                    $url = $term_options['banner_url_id'];
                }
                // Exit if the image url doesn't exist
                if ( !isset( $url ) or false === $url ) {
                    return;
                }
                // Get the banner link if it exists
                if ( '' !== $term_options['banner_link'] ) {
                    $link = $term_options['banner_link'];
                }
                // Print Output
                if ( isset( $link ) ) {
                    
                    if ( '' === $link ) {
                        echo  "<a>" ;
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                            $image_link = "http://" . $link;
                        } else {
                            $image_link = $link;
                        }
                        
                        
                        if ( 'self' === $cat_page_select_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $cat_page_select_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer  follow";
                        }
                        
                        echo  '<a href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                    }
                
                }
                if ( is_numeric( $url ) ) {
                    $url = wp_get_attachment_url( $url );
                }
                if ( false !== $url ) {
                    echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                }
                if ( isset( $link ) ) {
                    echo  "</a>" ;
                }
                
                if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                    $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                    $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                    echo  '<div class="banner_default_title_row">' ;
                    echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                    echo  esc_html( $heading_text, 'woo-banner-management' ) ;
                    echo  '</h2>' ;
                    echo  '</div>' ;
                    $no_main_title = '';
                } else {
                    $no_main_title = 'no_main_title';
                }
                
                
                if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                    $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                    $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                    echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                    if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                        echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'woo-banner-management' ) . '</p>' ;
                    }
                    if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                        echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'woo-banner-management' ) . '</a>' ;
                    }
                    echo  '</div>' ;
                }
                
                echo  '</div>' ;
            }
        
        } else {
            
            if ( is_product() && 'on' === $wbm_banner_detail_page_stored_results_serialize_banner_enable_status ) {
                $wbm_banner_detail_page_stored_results_serialize_benner_src = '';
                $wbm_banner_detail_page_stored_results_serialize_benner_link = '';
                $wbm_banner_detail_page_stored_results_serialize_benner_enable_status = '';
                $alt_tag_value = '';
                $wbm_banner_detail_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'banner_detail' ) : '' );
                if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                    $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
                }
                $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
                $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
                $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
                
                if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
                    $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
                    
                    if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                        $wbm_banner_detail_page_stored_results_serialize_benner_src = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src'] : '' );
                        $wbm_banner_detail_page_stored_results_serialize_benner_link = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src'] : '' );
                        $wbm_banner_detail_page_stored_results_serialize_benner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                        $wbm_banner_global_page_stored_results_serialize_banner_target = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target'] : '' );
                        $wbm_banner_global_page_stored_results_serialize_banner_relation = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation'] : '' );
                    }
                
                }
                
                
                if ( !empty($wbm_banner_detail_page_stored_results_serialize_benner_enable_status) && $wbm_banner_detail_page_stored_results_serialize_benner_enable_status === 'on' ) {
                    ?>
					<div class="wbm_banner_image">
						<?php 
                    
                    if ( '' === $wbm_banner_detail_page_stored_results_serialize_benner_link ) {
                        $alt_tag_css_banner_detail_page_front = 'class="no-link"';
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_banner_detail_page_stored_results_serialize_benner_link ) ) {
                            $image_link = "http://" . $wbm_banner_detail_page_stored_results_serialize_benner_link;
                        } else {
                            $image_link = $wbm_banner_detail_page_stored_results_serialize_benner_link;
                        }
                        
                        
                        if ( 'self' === $wbm_banner_global_page_stored_results_serialize_banner_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $wbm_banner_global_page_stored_results_serialize_banner_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer follow";
                        }
                        
                        $alt_tag_css_banner_detail_page_front = 'class="has-link" href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                    }
                    
                    ?>
						<a <?php 
                    echo  wp_kses_post( $alt_tag_css_banner_detail_page_front ) ;
                    ?>>
							<p>
								<img src="<?php 
                    echo  esc_url( $wbm_banner_detail_page_stored_results_serialize_benner_src ) ;
                    ?>"
										class="category_banner_image" alt="<?php 
                    echo  esc_attr( $alt_tag_value ) ;
                    ?>">
							</p>
						</a>
					</div>
					<?php 
                }
            
            }
        
        }
    
    }

}
/** Added new shortcode for category list banner page */
add_shortcode( 'display_category_banner', 'display_category_banner_callback' );
/** Added new shortcode for product detail page banner */
add_shortcode( 'display_product_banner', 'display_product_banner_callback' );
/** Added new shortcode for website other page banner */
add_shortcode( 'display_page_banner', 'display_page_banner_callback' );
/** Added new shortcode for product sliders */
add_shortcode( 'wcbm_product_slider', 'wcbm_product_slider_shortcode_callback' );
/** Added new shortcode for category sliders */
add_shortcode( 'wcbm_category_slider', 'wcbm_category_slider_shortcode_callback' );
/**
 * Function For display the product sliders
 *
 *
 */
function wcbm_product_slider_shortcode_callback( $atts )
{
    $atts = shortcode_atts( array(
        'on_sale'             => '',
        'featured_product_id' => '',
        'price_range'         => '',
        'category_id'         => '',
    ), $atts, 'wcbm_product_slider' );
    // setup query
    $args = array(
        'post_type'   => 'product',
        'post_status' => 'publish',
    );
    // query for on sale products
    
    if ( isset( $atts['on_sale'] ) && !empty($atts['on_sale']) && $atts['on_sale'] === 'yes' ) {
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $args['post__in'] = $product_ids_on_sale;
    }
    
    // query for featured products
    
    if ( isset( $atts['featured_product_id'] ) && !empty($atts['featured_product_id']) ) {
        $f_product_arr = explode( ',', $atts['featured_product_id'] );
        $args['post__in'] = $f_product_arr;
    }
    
    // query for on sale and featured product
    
    if ( isset( $atts['on_sale'] ) && !empty($atts['on_sale']) && $atts['on_sale'] === 'yes' && isset( $atts['featured_product_id'] ) && !empty($atts['featured_product_id']) ) {
        $f_product_arr = explode( ',', $atts['featured_product_id'] );
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $product_arr = array_intersect( $product_ids_on_sale, $f_product_arr );
        $args['post__in'] = $product_arr;
    }
    
    // query for product price range
    
    if ( isset( $atts['price_range'] ) && !empty($atts['price_range']) ) {
        $price_range = explode( '-', $atts['price_range'] );
        $current_min_price = $price_range[0];
        $current_max_price = $price_range[1];
        $args['meta_query'] = array( array(
            'key'     => '_price',
            'value'   => array( $current_min_price, $current_max_price ),
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ) );
    }
    
    // query database
    $products = new WP_Query( $args );
    ob_start();
    
    if ( $products->have_posts() ) {
        ?>
	<div class="wbm_banner_random_image">
		<div class="banner-content">
			<div class="wbm-product-bxslider">
				<?php 
        woocommerce_product_loop_start();
        while ( $products->have_posts() ) {
            $products->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        // end of the loop.
        woocommerce_product_loop_end();
        ?>
			</div>
		</div>
	</div>
	<?php 
    }
    
    return ob_get_clean();
}

/**
 * Function For display the product sliders
 *
 *
 */
function wcbm_category_slider_shortcode_callback( $atts )
{
    $atts = shortcode_atts( array(
        'category_id'     => '',
        'cat_title'       => '',
        'cat_feature_img' => '',
        'cat_description' => '',
        'cat_count'       => '',
    ), $atts, 'wcbm_category_slider' );
    // setup query
    $args = array(
        'post_type'  => 'product',
        'hide_empty' => false,
    );
    $terms = get_terms( 'product_cat', $args );
    ob_start();
    ?>
	<div class="wbm_banner_random_image">
		<div class="banner-content">
			<div class="wbm-product-bxslider">
				<ul class="wbm-category-slider">
					<?php 
    // query for selected categories
    
    if ( isset( $atts['category_id'] ) && !empty($atts['category_id']) ) {
        $prod_cat_arrs = explode( ',', $atts['category_id'] );
        foreach ( $prod_cat_arrs as $prod_cat_arr ) {
            foreach ( $terms as $term ) {
                
                if ( intval( $prod_cat_arr ) === $term->term_id ) {
                    ?>
									<li class="term-list">
										<?php 
                    
                    if ( isset( $atts['cat_feature_img'] ) && !empty($atts['cat_feature_img']) && $atts['cat_feature_img'] === 'yes' ) {
                        ?>
											<div class="term-img">
												<?php 
                        $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                        
                        if ( intval( $thumb_id ) !== 0 ) {
                            $term_img = wp_get_attachment_url( $thumb_id );
                            ?>
													<img src="<?php 
                            echo  esc_url( $term_img ) ;
                            ?>" alt="<?php 
                            echo  esc_attr( $term->name ) ;
                            ?>">	
												<?php 
                        }
                        
                        ?>
											</div>
											<?php 
                    }
                    
                    ?>
										<div class="term-content">
											<?php 
                    
                    if ( isset( $atts['cat_title'] ) && !empty($atts['cat_title']) && $atts['cat_title'] === 'yes' ) {
                        $term_link = get_term_link( $term );
                        // If there was an error, continue to the next term.
                        if ( is_wp_error( $term_link ) ) {
                            continue;
                        }
                        ?>
												<h3><a href="<?php 
                        echo  esc_url( $term_link ) ;
                        ?>"><?php 
                        echo  esc_html( $term->name, 'woo-banner-management' ) ;
                        ?></a></h3>
												<?php 
                    }
                    
                    // strip tags to avoid breaking any html
                    $desString = wp_strip_all_tags( $term->description );
                    
                    if ( strlen( $desString ) > 110 ) {
                        // truncate string
                        $desStringCut = substr( $desString, 0, 110 );
                        $endPoint = strrpos( $desStringCut, ' ' );
                        //if the string doesn't contain any space then it will cut without word basis.
                        $desString = ( $endPoint ? substr( $desStringCut, 0, $endPoint ) : substr( $desStringCut, 0 ) );
                        $desString .= '...';
                    }
                    
                    
                    if ( isset( $atts['cat_description'] ) && !empty($atts['cat_description']) && $atts['cat_description'] === 'yes' ) {
                        ?>
												<p><?php 
                        echo  esc_html( $desString, 'woo-banner-management' ) ;
                        ?></p>
												<?php 
                    }
                    
                    ?>
										</div>
									</li>
									<?php 
                }
            
            }
        }
    } elseif ( isset( $terms ) && !empty($terms) ) {
        foreach ( $terms as $term ) {
            ?>
							<li class="term-list">
								<?php 
            
            if ( isset( $atts['cat_feature_img'] ) && !empty($atts['cat_feature_img']) && $atts['cat_feature_img'] === 'yes' ) {
                ?>
									<div class="term-img">
										<?php 
                $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                
                if ( intval( $thumb_id ) !== 0 ) {
                    $term_img = wp_get_attachment_url( $thumb_id );
                    ?>
											<img src="<?php 
                    echo  esc_url( $term_img ) ;
                    ?>" alt="<?php 
                    echo  esc_attr( $term->name ) ;
                    ?>">	
										<?php 
                }
                
                ?>
									</div>
									<?php 
            }
            
            ?>
								<div class="term-content">
									<?php 
            
            if ( isset( $atts['cat_title'] ) && !empty($atts['cat_title']) && $atts['cat_title'] === 'yes' ) {
                $term_link = get_term_link( $term );
                // If there was an error, continue to the next term.
                if ( is_wp_error( $term_link ) ) {
                    continue;
                }
                ?>
										<h3><a href="<?php 
                echo  esc_url( $term_link ) ;
                ?>"><?php 
                echo  esc_html( $term->name, 'woo-banner-management' ) ;
                ?></a></h3>
										<?php 
            }
            
            // strip tags to avoid breaking any html
            $desString = wp_strip_all_tags( $term->description );
            
            if ( strlen( $desString ) > 110 ) {
                // truncate string
                $desStringCut = substr( $desString, 0, 110 );
                $endPoint = strrpos( $desStringCut, ' ' );
                //if the string doesn't contain any space then it will cut without word basis.
                $desString = ( $endPoint ? substr( $desStringCut, 0, $endPoint ) : substr( $desStringCut, 0 ) );
                $desString .= '...';
            }
            
            
            if ( isset( $atts['cat_description'] ) && !empty($atts['cat_description']) && $atts['cat_description'] === 'yes' ) {
                ?>
										<p><?php 
                echo  esc_html( $desString, 'woo-banner-management' ) ;
                ?></p>
										<?php 
            }
            
            ?>
								</div>
							</li>
							<?php 
        }
    }
    
    ?>
				</ul>
			</div>
		</div>
	</div>
	<?php 
    return ob_get_clean();
}

/** Get the category banner html */
function display_category_banner_callback()
{
    ob_start();
    echo  wp_kses_post( woocommerce_category_banner_management_Admin::display_category_banner_html() ) ;
    return ob_get_clean();
}

/** Get the product page banner */
function display_product_banner_callback()
{
    ob_start();
    echo  wp_kses_post( woocommerce_category_banner_management_Admin::display_category_banner_html() ) ;
    return ob_get_clean();
}

/** Get the website other page banner */
function display_page_banner_callback()
{
    ob_start();
    echo  wp_kses_post( woocommerce_category_banner_management_Admin::wcbm_show_other_page_banner() ) ;
    return ob_get_clean();
}
