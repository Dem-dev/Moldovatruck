<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_title_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_title_theme_setup' );
	function logistic_company_sc_title_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_title_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('logistic_company_sc_title')) {	
	function logistic_company_sc_title($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
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
		$css .= logistic_company_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !logistic_company_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !logistic_company_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(logistic_company_strpos($image, 'http')===0 ? $image : logistic_company_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !logistic_company_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	logistic_company_require_shortcode('trx_title', 'logistic_company_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_title_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_title_reg_shortcodes');
	function logistic_company_sc_title_reg_shortcodes() {
	
		logistic_company_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'logistic-company'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'logistic-company') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'logistic-company'),
					"desc" => wp_kses_data( __("Title content", 'logistic-company') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'logistic-company'),
					"desc" => wp_kses_data( __("Title type (header level)", 'logistic-company') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'logistic-company'),
						'2' => esc_html__('Header 2', 'logistic-company'),
						'3' => esc_html__('Header 3', 'logistic-company'),
						'4' => esc_html__('Header 4', 'logistic-company'),
						'5' => esc_html__('Header 5', 'logistic-company'),
						'6' => esc_html__('Header 6', 'logistic-company'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'logistic-company'),
					"desc" => wp_kses_data( __("Title style", 'logistic-company') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'logistic-company'),
						'underline' => esc_html__('Underline', 'logistic-company'),
						'divider' => esc_html__('Divider', 'logistic-company'),
						'iconed' => esc_html__('With icon (image)', 'logistic-company')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'logistic-company'),
					"desc" => wp_kses_data( __("Title text alignment", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'logistic-company'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'logistic-company'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'logistic-company') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'logistic-company'),
						'100' => esc_html__('Thin (100)', 'logistic-company'),
						'300' => esc_html__('Light (300)', 'logistic-company'),
						'400' => esc_html__('Normal (400)', 'logistic-company'),
						'600' => esc_html__('Semibold (600)', 'logistic-company'),
						'700' => esc_html__('Bold (700)', 'logistic-company'),
						'900' => esc_html__('Black (900)', 'logistic-company')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'logistic-company'),
					"desc" => wp_kses_data( __("Select color for the title", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'logistic-company'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'logistic-company') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => logistic_company_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'logistic-company'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'logistic-company') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => logistic_company_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'logistic-company'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'logistic-company') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'logistic-company'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'logistic-company') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'logistic-company'),
						'medium' => esc_html__('Medium', 'logistic-company'),
						'large' => esc_html__('Large', 'logistic-company')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'logistic-company'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'logistic-company') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'logistic-company'),
						'left' => esc_html__('Left', 'logistic-company')
					)
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
if ( !function_exists( 'logistic_company_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_title_reg_shortcodes_vc');
	function logistic_company_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'logistic-company'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'logistic-company'),
					"description" => wp_kses_data( __("Title content", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'logistic-company'),
					"description" => wp_kses_data( __("Title type (header level)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'logistic-company') => '1',
						esc_html__('Header 2', 'logistic-company') => '2',
						esc_html__('Header 3', 'logistic-company') => '3',
						esc_html__('Header 4', 'logistic-company') => '4',
						esc_html__('Header 5', 'logistic-company') => '5',
						esc_html__('Header 6', 'logistic-company') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'logistic-company'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'logistic-company') => 'regular',
						esc_html__('Underline', 'logistic-company') => 'underline',
						esc_html__('Divider', 'logistic-company') => 'divider',
						esc_html__('With icon (image)', 'logistic-company') => 'iconed',
                        esc_html__('Icon as divider', 'logistic-company') => 'icon-divider',
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Title text alignment", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'logistic-company'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'logistic-company'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'logistic-company') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'logistic-company') => 'inherit',
						esc_html__('Thin (100)', 'logistic-company') => '100',
						esc_html__('Light (300)', 'logistic-company') => '300',
						esc_html__('Normal (400)', 'logistic-company') => '400',
						esc_html__('Semibold (600)', 'logistic-company') => '600',
						esc_html__('Bold (700)', 'logistic-company') => '700',
						esc_html__('Black (900)', 'logistic-company') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'logistic-company'),
					"description" => wp_kses_data( __("Select color for the title", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => logistic_company_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'logistic-company'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'logistic-company'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => logistic_company_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'logistic-company'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'logistic-company') ),
					"group" => esc_html__('Icon &amp; Image', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'logistic-company'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'logistic-company') ),
					"group" => esc_html__('Icon &amp; Image', 'logistic-company'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'logistic-company') => 'small',
						esc_html__('Medium', 'logistic-company') => 'medium',
						esc_html__('Large', 'logistic-company') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'logistic-company'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'logistic-company') ),
					"group" => esc_html__('Icon &amp; Image', 'logistic-company'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'logistic-company') => 'top',
						esc_html__('Left', 'logistic-company') => 'left'
					),
					"type" => "dropdown"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('animation'),
				logistic_company_get_vc_param('css'),
				logistic_company_get_vc_param('margin_top'),
				logistic_company_get_vc_param('margin_bottom'),
				logistic_company_get_vc_param('margin_left'),
				logistic_company_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>