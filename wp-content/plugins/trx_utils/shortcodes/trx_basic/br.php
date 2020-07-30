<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_br_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_br_theme_setup' );
	function logistic_company_sc_br_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('logistic_company_sc_br')) {	
	function logistic_company_sc_br($atts, $content = null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	logistic_company_require_shortcode("trx_br", "logistic_company_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_br_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_br_reg_shortcodes');
	function logistic_company_sc_br_reg_shortcodes() {
	
		logistic_company_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'logistic-company'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'logistic-company'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'logistic-company'),
						'left' => esc_html__('Left', 'logistic-company'),
						'right' => esc_html__('Right', 'logistic-company'),
						'both' => esc_html__('Both', 'logistic-company')
					)
				)
			)
		));
	}
}
?>