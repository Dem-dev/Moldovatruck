<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'logistic_company_theme_setup' ) ) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_theme_setup', 1 );
	function logistic_company_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

        // Add wide and full blocks support
        add_theme_support( 'align-wide' );

		// Register theme menus
		add_filter( 'logistic_company_filter_add_theme_menus',		'logistic_company_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'logistic_company_filter_add_theme_sidebars',	'logistic_company_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'logistic_company_filter_importer_options',		'logistic_company_set_importer_options' );

		// Add theme required plugins
		add_filter( 'logistic_company_filter_required_plugins',		'logistic_company_add_required_plugins' );
		
		// Add preloader styles
		add_filter('logistic_company_filter_add_styles_inline',		'logistic_company_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'logistic_company_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'logistic_company_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'logistic_company_head_add_page_meta', 1);
		add_action('before',								'logistic_company_body_add_gtm');
		add_action('before',								'logistic_company_body_add_toc');
		add_action('before',								'logistic_company_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'logistic_company_footer_add_views_counter', 1);
		add_action('wp_footer',								'logistic_company_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'logistic_company_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'logistic_company_footer_add_custom_html', 1);
		add_action('wp_footer',								'logistic_company_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		logistic_company_storage_set('required_plugins', array(
			'essgrids',
			'revslider',
			'trx_utils',
			'visual_composer',
            'wp_gdpr_compliance',
            'contact_form_7'
			)
		);

        // Set list of the theme required custom fonts from folder /css/font-faces
        // Attention! Font's folder must have name equal to the font's name
        logistic_company_storage_set('required_custom_fonts', array(
            	'Amadeus'
            	)
        );

        logistic_company_storage_set('demo_data_url',  esc_url(logistic_company_get_protocol() . '://logistic-company.themerex.net/demo/'));
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'logistic_company_add_theme_menus' ) ) {
	function logistic_company_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'logistic_company_add_theme_sidebars' ) ) {
	function logistic_company_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'logistic-company' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'logistic-company' )
			);
			if (function_exists('logistic_company_exists_woocommerce') && logistic_company_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'logistic-company' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'logistic_company_add_required_plugins' ) ) {
	function logistic_company_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('Logistic Company Utilities', 'logistic-company'),
			'version'	=> '3.2',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> logistic_company_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}



//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'logistic_company_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'logistic_company_importer_set_options', 9 );
    function logistic_company_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( LOGISTIC_COMPANY_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = LOGISTIC_COMPANY_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( logistic_company_get_protocol().'://demofiles.themerex.net/logistic-company/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'essential-grid',
                'revslider',
                'trx_utils',
                'js_composer',
                'contact-form-7'
            );

            $options['theme_slug'] = 'logistic_company';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Education Demo', 'logistic-company' );
            $options['files']['default']['domain_dev'] = esc_url(logistic_company_get_protocol().'://logisticompany.dv.ancorathemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(logistic_company_get_protocol().'://logistic-company.themerex.net'); // Demo-site domain

        }
        return $options;
    }
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('logistic_company_body_classes') ) {
	function logistic_company_body_classes( $classes ) {

		$classes[] = 'logistic_company_body';
		$classes[] = 'body_style_' . trim(logistic_company_get_custom_option('body_style'));
		$classes[] = 'body_' . (logistic_company_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(logistic_company_get_custom_option('article_style'));
		
		$blog_style = logistic_company_get_custom_option(is_singular() && !logistic_company_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(logistic_company_get_template_name($blog_style));
		
		$body_scheme = logistic_company_get_custom_option('body_scheme');
		if (empty($body_scheme)  || logistic_company_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = logistic_company_get_custom_option('top_panel_position');
		if (!logistic_company_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = logistic_company_get_sidebar_class();

		if (logistic_company_get_custom_option('show_video_bg')=='yes' && (logistic_company_get_custom_option('video_bg_youtube_code')!='' || logistic_company_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!logistic_company_param_is_off(logistic_company_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('logistic_company_head_add_page_meta')) {
	function logistic_company_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (logistic_company_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('logistic_company_head_add_page_preloader_styles')) {
	function logistic_company_head_add_page_preloader_styles($css) {
		if (($preloader=logistic_company_get_theme_option('page_preloader'))!='none') {
			$image = logistic_company_get_theme_option('page_preloader_image');
			$bg_clr = logistic_company_get_scheme_color('bg_color');
			$link_clr = logistic_company_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('logistic_company_body_add_gtm')) {
	function logistic_company_body_add_gtm() {
		echo wp_kses_data(logistic_company_get_custom_option('gtm_code'));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('logistic_company_body_add_toc')) {
	function logistic_company_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (logistic_company_get_custom_option('menu_toc_home')=='yes' && function_exists('logistic_company_sc_anchor'))
            logistic_company_show_layout(logistic_company_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'logistic-company'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'logistic-company'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (logistic_company_get_custom_option('menu_toc_top')=='yes' && function_exists('logistic_company_sc_anchor'))
            logistic_company_show_layout(logistic_company_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'logistic-company'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'logistic-company'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('logistic_company_body_add_page_preloader')) {
	function logistic_company_body_add_page_preloader() {
		if ( ($preloader=logistic_company_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=logistic_company_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('logistic_company_footer_add_views_counter')) {
	function logistic_company_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(logistic_company_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add theme customizer
if (!function_exists('logistic_company_footer_add_theme_customizer')) {
	function logistic_company_footer_add_theme_customizer() {
		// Front customizer
		if (logistic_company_get_custom_option('show_theme_customizer')=='yes') {
            require_once LOGISTIC_COMPANY_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('logistic_company_footer_add_scroll_to_top')) {
	function logistic_company_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'logistic-company'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('logistic_company_footer_add_custom_html')) {
	function logistic_company_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			echo wp_kses_data(logistic_company_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('logistic_company_footer_add_gtm2')) {
	function logistic_company_footer_add_gtm2() {
		echo wp_kses_data(logistic_company_get_custom_option('gtm_code2'));
	}
}

function logistic_company_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter( 'comment_form_fields', 'logistic_company_move_comment_field_to_bottom' );

// Add theme required plugins
if ( !function_exists( 'logistic_company_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'logistic_company_add_trx_utils' );
    function logistic_company_add_trx_utils($enable=true) {
        return true;
    }
}

//Child-Theme functions and definitions

function logistic_company_child_scripts() {
    wp_enqueue_style( 'logistic-company-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'logistic_company_child_scripts' );


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>