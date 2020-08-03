<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'logistic_company_shortcodes_is_used' ) ) {
	function logistic_company_shortcodes_is_used() {
		return logistic_company_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')			// VC Role Manager
			|| (function_exists('logistic_company_vc_is_frontend') && logistic_company_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'logistic_company_shortcodes_width' ) ) {
	function logistic_company_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'logistic-company'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'logistic_company_shortcodes_height' ) ) {
	function logistic_company_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'logistic-company'),
			"desc" => wp_kses_data( __("Width and height of the element", 'logistic-company') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'logistic_company_get_sc_param' ) ) {
	function logistic_company_get_sc_param($prm) {
		return logistic_company_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'logistic_company_set_sc_param' ) ) {
	function logistic_company_set_sc_param($prm, $val) {
		logistic_company_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'logistic_company_sc_map' ) ) {
	function logistic_company_sc_map($sc_name, $sc_settings) {
		logistic_company_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'logistic_company_sc_map_after' ) ) {
	function logistic_company_sc_map_after($after, $sc_name, $sc_settings='') {
		logistic_company_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'logistic_company_sc_map_before' ) ) {
	function logistic_company_sc_map_before($before, $sc_name, $sc_settings='') {
		logistic_company_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'logistic_company_compare_sc_title' ) ) {
	function logistic_company_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'logistic_company_shortcodes_settings_theme_setup' ) ) {
//	if ( logistic_company_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'logistic_company_action_before_init_theme', 'logistic_company_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'logistic_company_action_after_init_theme', 'logistic_company_shortcodes_settings_theme_setup' );
	function logistic_company_shortcodes_settings_theme_setup() {
		if (logistic_company_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = logistic_company_storage_get('registered_templates');
			ksort($tmp);
			logistic_company_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			logistic_company_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'logistic-company'),
					"desc" => wp_kses_data( __("ID for current element", 'logistic-company') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'logistic-company'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'logistic-company'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'logistic-company') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'logistic-company'),
					'ol'	=> esc_html__('Ordered', 'logistic-company'),
					'iconed'=> esc_html__('Iconed', 'logistic-company')
				),

				'yes_no'	=> logistic_company_get_list_yesno(),
				'on_off'	=> logistic_company_get_list_onoff(),
				'dir' 		=> logistic_company_get_list_directions(),
				'align'		=> logistic_company_get_list_alignments(),
				'float'		=> logistic_company_get_list_floats(),
				'hpos'		=> logistic_company_get_list_hpos(),
				'show_hide'	=> logistic_company_get_list_showhide(),
				'sorting' 	=> logistic_company_get_list_sortings(),
				'ordering' 	=> logistic_company_get_list_orderings(),
				'shapes'	=> logistic_company_get_list_shapes(),
				'sizes'		=> logistic_company_get_list_sizes(),
				'sliders'	=> logistic_company_get_list_sliders(),
				'controls'	=> logistic_company_get_list_controls(),
                    'categories'=> is_admin() && logistic_company_get_value_gp('action')=='vc_edit_form' && substr(logistic_company_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? logistic_company_get_list_terms(false, logistic_company_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : logistic_company_get_list_categories(),
				'columns'	=> logistic_company_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), logistic_company_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), logistic_company_get_list_icons()),
				'locations'	=> logistic_company_get_list_dedicated_locations(),
				'filters'	=> logistic_company_get_list_portfolio_filters(),
				'formats'	=> logistic_company_get_list_post_formats_filters(),
				'hovers'	=> logistic_company_get_list_hovers(true),
				'hovers_dir'=> logistic_company_get_list_hovers_directions(true),
				'schemes'	=> logistic_company_get_list_color_schemes(true),
				'animations'		=> logistic_company_get_list_animations_in(),
				'margins' 			=> logistic_company_get_list_margins(true),
				'blogger_styles'	=> logistic_company_get_list_templates_blogger(),
				'forms'				=> logistic_company_get_list_templates_forms(),
				'posts_types'		=> logistic_company_get_list_posts_types(),
				'googlemap_styles'	=> logistic_company_get_list_googlemap_styles(),
				'field_types'		=> logistic_company_get_list_field_types(),
				'label_positions'	=> logistic_company_get_list_label_positions()
				)
			);

			// Common params
			logistic_company_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'logistic-company'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'logistic-company') ),
				"value" => "none",
				"type" => "select",
				"options" => logistic_company_get_sc_param('animations')
				)
			);
			logistic_company_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'logistic-company'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => logistic_company_get_sc_param('margins')
				)
			);
			logistic_company_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'logistic-company'),
				"value" => "inherit",
				"type" => "select",
				"options" => logistic_company_get_sc_param('margins')
				)
			);
			logistic_company_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'logistic-company'),
				"value" => "inherit",
				"type" => "select",
				"options" => logistic_company_get_sc_param('margins')
				)
			);
			logistic_company_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'logistic-company'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'logistic-company') ),
				"value" => "inherit",
				"type" => "select",
				"options" => logistic_company_get_sc_param('margins')
				)
			);

			logistic_company_storage_set('sc_params', apply_filters('logistic_company_filter_shortcodes_params', logistic_company_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			logistic_company_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('logistic_company_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = logistic_company_storage_get('shortcodes');
			uasort($tmp, 'logistic_company_compare_sc_title');
			logistic_company_storage_set('shortcodes', $tmp);
		}
	}
}
?>