<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_anchor_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_anchor_theme_setup' );
	function logistic_company_sc_anchor_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_anchor_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('logistic_company_sc_anchor')) {	
	function logistic_company_sc_anchor($atts, $content = null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(logistic_company_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (logistic_company_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	logistic_company_require_shortcode("trx_anchor", "logistic_company_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_anchor_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_anchor_reg_shortcodes');
	function logistic_company_sc_anchor_reg_shortcodes() {
	
		logistic_company_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'logistic-company') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'logistic-company'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'logistic-company') ),
					"value" => "",
					"type" => "icons",
					"options" => logistic_company_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'logistic-company'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'logistic-company'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'logistic-company'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'logistic-company'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'logistic-company') ),
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"id" => logistic_company_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_anchor_reg_shortcodes_vc');
	function logistic_company_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'logistic-company'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'logistic-company') ),
					"class" => "",
					"value" => logistic_company_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'logistic-company'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'logistic-company'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'logistic-company'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'logistic-company'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'logistic-company') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				logistic_company_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>