<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_button_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_button_theme_setup' );
	function logistic_company_sc_button_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_button_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('logistic_company_sc_button')) {	
	function logistic_company_sc_button($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= logistic_company_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (logistic_company_param_is_on($popup)) logistic_company_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (logistic_company_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	logistic_company_require_shortcode('trx_button', 'logistic_company_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_button_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_button_reg_shortcodes');
	function logistic_company_sc_button_reg_shortcodes() {
	
		logistic_company_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'logistic-company'),
			"desc" => wp_kses_data( __("Button with link", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'logistic-company'),
					"desc" => wp_kses_data( __("Button caption", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'logistic-company'),
					"desc" => wp_kses_data( __("Select button's shape", 'logistic-company') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'logistic-company'),
						'round' => esc_html__('Round', 'logistic-company')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'logistic-company'),
					"desc" => wp_kses_data( __("Select button's style", 'logistic-company') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'logistic-company'),
						'border' => esc_html__('Border', 'logistic-company')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'logistic-company'),
					"desc" => wp_kses_data( __("Select button's size", 'logistic-company') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'logistic-company'),
						'medium' => esc_html__('Medium', 'logistic-company'),
						'large' => esc_html__('Large', 'logistic-company')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'logistic-company'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'logistic-company') ),
					"value" => "",
					"type" => "icons",
					"options" => logistic_company_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'logistic-company'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'logistic-company') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'logistic-company'),
					"desc" => wp_kses_data( __("Any color for button's background", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'logistic-company'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'logistic-company') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'logistic-company'),
					"desc" => wp_kses_data( __("URL for link on button click", 'logistic-company') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'logistic-company'),
					"desc" => wp_kses_data( __("Target for link on button click", 'logistic-company') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'logistic-company'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'logistic-company') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'logistic-company'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'logistic-company') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'logistic_company_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_button_reg_shortcodes_vc');
	function logistic_company_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'logistic-company'),
			"description" => wp_kses_data( __("Button with link", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'logistic-company'),
					"description" => wp_kses_data( __("Button caption", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'logistic-company'),
					"description" => wp_kses_data( __("Select button's shape", 'logistic-company') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'logistic-company') => 'square',
						esc_html__('Round', 'logistic-company') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'logistic-company'),
					"description" => wp_kses_data( __("Select button's style", 'logistic-company') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'logistic-company') => 'filled',
						esc_html__('Border', 'logistic-company') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'logistic-company'),
					"description" => wp_kses_data( __("Select button's size", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'logistic-company') => 'small',
						esc_html__('Medium', 'logistic-company') => 'medium',
						esc_html__('Large', 'logistic-company') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'logistic-company') ),
					"class" => "",
					"value" => logistic_company_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'logistic-company'),
					"description" => wp_kses_data( __("Any color for button's caption", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'logistic-company'),
					"description" => wp_kses_data( __("Any color for button's background", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'logistic-company'),
					"description" => wp_kses_data( __("URL for the link on button click", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Link', 'logistic-company'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'logistic-company'),
					"description" => wp_kses_data( __("Target for the link on button click", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Link', 'logistic-company'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'logistic-company'),
					"description" => wp_kses_data( __("Open link target in popup window", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Link', 'logistic-company'),
					"value" => array(esc_html__('Open in popup', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'logistic-company'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Link', 'logistic-company'),
					"value" => "",
					"type" => "textfield"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>