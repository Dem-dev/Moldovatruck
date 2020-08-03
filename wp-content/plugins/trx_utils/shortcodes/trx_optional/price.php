<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_price_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_price_theme_setup' );
	function logistic_company_sc_price_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_price_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('logistic_company_sc_price')) {	
	function logistic_company_sc_price($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($money).'</span>'
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : '')
				. '</div>';
		}
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	logistic_company_require_shortcode('trx_price', 'logistic_company_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_price_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_price_reg_shortcodes');
	function logistic_company_sc_price_reg_shortcodes() {
	
		logistic_company_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"money" => array(
					"title" => esc_html__("Money", 'logistic-company'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'logistic-company'),
					"desc" => wp_kses_data( __("Currency character", 'logistic-company') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'logistic-company'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'logistic-company'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('float')
				), 
				"top" => logistic_company_get_sc_param('top'),
				"bottom" => logistic_company_get_sc_param('bottom'),
				"left" => logistic_company_get_sc_param('left'),
				"right" => logistic_company_get_sc_param('right'),
				"id" => logistic_company_get_sc_param('id'),
				"class" => logistic_company_get_sc_param('class'),
				"css" => logistic_company_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_price_reg_shortcodes_vc');
	function logistic_company_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'logistic-company'),
			"description" => wp_kses_data( __("Insert price with decoration", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'logistic-company'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'logistic-company'),
					"description" => wp_kses_data( __("Currency character", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'logistic-company'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Align price to left or right side", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('float')),
					"type" => "dropdown"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('css'),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>