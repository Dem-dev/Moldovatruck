<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_number_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_number_theme_setup' );
	function logistic_company_sc_number_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_number_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('logistic_company_sc_number')) {	
	function logistic_company_sc_number($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
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
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < logistic_company_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(logistic_company_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	logistic_company_require_shortcode('trx_number', 'logistic_company_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_number_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_number_reg_shortcodes');
	function logistic_company_sc_number_reg_shortcodes() {
	
		logistic_company_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'logistic-company'),
					"desc" => wp_kses_data( __("Number or any word", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'logistic-company'),
					"desc" => wp_kses_data( __("Select block alignment", 'logistic-company') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('align')
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
if ( !function_exists( 'logistic_company_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_number_reg_shortcodes_vc');
	function logistic_company_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'logistic-company'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'logistic-company'),
					"description" => wp_kses_data( __("Number or any word to separate", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Select block alignment", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('align')),
					"type" => "dropdown"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>