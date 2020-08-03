<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_hide_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_hide_theme_setup' );
	function logistic_company_sc_hide_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('logistic_company_sc_hide')) {	
	function logistic_company_sc_hide($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			logistic_company_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	logistic_company_require_shortcode('trx_hide', 'logistic_company_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_hide_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_hide_reg_shortcodes');
	function logistic_company_sc_hide_reg_shortcodes() {
	
		logistic_company_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'logistic-company'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'logistic-company'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'logistic-company'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'logistic-company') ),
					"value" => "yes",
					"size" => "small",
					"options" => logistic_company_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>