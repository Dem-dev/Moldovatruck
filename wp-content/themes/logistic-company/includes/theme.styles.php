<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('logistic_company_action_theme_styles_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_action_theme_styles_theme_setup', 1 );
	function logistic_company_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('logistic_company_filter_used_fonts',			'logistic_company_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('logistic_company_filter_list_fonts',			'logistic_company_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('logistic_company_action_add_styles',			'logistic_company_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('logistic_company_filter_add_styles_inline',		'logistic_company_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('logistic_company_action_add_scripts',			'logistic_company_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('logistic_company_filter_localize_script',		'logistic_company_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('logistic_company_filter_compile_less',			'logistic_company_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_light		- placeholder text
		input_dark		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		logistic_company_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'logistic-company'),
			
			// Whole block border and background
			'bd_color'				=> '#e6e6e6',       //
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#898b92',       //
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#555862',       //
			'text_link'				=> '#3ea2ee',       //
			'text_hover'			=> '#dd4a2d',       //

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#e4e4e4',       //
			'input_light'			=> '#d8d6d6',       //
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ebebeb',       //
			'input_bd_hover'		=> '#1e7cc4',       //
			'input_bg_color'		=> '#dfdfdf',       //
			'input_bg_hover'		=> '#2f8fd8',       //
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#8d959d',       //
			'alter_light'			=> '#898b92',       //c8c8c8
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#cdcdcd',       //
			'alter_hover'			=> '#1d2125',       //
			'alter_bd_color'		=> '#f3f3f3',       //
			'alter_bd_hover'		=> '#2a2a2a',       //
			'alter_bg_color'		=> '#f3f3f3',       //
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);

        // Add color schemes
        logistic_company_add_color_scheme('green', array(

                'title'					=> esc_html__('Green', 'logistic-company'),

                // Whole block border and background
                'bd_color'				=> '#e6e6e6',       //
                'bg_color'				=> '#ffffff',

                // Headers, text and links colors
                'text'					=> '#898b92',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#555862',       //
                'text_link'				=> '#3ea2ee',       //
                'text_hover'			=> '#1da650',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#e4e4e4',       //
                'input_light'			=> '#d8d6d6',       //
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#ebebeb',       //
                'input_bd_hover'		=> '#1e7cc4',       //
                'input_bg_color'		=> '#dfdfdf',       //
                'input_bg_hover'		=> '#2f8fd8',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#8d959d',       //
                'alter_light'			=> '#c8c8c8',       //
                'alter_dark'			=> '#232a34',
                'alter_link'			=> '#cdcdcd',       //
                'alter_hover'			=> '#1d2125',       //
                'alter_bd_color'		=> '#f3f3f3',       //
                'alter_bd_hover'		=> '#2a2a2a',       //
                'alter_bg_color'		=> '#f3f3f3',       //
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );

        // Add color schemes
        logistic_company_add_color_scheme('yellow', array(

                'title'					=> esc_html__('Yellow', 'logistic-company'),

                // Whole block border and background
                'bd_color'				=> '#e6e6e6',       //
                'bg_color'				=> '#ffffff',

                // Headers, text and links colors
                'text'					=> '#898b92',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#555862',       //
                'text_link'				=> '#3ea2ee',       //
                'text_hover'			=> '#ebcc27',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#e4e4e4',       //
                'input_light'			=> '#d8d6d6',       //
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#ebebeb',       //
                'input_bd_hover'		=> '#1e7cc4',       //
                'input_bg_color'		=> '#dfdfdf',       //
                'input_bg_hover'		=> '#2f8fd8',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#8d959d',       //
                'alter_light'			=> '#c8c8c8',       //
                'alter_dark'			=> '#232a34',
                'alter_link'			=> '#cdcdcd',       //
                'alter_hover'			=> '#1d2125',       //
                'alter_bd_color'		=> '#f3f3f3',       //
                'alter_bd_hover'		=> '#2a2a2a',       //
                'alter_bg_color'		=> '#f3f3f3',       //
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );


        // Add color schemes
        logistic_company_add_color_scheme('red', array(

                'title'					=> esc_html__('Red', 'logistic-company'),

                // Whole block border and background
                'bd_color'				=> '#e6e6e6',       //
                'bg_color'				=> '#ffffff',

                // Headers, text and links colors
                'text'					=> '#898b92',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#555862',       //
                'text_link'				=> '#3ea2ee',       //
                'text_hover'			=> '#e11612',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#e4e4e4',       //
                'input_light'			=> '#d8d6d6',       //
                'input_dark'			=> '#232a34',
                'input_bd_color'		=> '#ebebeb',       //
                'input_bd_hover'		=> '#1e7cc4',       //
                'input_bg_color'		=> '#dfdfdf',       //
                'input_bg_hover'		=> '#2f8fd8',       //

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#8d959d',       //
                'alter_light'			=> '#c8c8c8',       //
                'alter_dark'			=> '#232a34',
                'alter_link'			=> '#cdcdcd',       //
                'alter_hover'			=> '#1d2125',       //
                'alter_bd_color'		=> '#f3f3f3',       //
                'alter_bd_hover'		=> '#2a2a2a',       //
                'alter_bg_color'		=> '#f3f3f3',       //
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );



		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		logistic_company_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '5.143em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.25em',
			'margin-top'	=> '2.52em',
			'margin-bottom'	=> '0.25em'
			)
		);
		logistic_company_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '4.286em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.333em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '0.39em'
			)
		);
		logistic_company_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.429em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.333em',
			'margin-top'	=> '2.55em',
			'margin-bottom'	=> '0.64em'
			)
		);
		logistic_company_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.572em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.2em',
			'margin-top'	=> '2.75em',
			'margin-bottom'	=> '1.08em'
			)
		);
		logistic_company_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.714em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.334em',
			'margin-top'	=> '4.1em',
			'margin-bottom'	=> '0.6em'
			)
		);
		logistic_company_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.286em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.334em',
			'margin-top'	=> '5.95em',
			'margin-bottom'	=> '2em'
			)
		);
		logistic_company_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> 'Heebo',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.715em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		logistic_company_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		logistic_company_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.7em'
			)
		);
		logistic_company_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.071em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
			)
		);
		logistic_company_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.071em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '0.55em',
			'margin-bottom'	=> '0.4em'
			)
		);
		logistic_company_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.071em',
			'margin-top'	=> '2.85em',
			'margin-bottom'	=> '2.25em'
			)
		);
		logistic_company_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		logistic_company_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'logistic-company'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('logistic_company_filter_theme_styles_used_fonts')) {
	function logistic_company_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Heebo'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('logistic_company_filter_theme_styles_list_fonts')) {
	function logistic_company_filter_theme_styles_list_fonts($list) {
		if (!isset($list['Heebo']))	$list['Heebo'] = array('family'=>'sans-serif', 'link'=>'Heebo:300,400,500,700');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('logistic_company_filter_theme_styles_compile_less')) {
	function logistic_company_filter_theme_styles_compile_less($files) {
		if (file_exists(logistic_company_get_file_dir('css/theme.less'))) {
		 	$files[] = logistic_company_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('logistic_company_action_theme_styles_add_styles')) {
	function logistic_company_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( logistic_company_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'logistic-company-theme-style', logistic_company_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'logistic-company-theme-style', logistic_company_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('logistic_company_filter_theme_styles_add_styles_inline')) {
	function logistic_company_filter_theme_styles_add_styles_inline($custom_style) {

		// Submenu width
		$menu_width = logistic_company_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = logistic_company_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = logistic_company_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = logistic_company_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= logistic_company_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('logistic_company_action_theme_styles_add_scripts')) {
	function logistic_company_action_theme_styles_add_scripts() {
		if (logistic_company_get_theme_option('show_theme_customizer') == 'yes' && file_exists(logistic_company_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'logistic-company-theme_styles-customizer-script', logistic_company_get_file_url('js/theme.customizer.js'), array(), null, true );
	}
}

// Add theme scripts inline
if (!function_exists('logistic_company_filter_theme_styles_localize_script')) {
	function logistic_company_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = logistic_company_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = logistic_company_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = logistic_company_get_scheme_color('bg_color');
		return $vars;
	}
}
?>