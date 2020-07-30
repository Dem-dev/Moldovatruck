<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_tooltip_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_tooltip_theme_setup' );
	function logistic_company_sc_tooltip_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('logistic_company_sc_tooltip')) {	
	function logistic_company_sc_tooltip($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	logistic_company_require_shortcode('trx_tooltip', 'logistic_company_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_tooltip_reg_shortcodes');
	function logistic_company_sc_tooltip_reg_shortcodes() {
	
		logistic_company_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'logistic-company'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'logistic-company'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'logistic-company'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'logistic-company') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => logistic_company_get_sc_param('id'),
				"class" => logistic_company_get_sc_param('class'),
				"css" => logistic_company_get_sc_param('css')
			)
		));
	}
}
?>