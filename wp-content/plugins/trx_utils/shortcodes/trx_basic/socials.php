<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_socials_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_socials_theme_setup' );
	function logistic_company_sc_socials_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_socials_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('logistic_company_sc_socials')) {	
	function logistic_company_sc_socials($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => logistic_company_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		logistic_company_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? logistic_company_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) logistic_company_storage_set_array('sc_social_data', 'icons', $list);
		} else if (logistic_company_param_is_on($custom))
			$content = do_shortcode($content);
		if (logistic_company_storage_get_array('sc_social_data', 'icons')===false) logistic_company_storage_set_array('sc_social_data', 'icons', logistic_company_get_custom_option('social_icons'));
		$output = logistic_company_prepare_socials(logistic_company_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	logistic_company_require_shortcode('trx_socials', 'logistic_company_sc_socials');
}


if (!function_exists('logistic_company_sc_social_item')) {	
	function logistic_company_sc_social_item($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (empty($icon)) {
			if (!empty($name)) {
				$type = logistic_company_storage_get_array('sc_social_data', 'type');
				if ($type=='images') {
					if (file_exists(logistic_company_get_socials_dir($name.'.png')))
						$icon = logistic_company_get_socials_url($name.'.png');
				} else
					$icon = 'icon-'.esc_attr($name);
			}
		} else if ((int) $icon > 0) {
			$attach = wp_get_attachment_image_src( $icon, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$icon = $attach[0];
		}
		if (!empty($icon) && !empty($url)) {
			if (logistic_company_storage_get_array('sc_social_data', 'icons')===false) logistic_company_storage_set_array('sc_social_data', 'icons', array());
			logistic_company_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	logistic_company_require_shortcode('trx_social_item', 'logistic_company_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_socials_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_socials_reg_shortcodes');
	function logistic_company_sc_socials_reg_shortcodes() {
	
		logistic_company_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'logistic-company'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'logistic-company') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'logistic-company'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'logistic-company') ),
					"value" => logistic_company_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'logistic-company'),
						'images' => esc_html__('Images', 'logistic-company')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'logistic-company'),
					"desc" => wp_kses_data( __("Size of the icons", 'logistic-company') ),
					"value" => "small",
					"options" => logistic_company_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'logistic-company'),
					"desc" => wp_kses_data( __("Shape of the icons", 'logistic-company') ),
					"value" => "square",
					"options" => logistic_company_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'logistic-company'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'logistic-company') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'logistic-company'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'logistic-company') ),
					"divider" => true,
					"value" => "no",
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
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'logistic-company'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'logistic-company') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'logistic-company'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'logistic-company') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'logistic-company'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'logistic-company') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'logistic-company'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'logistic-company') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_socials_reg_shortcodes_vc');
	function logistic_company_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'logistic-company'),
			"description" => wp_kses_data( __("Custom social icons", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'logistic-company'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'logistic-company') ),
					"class" => "",
					"std" => logistic_company_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'logistic-company') => 'icons',
						esc_html__('Images', 'logistic-company') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'logistic-company'),
					"description" => wp_kses_data( __("Size of the icons", 'logistic-company') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(logistic_company_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'logistic-company'),
					"description" => wp_kses_data( __("Shape of the icons", 'logistic-company') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(logistic_company_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'logistic-company'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'logistic-company'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'logistic-company') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'logistic-company') => 'yes'),
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'logistic-company'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'logistic-company') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'logistic-company'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'logistic-company'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'logistic-company'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>