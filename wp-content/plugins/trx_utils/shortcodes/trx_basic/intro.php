<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_intro_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_intro_theme_setup' );
	function logistic_company_sc_intro_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_intro_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_intro_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('logistic_company_sc_intro')) {	
	function logistic_company_sc_intro($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"align" => "none",
			"image" => "",
			"bg_color" => "",
			"icon" => "",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Read more', 'logistic-company'),
			"link2" => '',
			"link2_caption" => '',
			"url" => "",
			"content_position" => "",
			"content_width" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		
		$width  = logistic_company_prepare_css_value($width);
		$height = logistic_company_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);

		$css .= logistic_company_get_css_dimensions_from_values($width,$height);
		$css .= ($image ? 'background: url('.$image.');' : '');
		$css .= ($bg_color ? 'background-color: '.$bg_color.';' : '');
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_intro_buttons sc_item_buttons">'
							. (!empty($link) 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" size="medium"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) && $style==2 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" size="medium"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
						
		$output = '<div '.(!empty($url) ? 'data-href="'.esc_url($url).'"' : '') 
					. ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_intro' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($content_position && $style==1 ? ' sc_intro_position_' . esc_attr($content_position) : '') 
						. ($style==5 ? ' small_padding' : '') 
						. ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
					. ($css ? ' style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_intro_inner '.($style ? ' sc_intro_style_' . esc_attr($style) : '').'"'.(!empty($content_width) ? ' style="width:'.esc_attr($content_width).';"' : '').'>'
						. (!empty($icon) && $style==5 ? '<div class="sc_intro_icon '.esc_attr($icon).'"></div>' : '')
						. '<div class="sc_intro_content">'
							. (!empty($subtitle) && $style!=4 && $style!=5 ? '<h6 class="sc_intro_subtitle">' . trim(logistic_company_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_intro_title">' . trim(logistic_company_strmacros($title)) . '</h2>' : '')
							. (!empty($description) && $style!=1 ? '<div class="sc_intro_descr">' . trim(logistic_company_strmacros($description)) . '</div>' : '')
							. ($style==2 || $style==3 ? $buttons : '')
						. '</div>'
					. '</div>'
				.'</div>';
	
	
	
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_intro', $atts, $content);
	}
	logistic_company_require_shortcode('trx_intro', 'logistic_company_sc_intro');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_intro_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_intro_reg_shortcodes');
	function logistic_company_sc_intro_reg_shortcodes() {
	
		logistic_company_sc_map("trx_intro", array(
			"title" => esc_html__("Intro", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert Intro block in your page (post)", 'logistic-company') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'logistic-company'),
					"desc" => wp_kses_data( __("Select style to display block", 'logistic-company') ),
					"value" => "1",
					"type" => "checklist",
					"options" => logistic_company_get_list_styles(1, 5)
				),
				"align" => array(
					"title" => esc_html__("Alignment of the intro block", 'logistic-company'),
					"desc" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('float')
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Select the intro image from the library for this section", 'logistic-company') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'logistic-company'),
					"desc" => wp_kses_data( __("Select background color for the intro", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Icon',  'logistic-company'),
					"desc" => wp_kses_data( __("Select icon from Fontello icons set",  'logistic-company') ),
					"dependency" => array(
						'style' => array(5)
					),
					"value" => "",
					"type" => "icons",
					"options" => logistic_company_get_sc_param('icons')
				),
				"content_position" => array(
					"title" => esc_html__('Content position', 'logistic-company'),
					"desc" => wp_kses_data( __("Select content position", 'logistic-company') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "top_left",
					"type" => "checklist",
					"options" => array(
						'top_left' => esc_html__('Top Left', 'logistic-company'),
						'top_right' => esc_html__('Top Right', 'logistic-company'),
						'bottom_right' => esc_html__('Bottom Right', 'logistic-company'),
						'bottom_left' => esc_html__('Bottom Left', 'logistic-company')
					)
				),
				"content_width" => array(
					"title" => esc_html__('Content width', 'logistic-company'),
					"desc" => wp_kses_data( __("Select content width", 'logistic-company') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "100%",
					"type" => "checklist",
					"options" => array(
						'100%' => esc_html__('100%', 'logistic-company'),
						'90%' => esc_html__('90%', 'logistic-company'),
						'80%' => esc_html__('80%', 'logistic-company'),
						'70%' => esc_html__('70%', 'logistic-company'),
						'60%' => esc_html__('60%', 'logistic-company'),
						'50%' => esc_html__('50%', 'logistic-company'),
						'40%' => esc_html__('40%', 'logistic-company'),
						'30%' => esc_html__('30%', 'logistic-company')
					)
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'logistic-company'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'logistic-company') ),
					"divider" => true,
					"dependency" => array(
						'style' => array(1,2,3)
					),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title", 'logistic-company'),
					"desc" => wp_kses_data( __("Title for the block", 'logistic-company') ),
					"value" => "",
					"type" => "textarea"
				),
				"description" => array(
					"title" => esc_html__("Description", 'logistic-company'),
					"desc" => wp_kses_data( __("Short description for the block", 'logistic-company') ),
					"dependency" => array(
						'style' => array(2,3,4,5),
					),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'logistic-company') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'logistic-company'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'logistic-company') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'logistic-company') ),
					"dependency" => array(
						'style' => array(2)
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'logistic-company'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'logistic-company') ),
					"dependency" => array(
						'style' => array(2)
					),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("Link", 'logistic-company'),
					"desc" => wp_kses_data( __("Link of the intro block", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'logistic-company'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('schemes')
				),
				"width" => logistic_company_shortcodes_width(),
				"height" => logistic_company_shortcodes_height(),
				"top" => logistic_company_get_sc_param('top'),
				"bottom" => logistic_company_get_sc_param('bottom'),
				"left" => logistic_company_get_sc_param('left'),
				"right" => logistic_company_get_sc_param('right'),
				"id" => logistic_company_get_sc_param('id'),
				"class" => logistic_company_get_sc_param('class'),
				"animation" => logistic_company_get_sc_param('animation'),
				"css" => logistic_company_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_intro_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_intro_reg_shortcodes_vc');
	function logistic_company_sc_intro_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_intro",
			"name" => esc_html__("Intro", 'logistic-company'),
			"description" => wp_kses_data( __("Insert Intro block", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_intro',
			"class" => "trx_sc_single trx_sc_intro",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style of the block", 'logistic-company'),
					"description" => wp_kses_data( __("Select style to display this block", 'logistic-company') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(logistic_company_get_list_styles(1, 5)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the block", 'logistic-company'),
					"description" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'logistic-company') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(logistic_company_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'logistic-company'),
					"description" => wp_kses_data( __("Select the intro image from the library for this section", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'logistic-company'),
					"description" => wp_kses_data( __("Select background color for the intro", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set", 'logistic-company') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('5')
					),
					"value" => logistic_company_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_position",
					"heading" => esc_html__("Content position", 'logistic-company'),
					"description" => wp_kses_data( __("Select content position", 'logistic-company') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('Top Left', 'logistic-company') => 'top_left',
						esc_html__('Top Right', 'logistic-company') => 'top_right',
						esc_html__('Bottom Right', 'logistic-company') => 'bottom_right',
						esc_html__('Bottom Left', 'logistic-company') => 'bottom_left'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_width",
					"heading" => esc_html__("Content width", 'logistic-company'),
					"description" => wp_kses_data( __("Select content width", 'logistic-company') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('100%', 'logistic-company') => '100%',
						esc_html__('90%', 'logistic-company') => '90%',
						esc_html__('80%', 'logistic-company') => '80%',
						esc_html__('70%', 'logistic-company') => '70%',
						esc_html__('60%', 'logistic-company') => '60%',
						esc_html__('50%', 'logistic-company') => '50%',
						esc_html__('40%', 'logistic-company') => '40%',
						esc_html__('30%', 'logistic-company') => '30%'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'logistic-company'),
					"description" => wp_kses_data( __("Subtitle for the block", 'logistic-company') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'logistic-company'),
					"description" => wp_kses_data( __("Title for the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'logistic-company'),
					"description" => wp_kses_data( __("Description for the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3','4','5')
					),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'logistic-company'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'logistic-company'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'logistic-company'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'logistic-company'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Link", 'logistic-company'),
					"description" => wp_kses_data( __("Link of the intro block", 'logistic-company') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'logistic-company'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_vc_width(),
				logistic_company_vc_height(),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Intro extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>