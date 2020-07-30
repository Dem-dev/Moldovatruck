<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_promo_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_promo_theme_setup' );
	function logistic_company_sc_promo_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_promo_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('logistic_company_sc_promo')) {	
	function logistic_company_sc_promo($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "large",
			"align" => "none",
			"image" => "",
			"image_position" => "left",
			"image_width" => "50%",
			"text_margins" => '',
			"text_align" => "left",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'logistic-company'),
			"link" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if ($image == '') {
			$image_width = '0%';
			$text_margins = '';
		}
		
		$width  = logistic_company_prepare_css_value($width);
		$height = logistic_company_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= logistic_company_get_css_dimensions_from_values($width, $height);
		
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '')
				     . (!empty($image_width) ? 'width:'.trim($image_width).';' : '')
				     . (!empty($image_position) ? $image_position.': 0;' : '');
	
		$text_width = logistic_company_strpos($image_width, '%')!==false
						? (100 - (int) str_replace('%', '', $image_width)).'%'
						: 'calc(100%-'.trim($image_width).')';
		$css_text = 'width: '.esc_attr($text_width).'; float: '.($image_position=='left' ? 'right' : 'left').';'.(!empty($text_margins) ? ' margin:'.esc_attr($text_margins).';' : '');
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($size ? ' sc_promo_size_'.esc_attr($size) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. (empty($image) ? ' no_image' : '')
						. '"'
					. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_inner">'
						. (!empty($image) ? '<div class="sc_promo_image" style="'.esc_attr($css_image).'"></div>' : '')
						. '<div class="sc_promo_block sc_align_'.esc_attr($text_align).'" style="'.esc_attr($css_text).'">'
							. '<div class="sc_promo_block_inner">'
									. (!empty($subtitle) ? '<h6 class="sc_promo_subtitle sc_item_subtitle">' . trim(logistic_company_strmacros($subtitle)) . '</h6>' : '')
									. (!empty($title) ? '<h2 class="sc_promo_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(logistic_company_strmacros($title)) . '</h2>' : '')
									. (!empty($description) ? '<div class="sc_promo_descr sc_item_descr">' . trim(logistic_company_strmacros($description)) . '</div>' : '')
									. (!empty($content) ? '<div class="sc_promo_content">'.do_shortcode($content).'</div>' : '')
									. (!empty($link) ? '<div class="sc_promo_button sc_item_button">'.logistic_company_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
							. '</div>'
						. '</div>'
					. '</div>'
				. '</div>';
	
	
	
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	logistic_company_require_shortcode('trx_promo', 'logistic_company_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_promo_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_promo_reg_shortcodes');
	function logistic_company_sc_promo_reg_shortcodes() {
	
		logistic_company_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert promo diagramm in your page (post)", 'logistic-company') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", 'logistic-company'),
					"desc" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('float')
				), 
				"size" => array(
					"title" => esc_html__("Size of the promo block", 'logistic-company'),
					"desc" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'logistic-company') ),
					"value" => "large",
					"type" => "switch",
					"options" => array(
						'small' => esc_html__('Small', 'logistic-company'),
						'large' => esc_html__('Large', 'logistic-company')
					)
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Select the promo image from the library for this section", 'logistic-company') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_position" => array(
					"title" => esc_html__("Image position", 'logistic-company'),
					"desc" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'logistic-company') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('hpos')
				),
				"image_width" => array(
					"title" => esc_html__("Image width", 'logistic-company'),
					"desc" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'logistic-company') ),
					"value" => "50%",
					"type" => "text"
				),
				"text_margins" => array(
					"title" => esc_html__("Text margins", 'logistic-company'),
					"desc" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"text_align" => array(
					"title" => esc_html__("Text alignment", 'logistic-company'),
					"desc" => wp_kses_data( __("Align the text inside the block", 'logistic-company') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('align')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'logistic-company'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('schemes')
				),
				"title" => array(
					"title" => esc_html__("Title", 'logistic-company'),
					"desc" => wp_kses_data( __("Title for the block", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'logistic-company'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'logistic-company'),
					"desc" => wp_kses_data( __("Short description for the block", 'logistic-company') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'logistic-company'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				), 
				"width" => logistic_company_shortcodes_width(),
				"height" => logistic_company_shortcodes_height(),
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
if ( !function_exists( 'logistic_company_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_promo_reg_shortcodes_vc');
	function logistic_company_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", 'logistic-company'),
			"description" => wp_kses_data( __("Insert promo block", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_collection trx_sc_promo",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", 'logistic-company'),
					"description" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'logistic-company') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(logistic_company_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Size of the promo block", 'logistic-company'),
					"description" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'logistic-company') ),
					"class" => "",
					"value" => array(esc_html__('Use small block', 'logistic-company') => 'small'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'logistic-company'),
					"description" => wp_kses_data( __("Select the promo image from the library for this section", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_position",
					"heading" => esc_html__("Image position", 'logistic-company'),
					"description" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'logistic-company') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(logistic_company_get_sc_param('hpos')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image_width",
					"heading" => esc_html__("Image width", 'logistic-company'),
					"description" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'logistic-company') ),
					"value" => '',
					"std" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_margins",
					"heading" => esc_html__("Text margins", 'logistic-company'),
					"description" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'logistic-company') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "text_align",
					"heading" => esc_html__("Text alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Align text to the left or to the right side inside the block", 'logistic-company') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(logistic_company_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'logistic-company'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'logistic-company'),
					"description" => wp_kses_data( __("Title for the block", 'logistic-company') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'logistic-company'),
					"description" => wp_kses_data( __("Subtitle for the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'logistic-company'),
					"description" => wp_kses_data( __("Description for the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'logistic-company'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'logistic-company'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'logistic-company') ),
					"group" => esc_html__('Captions', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_vc_width(),
				logistic_company_vc_height(),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Promo extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
	}
}
?>