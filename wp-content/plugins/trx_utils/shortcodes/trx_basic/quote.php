<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_quote_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_quote_theme_setup' );
	function logistic_company_sc_quote_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_quote_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('logistic_company_sc_quote')) {	
	function logistic_company_sc_quote($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= logistic_company_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (logistic_company_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	logistic_company_require_shortcode('trx_quote', 'logistic_company_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_quote_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_quote_reg_shortcodes');
	function logistic_company_sc_quote_reg_shortcodes() {
	
		logistic_company_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'logistic-company'),
			"desc" => wp_kses_data( __("Quote text", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'logistic-company'),
					"desc" => wp_kses_data( __("URL for quote cite", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'logistic-company'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'logistic-company'),
					"desc" => wp_kses_data( __("Quote content", 'logistic-company') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => logistic_company_shortcodes_width(),
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
if ( !function_exists( 'logistic_company_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_quote_reg_shortcodes_vc');
	function logistic_company_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'logistic-company'),
			"description" => wp_kses_data( __("Quote text", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'logistic-company'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'logistic-company'),
					"description" => wp_kses_data( __("Quote title (author name)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'logistic-company'),
					"description" => wp_kses_data( __("Quote content", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_vc_width(),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>