<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_section_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_section_theme_setup' );
	function logistic_company_sc_section_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_section_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

logistic_company_storage_set('sc_section_dedicated', '');

if (!function_exists('logistic_company_sc_section')) {	
	function logistic_company_sc_section($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
            "section_style" => "",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = logistic_company_get_scheme_color('bg');
			$rgb = logistic_company_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(logistic_company_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!logistic_company_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(logistic_company_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !logistic_company_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = logistic_company_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && logistic_company_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = logistic_company_prepare_css_value($width);
		$height = logistic_company_prepare_css_value($height);
	
		if ((!logistic_company_param_is_off($scroll) || !logistic_company_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!logistic_company_param_is_off($scroll)) logistic_company_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '')
                    . ($section_style ? ' section_style_' . esc_attr($section_style) : '')
					. ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (logistic_company_param_is_on($scroll) && !logistic_company_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || logistic_company_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (logistic_company_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (logistic_company_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (logistic_company_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (logistic_company_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(logistic_company_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h3 class="sc_section_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(logistic_company_strmacros($title)) . '</h3>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(logistic_company_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.logistic_company_do_shortcode('[trx_button link="'.esc_url($link).'" size="medium"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (logistic_company_param_is_on($pan) ? '</div>' : '')
					. (logistic_company_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!logistic_company_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || logistic_company_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (logistic_company_param_is_on($dedicated)) {
			if (logistic_company_storage_get('sc_section_dedicated')=='') {
				logistic_company_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	logistic_company_require_shortcode('trx_section', 'logistic_company_sc_section');
}

if (!function_exists('logistic_company_sc_block')) {	
	function logistic_company_sc_block($atts, $content=null) {
		if(isset($atts['class'])) $atts['class'] = (!empty($atts['class']) ? $atts['class'] . ' ' : '') . 'sc_section_block';
		return apply_filters('logistic_company_shortcode_output', logistic_company_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	logistic_company_require_shortcode('trx_block', 'logistic_company_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_section_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_section_reg_shortcodes');
	function logistic_company_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'logistic-company'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'logistic-company') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
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
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'logistic-company'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'logistic-company') ),
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'logistic-company'),
					"desc" => wp_kses_data( __("Select block alignment", 'logistic-company') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'logistic-company'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'logistic-company') ),
					"value" => "none",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'logistic-company'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'logistic-company') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'logistic-company'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'logistic-company') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'logistic-company'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'logistic-company') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => logistic_company_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'logistic-company'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'logistic-company') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'logistic-company'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
					"value" => "",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'logistic-company'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'logistic-company') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'logistic-company'),
					"desc" => wp_kses_data( __("Any background color for this section", 'logistic-company') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'logistic-company'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'logistic-company') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'logistic-company'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'logistic-company') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'logistic-company'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'logistic-company') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'logistic-company'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'logistic-company') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'logistic-company'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'logistic-company') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'logistic-company'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'logistic-company'),
					"desc" => wp_kses_data( __("Font weight of the text", 'logistic-company') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'logistic-company'),
						'300' => esc_html__('Light (300)', 'logistic-company'),
						'400' => esc_html__('Normal (400)', 'logistic-company'),
						'700' => esc_html__('Bold (700)', 'logistic-company')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'logistic-company'),
					"desc" => wp_kses_data( __("Content for section container", 'logistic-company') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		logistic_company_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'logistic-company');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'logistic-company');
		logistic_company_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_section_reg_shortcodes_vc');
	function logistic_company_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'logistic-company'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'logistic-company'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'logistic-company'),
					"description" => wp_kses_data( __("Select block alignment", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('align')),
					"type" => "dropdown"
				),
                array(
                    "param_name" => "section_style",
                    "heading" => esc_html__("Section's style", 'logistic-company'),
                    "description" => wp_kses_data( __("Select section's style", 'logistic-company') ),
                    "class" => "",
                    "value" => array(
                        esc_html__('Original', 'logistic-company') => 'original_style',
                        esc_html__('Call to action', 'logistic-company') => 'call_to_action',
                        esc_html__('White text and red button', 'logistic-company') => 'white_text_section',
                        esc_html__('White text, title and button', 'logistic-company') => 'white_full_section',
                    ),
                    "type" => "dropdown"
                ),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'logistic-company'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('columns')),
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
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'logistic-company'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'logistic-company') ),
					"group" => esc_html__('Scroll', 'logistic-company'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'logistic-company'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'logistic-company') ),
					"group" => esc_html__('Scroll', 'logistic-company'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'logistic-company'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'logistic-company'),
					"value" => array_flip(logistic_company_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'logistic-company'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'logistic-company') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'logistic-company'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(logistic_company_get_sc_param('controls')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'logistic-company'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'logistic-company'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'logistic-company'),
					"description" => wp_kses_data( __("Any background color for this section", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'logistic-company'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'logistic-company'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'logistic-company'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'logistic-company'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'logistic-company'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'logistic-company') ),
					"group" => esc_html__('Colors and Images', 'logistic-company'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'logistic-company') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'logistic-company'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'logistic-company') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'logistic-company'),
					"description" => wp_kses_data( __("Font weight of the text", 'logistic-company') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'logistic-company') => 'inherit',
						esc_html__('Thin (100)', 'logistic-company') => '100',
						esc_html__('Light (300)', 'logistic-company') => '300',
						esc_html__('Normal (400)', 'logistic-company') => '400',
						esc_html__('Bold (700)', 'logistic-company') => '700'
					),
					"type" => "dropdown"
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'logistic-company');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'logistic-company') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
	}
}
?>