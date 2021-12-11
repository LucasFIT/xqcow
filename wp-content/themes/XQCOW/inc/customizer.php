<?php

/**
 * xqcow - XQCOW Theme Customizer
 *
 * @package xqcow
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function xqcow_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'xqcow_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'xqcow_customize_partial_blogdescription',
			)
		);
	}

	/**
	 * xqcow customizer panel
	 * 
	 * Add all custom settings for this theme here
	 */
	$wp_customize->add_panel(
		'pan_xqcow_panel',
		array(
			'title'			=> 'xqcow - XQCOW',
			'description'	=> __('Personalize a aparência do tema.', 'xqcow'),
		)
	);

	/**
	 * Custom Social Icons
	 */
	$wp_customize->add_section(
		'sec_social_icons',
		array(
			'title'			=> __('Redes Sociais', 'xqcow'),
			'description'	=> __('Adicione suas redes sociais ao site.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);

	$social = array("instagram", "facebook", "pinterest", "youtube", "linkedin");

	for ($i = 0; $i < count($social); $i++) {
		$wp_customize->add_setting(
			'set_' . $social[$i] . '_url',
			array(
				'type'				=> 'theme_mod',
				'default'			=> '',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			'set_' . $social[$i] . '_url',
			array(
				'label'			=> ucfirst($social[$i]),
				'description'	=> __('Link para o seu ' . $social[$i], 'xqcow'),
				'section'		=> 'sec_social_icons',
				'type'			=> 'url'
			)
		);

		$wp_customize->add_setting(
			'set_' . $social[$i] . '_checkbox',
			array(
				'default'				=> '',
				'sanitize_callback'		=> 'xqcow_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'set_' . $social[$i] . '_checkbox',
			array(
				'label'		=> __('Exibir ' . ucfirst($social[$i]), 'xqcow'),
				'section'	=> 'sec_social_icons',
				'type'		=> 'checkbox'
			)
		);
	}

	// WhatsApp - Cell Phone number
	$wp_customize->add_setting(
		'set_whatsapp_number',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'xqcow_sanitize_phone'
		)
	);

	$wp_customize->add_control(
		'set_whatsapp_number',
		array(
			'label'			=> 'WhatsApp',
			'description'	=> __('Número do seu WhatsApp para contato(DDD e número)'),
			'section'		=> 'sec_social_icons',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: 00999996666'),
			),
		)
	);

	$wp_customize->add_setting(
		'set_whatsapp_checkbox',
		array(
			'default'				=> '',
			'sanitize_callback'		=> 'xqcow_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'set_whatsapp_checkbox',
		array(
			'label'		=> __('Exibir WhatsApp', 'xqcow'),
			'section'	=> 'sec_social_icons',
			'type'		=> 'checkbox'
		)
	);

	/**
	 * Copyright
	 */
	$wp_customize->add_section(
		'sec_copyright_fields',
		array(
			'title'			=> __('Direitos autorais', 'xqcow'),
			'description'	=> __('Adicione as informações legais da sua empresa.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	// CNPJ
	$wp_customize->add_setting(
		'set_copyright_cnpj',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'xqcow_sanitize_cnpj'
		)
	);

	$wp_customize->add_control(
		'set_copyright_cnpj',
		array(
			'label'			=> __('CNPJ', 'xqcow'),
			'description'	=> __('CNPJ da empresa sem pontos ou traços', 'xqcow'),
			'section'		=> 'sec_copyright_fields',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: 00.000.000/0000-00'),
			),
		)
	);

	// Inscrição Estadual
	$wp_customize->add_setting(
		'set_copyright_registration',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'xqcow_sanitize_registration'
		)
	);

	$wp_customize->add_control(
		'set_copyright_registration',
		array(
			'label'			=> __('Inscrição Estadual', 'xqcow'),
			'description'	=> __('Inscrição Estadual da empresa sem pontos ou traços', 'xqcow'),
			'section'		=> 'sec_copyright_fields',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: 000.000.000.000'),
			),
		)
	);

	// E-mail
	$wp_customize->add_setting(
		'set_copyright_email',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'sanitize_email'
		)
	);

	$wp_customize->add_control(
		'set_copyright_email',
		array(
			'label'			=> __('E-mail', 'xqcow'),
			'description'	=> __('E-mail para o contato com o cliente', 'xqcow'),
			'section'		=> 'sec_copyright_fields',
			'type'			=> 'email',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: cliente@email.com'),
			),
		)
	);

	// Phone number
	$wp_customize->add_setting(
		'set_copyright_phone',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'xqcow_sanitize_phone'
		)
	);

	$wp_customize->add_control(
		'set_copyright_phone',
		array(
			'label'			=> __('Telefone - (xx) xxxx-xxxx', 'xqcow'),
			'description'	=> __('Informe um telefone para contato', 'xqcow'),
			'section'		=> 'sec_copyright_fields',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: 1199990000'),
			),
		)
	);

	// Address
	$wp_customize->add_setting(
		'set_copyright_address',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'set_copyright_address',
		array(
			'label'			=> __('Endereço Completo', 'xqcow'),
			'description'	=> __('Local fisíco da sua empresa', 'xqcow'),
			'section'		=> 'sec_copyright_fields',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('EX: Rua nome da rua, 170 - Centro - SP, 00000-000'),
			),
		)
	);

	/**
	 * Footer logo
	 */
	$wp_customize->add_section(
		'sec_footer_logo',
		array(
			'title'			=> __('Logo do rodapé', 'xqcow'),
			'description'	=> __('Adicione uma imagem para o logo do rodapé.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	// Logo image
	$wp_customize->add_setting(
		'set_footer_logo',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_footer_logo',
			array(
				'label'		=> __('Carregar logo', 'xqcow'),
				'section'	=> 'sec_footer_logo',
				'settings'	=> 'set_footer_logo',
			)
		)
	);
	
	/**
	* Images Center  
	*/
	$wp_customize->add_section(
		'sec_images_center',
		array(
			'title'			=> __('Imagens Centrais', 'xqcow'),
			'description'	=> __('Adicione imagens.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	
	// Image 01
	$wp_customize->add_setting(
		'set_images_center_image_one',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_center_image_one',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_center',
				'settings'	=> 'set_images_center_image_one',
			)
		)
	);

	// Image 02
	$wp_customize->add_setting(
		'set_images_center_image_two',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_center_image_two',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_center',
				'settings'	=> 'set_images_center_image_two',
			)
		)
	);

	/**
	* Images Footer 
	*/
	$wp_customize->add_section(
		'sec_images_bottom',
		array(
			'title'			=> __('Imagens do Rodapé', 'xqcow'),
			'description'	=> __('Adicione imagens.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	// Image 01
	$wp_customize->add_setting(
		'set_images_bottom_image_one',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_bottom_image_one',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_bottom',
				'settings'	=> 'set_images_bottom_image_one',
			)
		)
	);

	// Image 02
	$wp_customize->add_setting(
		'set_images_bottom_image_two',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_bottom_image_two',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_bottom',
				'settings'	=> 'set_images_bottom_image_two',
			)
		)
	);

	// Image 03
	$wp_customize->add_setting(
		'set_images_bottom_image_three',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_bottom_image_three',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_bottom',
				'settings'	=> 'set_images_bottom_image_three',
			)
		)
	);

	// Image 04
	$wp_customize->add_setting(
		'set_images_bottom_image_four',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'set_images_bottom_image_four',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_images_bottom',
				'settings'	=> 'set_images_bottom_image_four',
			)
		)
	);
	
	/**
	*  Banner Central
	*/
	
	$wp_customize->add_section(
		'sec_banner_central',
		array(
			'title'			=> __('Banner Central', 'xqcow'),
			'description'	=> __('Adicione uma imagem para a seção de contato.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	// Logo image
	$wp_customize->add_setting(
		'sec_banner_central_image',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'sec_banner_central_image',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_banner_central',
				'settings'	=> 'sec_banner_central_image',
			)
		)
	);

	$wp_customize->add_setting('sec_banner_central_color', array(
		'sanitize_callback' => 'themeslug_sanitize_hex_color',
	));

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sec_banner_central_color',
			array(
				'label' => __('Cor do Banner'),
				'description' => __('Selecione a cor para o banner'),
				'section' => 'sec_banner_central', // Add a default or your own section
			)
		)
	);
	$wp_customize->add_setting(
		'sec_banner_central_title',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_central_title',
		array(
			'label'			=> __('Titulo', 'xqcow'),
			'description'	=> __('Titulo do banner', 'xqcow'),
			'section'		=> 'sec_banner_central',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Texto legal para chamar atenção!'),
			),
		)
	);
	
	$wp_customize->add_setting(
		'sec_banner_central_subtitle',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_central_subtitle',
		array(
			'label'			=> __('Subtítulo', 'xqcow'),
			'description'	=> __('Subtítulo do banner', 'xqcow'),
			'section'		=> 'sec_banner_central',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Frase de apoio legal de destaque.'),
			),
		)
	);

	$wp_customize->add_setting(
		'sec_banner_central_btn',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_central_btn',
		array(
			'label'			=> __('Titulo do botão', 'xqcow'),
			'description'	=> __('Titulo do botão', 'xqcow'),
			'section'		=> 'sec_banner_central',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Texto do botão'),
			),
		)
	);


	$wp_customize->add_setting(
		'sec_banner_central_btn_link',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_central_btn_link',
		array(
			'label'			=> __('Link do botão', 'xqcow'),
			'description'	=> __('Link do botão', 'xqcow'),
			'section'		=> 'sec_banner_central',
			'type'			=> 'url',
		)
	);
	
	/**
	*  Footer 2
	*/
	$wp_customize->add_section(
		'sec_banner_footer',
		array(
			'title'			=> __('Banner do Rodapé', 'xqcow'),
			'description'	=> __('Adicione uma imagem para a seção de contato.', 'xqcow'),
			'panel'			=> 'pan_xqcow_panel'
		)
	);
	// Logo image
	$wp_customize->add_setting(
		'sec_banner_footer_image',
		array(
			'type'				=> 'theme_mod',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'sec_banner_footer_image',
			array(
				'label'		=> __('Carregar imagem', 'xqcow'),
				'section'	=> 'sec_banner_footer',
				'settings'	=> 'sec_banner_footer_image',
			)
		)
	);

	$wp_customize->add_setting('sec_banner_footer_color', array(
		'sanitize_callback' => 'themeslug_sanitize_hex_color',
	));

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sec_banner_footer_color',
			array(
				'label' => __('Cor do Banner'),
				'description' => __('Selecione a cor para o banner'),
				'section' => 'sec_banner_footer', // Add a default or your own section
			)
		)
	);
	$wp_customize->add_setting(
		'sec_banner_footer_title',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_footer_title',
		array(
			'label'			=> __('Titulo', 'xqcow'),
			'description'	=> __('Titulo do banner', 'xqcow'),
			'section'		=> 'sec_banner_footer',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Tem alguma dúvida ou sugestão'),
			),
		)
	);

	$wp_customize->add_setting(
		'sec_banner_footer_subtitle',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_footer_subtitle',
		array(
			'label'			=> __('Subtítulo', 'xqcow'),
			'description'	=> __('Subtítulo do Banner', 'xqcow'),
			'section'		=> 'sec_banner_footer',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Nos mande um e-mail, ou mensagem via WhatsApp, assim que possível entraremos em contato.'),
			),
		)
	);

	$wp_customize->add_setting(
		'sec_banner_footer_btn',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_footer_btn',
		array(
			'label'			=> __('Titulo do botão', 'xqcow'),
			'description'	=> __('Titulo do botão', 'xqcow'),
			'section'		=> 'sec_banner_footer',
			'type'			=> 'text',
			'input_attrs' 	=> array(
				'placeholder' => __('Saiba mais'),
			),
		)
	);


	$wp_customize->add_setting(
		'sec_banner_footer_btn_link',
		array(
			'type'				=> 'theme_mod',
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		'sec_banner_footer_btn_link',
		array(
			'label'			=> __('Link do botão', 'xqcow'),
			'description'	=> __('Link do botão', 'xqcow'),
			'section'		=> 'sec_banner_footer',
			'type'			=> 'url',
		)
	);
}

