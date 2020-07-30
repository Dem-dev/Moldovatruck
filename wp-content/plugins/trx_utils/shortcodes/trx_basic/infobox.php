<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_infobox_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_infobox_theme_setup' );
	function logistic_company_sc_infobox_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_infobox_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('logistic_company_sc_infobox')) {	
	function logistic_company_sc_infobox($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (logistic_company_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !logistic_company_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	logistic_company_require_shortcode('trx_infobox', 'logistic_company_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_infobox_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_infobox_reg_shortcodes');
	function logistic_company_sc_infobox_reg_shortcodes() {
	
		logistic_company_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'logistic-company'),
					"desc" => wp_kses_data( __("Infobox style", 'logistic-company') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'logistic-company'),
						'info' => esc_html__('Info', 'logistic-company'),
						'success' => esc_html__('Success', 'logistic-company'),
						'error' => esc_html__('Error', 'logistic-company')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'logistic-company'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'logistic-company') ),
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'logistic-company'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'logistic-company') ),
					"value" => "",
					"type" => "icons",
					"options" => logistic_company_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'logistic-company'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'logistic-company'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'logistic-company'),
					"desc" => wp_kses_data( __("Content for infobox", 'logistic-company') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'logistic_company_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_infobox_reg_shortcodes_vc');
	function logistic_company_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'logistic-company'),
			"description" => wp_kses_data( __("Box with info or error message", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'logistic-company'),
					"description" => wp_kses_data( __("Infobox style", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'logistic-company') => 'regular',
							esc_html__('Info', 'logistic-company') => 'info',
							esc_html__('Success', 'logistic-company') => 'success',
							esc_html__('Error', 'logistic-company') => 'error',
							esc_html__('Result', 'logistic-company') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'logistic-company'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'logistic-company') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'logistic-company') ),
					"class" => "",
					"value" => logistic_company_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'logistic-company'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'logistic-company'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends LOGISTIC_COMPANY_VC_ShortCodeContainer {}
	}
}
?>