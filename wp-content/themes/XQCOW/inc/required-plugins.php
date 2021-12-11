<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme xqcow
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */

require get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'xqcow_register_required_plugins' );

function xqcow_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => true,
		),

		array(
			'name'      => 'Brazilian Market on WooCommerce',
			'slug'      => 'woocommerce-extra-checkout-fields-for-brazil',
			'required'  => true,
		),

		array(
			'name'      => 'Claudio Sanches – Correios for WooCommerce',
			'slug'      => 'woocommerce-correios',
			'required'  => true,
		),
		
		array(
			'name'      => 'Claudio Sanches – PagSeguro for WooCommerce',
			'slug'      => 'woocommerce-pagseguro',
			'required'  => true,
		),

		array(
			'name'      => 'Ajax Search for WooCommerce',
			'slug'      => 'ajax-search-for-woocommerce',
			'required'  => true,
		),

		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => true,
		),

		array(
			'name'      => 'Smart Slider 3',
			'slug'      => 'smart-slider-3',
			'required'  => true,
		),

		array(
			'name'      => 'WOOF – Products Filter for WooCommerce',
			'slug'      => 'woocommerce-products-filter',
			'required'  => true,
		),

		array(
			'name'      => 'Captcha de Imagem para o Contact Form 7',
			'slug'      => 'contact-form-7-image-captcha',
			'required'  => true,
		),		
	);
	
	$config = array(
		'id'           => 'xqcow',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		'strings'      => array(
			'page_title'                      => __( 'Instale os plugins obrigatórios', 'xqcow' ),
			'menu_title'                      => __( 'Instalar plugins', 'xqcow' ),
			'installing'                      => __( 'Instalando Plugin: %s', 'xqcow' ),
			'updating'                        => __( 'Updating Plugin: %s', 'xqcow' ),
			'oops'                            => __( 'Algo deu errado com o plugin!.', 'xqcow' ),
			'notice_can_install_required'     => _n_noop(				
				'Este tema requer o seguinte plugin: %1$s.',
				'Este tema requer os seguintes plugins: %1$s.',
				'xqcow'
			),		
			'notice_can_activate_required'    => _n_noop(			
				'O seguinte plugin obrigatório está desativado: %1$s.',
				'Os seguintes plugins obrigatórios estão desativados: %1$s.',
				'xqcow'
			),	

			'install_link'                    => _n_noop(
				'Iniciar instalação dos plugin',
				'Iniciar instalação dos plugins',
				'xqcow'
			),
			'update_link' 					  => _n_noop(
				'Iniciar atualização dos plugin',
				'Iniciar atualização dos plugins',
				'xqcow'
			),
			'activate_link'                   => _n_noop(
				'Iniciar ativação dos plugin',
				'Iniciar ativação dos plugins',
				'xqcow'
			),

			'return'                          => __( 'Voltar para o instalador de plugins obrigatórios', 'xqcow' ),
			'plugin_activated'                => __( 'Plugin ativado com sucesso.', 'xqcow' ),
			'activated_successfully'          => __( 'O seguinte plugin foi ativado com sucesso:', 'xqcow' ),
			
			'plugin_already_active'           => __( 'Nenhuma ação foi tomada. O Plugin %1$s já está ativo.', 'xqcow' ),
			
			'plugin_needs_higher_version'     => __( 'Plugin não ativado. Uma versão nova de %s é necessária para esse tema. Por favor atualize o plugin.', 'xqcow' ),
			
			'complete'                        => __( 'Todos os plugins foram instalados e ativados com sucesso. %1$s', 'xqcow' ),
			'dismiss'                         => __( 'Fechar este aviso', 'xqcow' ),
			'notice_cannot_install_activate'  => __( 'Existe um ou mais plugins obrigatórios para instalar, atualizar ou ativar.', 'xqcow' ),
			'contact_admin'                   => __( 'Por favor, contate o administrador do site para ajuda.', 'xqcow' ),
		),
		
	);

	tgmpa( $plugins, $config );
}