add_action('customize_register', 'xqcow_customize_register');

function themeslug_sanitize_hex_color($hex_color, $setting)
{
	// Sanitize $input as a hex value without the hash prefix.
	$hex_color = sanitize_hex_color($hex_color);

	// If $input is a valid hex value, return it; otherwise, return the default.
	return ($hex_color != null ? $hex_color : $setting->default);
}
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function xqcow_customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function xqcow_customize_partial_blogdescription()
{
	bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function xqcow_customize_preview_js()
{
	wp_enqueue_script('xqcow-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'xqcow_customize_preview_js');

/**
 * Checkbox sanitization function
 * 
 * @param checked verify if the input is checked
 * 
 * @return boolean
 */
function xqcow_sanitize_checkbox($checked)
{
	// Boolean check.
	return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Phone number sanitization function
 * 
 * @param input the user phone number
 * 
 * @return int
 */
function xqcow_sanitize_phone($input)
{
	$input = preg_replace("/[^0-9]/", "", $input);

	if (strlen($input) >= 10 && strlen($input) <= 11) {
		if (is_numeric($input)) {
			return intval($input);
		}
	} elseif (strlen($input) == 0) {
		return 0;
	}
}

/**
 * CNPJ sanitization function
 * 
 * @param value CNPJ number
 * 
 * @return int
 */
function xqcow_sanitize_cnpj($value)
{
	$input = preg_replace("/\D/", '', $value);

	if (strlen($input) === 14) {
		if (is_numeric($input)) {
			return $input;
		}
	}
}

/**
 * Registration sanitization function
 * 
 * @param value Registration number
 * 
 * @return int
 */
function xqcow_sanitize_registration($value)
{
	$input = preg_replace("/\D/", '', $value);

	if (strlen($input) === 12) {
		if (is_numeric($input)) {
			return intval($input);
		}
	}
}

/**
 * Phone number formatter function
 * 
 * @param value the number to be formatted
 * 
 * @return string
 */
function xqcow_phone_formatter($value)
{
	$number = preg_replace("/\D/", '', $value);

	// Phone
	if (strlen($number) === 10) {
		return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $number);
	}

	// Cell phone
	if (strlen($number) === 11) {
		return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $number);
	}
}

/**
 * CNPJ formatter
 * 
 * @param value the cnpj number
 * 
 * @return string
 */
function xqcow_cnpj_formatter($value)
{
	$cnpj = preg_replace("/\D/", '', $value);

	if (strlen($cnpj) === 14) {
		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj);
	}
}

/**
 * Registration formatter
 * 
 * @param value the registration number
 * 
 * @return string
 */
function xqcow_registration_formatter($value)
{
	$registration = preg_replace("/\D/", '', $value);

	if (strlen($registration) === 12) {
		return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{3})/", "\$1.\$2.\$3.\$4", $registration);
	}
}
