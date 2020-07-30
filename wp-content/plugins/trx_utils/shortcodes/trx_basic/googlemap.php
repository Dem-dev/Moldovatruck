<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_googlemap_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_googlemap_theme_setup' );
	function logistic_company_sc_googlemap_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_googlemap_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('logistic_company_sc_googlemap')) {	
	function logistic_company_sc_googlemap($atts, $content = null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= logistic_company_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = logistic_company_get_custom_option('googlemap_style');

        if (logistic_company_get_theme_option('api_google') != '') {
            $api_key = logistic_company_get_theme_option('api_google');
            wp_enqueue_script( 'googlemap', logistic_company_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
        }

//		$api_key = logistic_company_get_theme_option('api_google');
//		wp_enqueue_script( 'googlemap', logistic_company_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
//
		wp_enqueue_script( 'logistic-company-googlemap-script', logistic_company_get_file_url('js/core.googlemap.js'), array(), null, true );
		logistic_company_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = logistic_company_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => logistic_company_get_custom_option('googlemap_title'),
				'description' => logistic_company_strmacros(logistic_company_get_custom_option('googlemap_description')),
				'latlng' => logistic_company_get_custom_option('googlemap_latlng'),
				'address' => logistic_company_get_custom_option('googlemap_address'),
				'point' => logistic_company_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
        $cnt = 0;
        foreach ($markers as $marker) {
            $cnt++;
            if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
            if (logistic_company_get_theme_option('api_google') != '') {
                $output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
                    . ' data-title="'.esc_attr($marker['title']).'"'
                    . ' data-description="'.esc_attr(logistic_company_strmacros($marker['description'])).'"'
                    . ' data-address="'.esc_attr($marker['address']).'"'
                    . ' data-latlng="'.esc_attr($marker['latlng']).'"'
                    . ' data-point="'.esc_attr($marker['point']).'"'
                    . '></div>';
            } else {
                $output .= '<iframe src="https://maps.google.com/maps?t=m&output=embed&iwloc=near&z='.esc_attr($zoom > 0 ? $zoom : 14).'&q='
                    . esc_attr(!empty($marker['address']) ? urlencode($marker['address']) : '')
                    . ( !empty($marker['latlng'])
                        ? ( !empty($marker['address']) ? '@' : '' ) . str_replace(' ', '', $marker['latlng'])
                        : ''
                    )
                    . '" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"
                    aria-label="' . esc_attr(!empty($marker['title']) ? $marker['title'] : '') . '"></iframe>';
                break; // Remove this line if you want display separate iframe for each marker (otherwise only first marker shown)
            }
        }

        $output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	logistic_company_require_shortcode("trx_googlemap", "logistic_company_sc_googlemap");
}


if (!function_exists('logistic_company_sc_googlemap_marker')) {	
	function logistic_company_sc_googlemap_marker($atts, $content = null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		logistic_company_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : logistic_company_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	logistic_company_require_shortcode("trx_googlemap_marker", "logistic_company_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_googlemap_reg_shortcodes');
	function logistic_company_sc_googlemap_reg_shortcodes() {
	
		logistic_company_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'logistic-company'),
					"desc" => wp_kses_data( __("Map zoom factor", 'logistic-company') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'logistic-company'),
					"desc" => wp_kses_data( __("Select map style", 'logistic-company') ),
					"value" => "default",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'logistic-company'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('schemes')
				),
				"width" => logistic_company_shortcodes_width('100%'),
				"height" => logistic_company_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'logistic-company'),
				"desc" => wp_kses_data( __("Google map marker", 'logistic-company') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'logistic-company'),
						"desc" => wp_kses_data( __("Address of this marker", 'logistic-company') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'logistic-company'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'logistic-company') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'logistic-company'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'logistic-company') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'logistic-company'),
						"desc" => wp_kses_data( __("Title for this marker", 'logistic-company') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'logistic-company'),
						"desc" => wp_kses_data( __("Description for this marker", 'logistic-company') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => logistic_company_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_googlemap_reg_shortcodes_vc');
	function logistic_company_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'logistic-company'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'logistic-company'),
					"description" => wp_kses_data( __("Map zoom factor", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'logistic-company'),
					"description" => wp_kses_data( __("Map custom style", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'logistic-company'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_vc_width('100%'),
				logistic_company_vc_height(240),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'logistic-company'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'logistic-company') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'logistic-company'),
					"description" => wp_kses_data( __("Address of this marker", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'logistic-company'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'logistic-company'),
					"description" => wp_kses_data( __("Title for this marker", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'logistic-company'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				logistic_company_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
	}
}
?>