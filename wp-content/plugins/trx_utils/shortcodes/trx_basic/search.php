<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_search_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_search_theme_setup' );
	function logistic_company_sc_search_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_search_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('logistic_company_sc_search')) {	
	function logistic_company_sc_search($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'logistic-company'),
			"scheme" => "original",
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
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = logistic_company_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = logistic_company_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = logistic_company_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		logistic_company_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (logistic_company_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'logistic-company') : esc_attr__('Start search', 'logistic-company')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (logistic_company_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	logistic_company_require_shortcode('trx_search', 'logistic_company_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_search_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_search_reg_shortcodes');
	function logistic_company_sc_search_reg_shortcodes() {
	
		logistic_company_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'logistic-company'),
			"desc" => wp_kses_data( __("Show search form", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'logistic-company'),
					"desc" => wp_kses_data( __("Select style to display search field", 'logistic-company') ),
					"value" => "regular",
					"options" => logistic_company_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'logistic-company'),
					"desc" => wp_kses_data( __("Select search field initial state", 'logistic-company') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'logistic-company'),
						"opened" => esc_html__('Opened', 'logistic-company'),
						"closed" => esc_html__('Closed', 'logistic-company')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'logistic-company'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'logistic-company') ),
					"value" => esc_html__("Search &hellip;", 'logistic-company'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'logistic-company'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'logistic-company') ),
					"value" => "yes",
					"options" => logistic_company_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'logistic_company_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_search_reg_shortcodes_vc');
	function logistic_company_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'logistic-company'),
			"description" => wp_kses_data( __("Insert search form", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'logistic-company'),
					"description" => wp_kses_data( __("Select style to display search field", 'logistic-company') ),
					"class" => "",
					"value" => logistic_company_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'logistic-company'),
					"description" => wp_kses_data( __("Select search field initial state", 'logistic-company') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'logistic-company')  => "fixed",
						esc_html__('Opened', 'logistic-company') => "opened",
						esc_html__('Closed', 'logistic-company') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'logistic-company'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'logistic-company'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'logistic-company'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'logistic-company') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'logistic-company') => 'yes'),
					"type" => "checkbox"
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
		
		class WPBakeryShortCode_Trx_Search extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>