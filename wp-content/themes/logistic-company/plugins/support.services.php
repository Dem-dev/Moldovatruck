<?php
/**
 * Logistic Company Framework: Services support
 *
 * @package	logistic_company
 * @since	logistic_company 1.0
 */

// Theme init
if (!function_exists('logistic_company_services_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_services_theme_setup',1 );
	function logistic_company_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('logistic_company_filter_get_blog_type',			'logistic_company_services_get_blog_type', 9, 2);
		add_filter('logistic_company_filter_get_blog_title',		'logistic_company_services_get_blog_title', 9, 2);
		add_filter('logistic_company_filter_get_current_taxonomy',	'logistic_company_services_get_current_taxonomy', 9, 2);
		add_filter('logistic_company_filter_is_taxonomy',			'logistic_company_services_is_taxonomy', 9, 2);
		add_filter('logistic_company_filter_get_stream_page_title',	'logistic_company_services_get_stream_page_title', 9, 2);
		add_filter('logistic_company_filter_get_stream_page_link',	'logistic_company_services_get_stream_page_link', 9, 2);
		add_filter('logistic_company_filter_get_stream_page_id',	'logistic_company_services_get_stream_page_id', 9, 2);
		add_filter('logistic_company_filter_query_add_filters',		'logistic_company_services_query_add_filters', 9, 2);
		add_filter('logistic_company_filter_detect_inheritance_key','logistic_company_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (logistic_company_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'logistic_company_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'logistic_company_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('logistic_company_action_shortcodes_list',		'logistic_company_services_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_services_reg_shortcodes_vc');
		
		// Add supported data types
		logistic_company_theme_support_pt('services');
		logistic_company_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'logistic_company_services_settings_theme_setup2' ) ) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_services_settings_theme_setup2', 3 );
	function logistic_company_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		logistic_company_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'custom'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'logistic_company_is_services_page' ) ) {
	function logistic_company_is_services_page() {
		$is = in_array(logistic_company_storage_get('page_template'), array('blog-services', 'single-service'));
		if (!$is) {
			if (!logistic_company_storage_empty('pre_query'))
				$is = logistic_company_storage_call_obj_method('pre_query', 'get', 'post_type')=='services' 
						|| logistic_company_storage_call_obj_method('pre_query', 'is_tax', 'services_group') 
						|| (logistic_company_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=logistic_company_get_template_page_id('blog-services')) > 0 
								&& $id==logistic_company_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=logistic_company_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'logistic_company_services_detect_inheritance_key' ) ) {
	//Handler of add_filter('logistic_company_filter_detect_inheritance_key',	'logistic_company_services_detect_inheritance_key', 9, 1);
	function logistic_company_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return logistic_company_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'logistic_company_services_get_blog_type' ) ) {
	//Handler of add_filter('logistic_company_filter_get_blog_type',	'logistic_company_services_get_blog_type', 9, 2);
	function logistic_company_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'logistic_company_services_get_blog_title' ) ) {
	//Handler of add_filter('logistic_company_filter_get_blog_title',	'logistic_company_services_get_blog_title', 9, 2);
	function logistic_company_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( logistic_company_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = logistic_company_get_post_title();
			} else {
				$title = esc_html__('All services', 'logistic-company');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'logistic_company_services_get_stream_page_title' ) ) {
	//Handler of add_filter('logistic_company_filter_get_stream_page_title',	'logistic_company_services_get_stream_page_title', 9, 2);
	function logistic_company_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (logistic_company_strpos($page, 'services')!==false) {
			if (($page_id = logistic_company_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = logistic_company_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'logistic-company');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'logistic_company_services_get_stream_page_id' ) ) {
	//Handler of add_filter('logistic_company_filter_get_stream_page_id',	'logistic_company_services_get_stream_page_id', 9, 2);
	function logistic_company_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (logistic_company_strpos($page, 'services')!==false) $id = logistic_company_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'logistic_company_services_get_stream_page_link' ) ) {
	//Handler of add_filter('logistic_company_filter_get_stream_page_link',	'logistic_company_services_get_stream_page_link', 9, 2);
	function logistic_company_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (logistic_company_strpos($page, 'services')!==false) {
			$id = logistic_company_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'logistic_company_services_get_current_taxonomy' ) ) {
	//Handler of add_filter('logistic_company_filter_get_current_taxonomy',	'logistic_company_services_get_current_taxonomy', 9, 2);
	function logistic_company_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( logistic_company_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'logistic_company_services_is_taxonomy' ) ) {
	//Handler of add_filter('logistic_company_filter_is_taxonomy',	'logistic_company_services_is_taxonomy', 9, 2);
	function logistic_company_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'logistic_company_services_query_add_filters' ) ) {
	//Handler of add_filter('logistic_company_filter_query_add_filters',	'logistic_company_services_query_add_filters', 9, 2);
	function logistic_company_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'logistic_company_sc_services' ) ) {
	function logistic_company_sc_services($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"equalheight" => "no",
			"align" => "",
            "size_style" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'logistic-company'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'logistic-company'),
			"link" => '',
			"scheme" => '',
			"image" => '',
			"image_align" => '',
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
	
		if (logistic_company_param_is_off($slider) && $columns > 1 && $style == 'services-5' && !empty($image)) $columns = 2;
		if (!empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
		}

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && logistic_company_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = logistic_company_get_css_dimensions_from_values($width);
		$hs = logistic_company_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$columns = max(1, min(12, (int) $columns));
		$count = max(1, (int) $count);
		if (logistic_company_param_is_off($custom) && $count < $columns) $columns = $count;

		if (logistic_company_param_is_on($slider)) logistic_company_enqueue_slider('swiper');

		logistic_company_storage_set('sc_services_data', array(
			'id' => $id,
            'style' => $style,
            'type' => $type,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs,
            'readmore' => $readmore
            )
        );
        $alt = basename($image);
        $alt = substr($alt,0,strlen($alt) - 4);


        $output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
						. ' class="sc_services_wrap'
						. ($scheme && !logistic_company_param_is_off($scheme) && !logistic_company_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(logistic_company_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
                            . (!empty($size_style) ? ' '.esc_attr($size_style) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!logistic_company_param_is_off($equalheight) ? ' data-equal-height=".sc_services_item"' : '')
						. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(logistic_company_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(logistic_company_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(logistic_company_strmacros($description)) . '</div>' : '')
					. (logistic_company_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(logistic_company_get_slider_controls_classes($controls))
										. (logistic_company_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && logistic_company_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && logistic_company_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ' data-slides-min-width="250"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? ($style == 'services-5' && !empty($image) 
								? '<div class="sc_service_container sc_align_'.esc_attr($image_align).'">'
									. '<div class="sc_services_image"><img src="'.esc_url($image).'" alt="'.esc_html($alt).'"></div>'
								: '')
								. '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		if (logistic_company_param_is_on($custom) && $content) {
			$output .= do_shortcode($content);
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = logistic_company_query_add_sort_order($args, $orderby, $order);
			$args = logistic_company_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => logistic_company_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= logistic_company_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (logistic_company_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
			if ($style == 'services-5' && !empty($image))
				$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.logistic_company_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('logistic_company_action_blog_scripts', $style);
	
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	logistic_company_require_shortcode('trx_services', 'logistic_company_sc_services');
}


if ( !function_exists( 'logistic_company_sc_services_item' ) ) {
	function logistic_company_sc_services_item($atts, $content=null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		logistic_company_storage_inc_array('sc_services_data', 'counter');

		$id = $id ? $id : (logistic_company_storage_get_array('sc_services_data', 'id') ? logistic_company_storage_get_array('sc_services_data', 'id') . '_' . logistic_company_storage_get_array('sc_services_data', 'counter') : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? logistic_company_storage_get_array('sc_services_data', 'readmore') : $readmore;

		$type = logistic_company_storage_get_array('sc_services_data', 'type');
		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = logistic_company_get_thumb_sizes(array('layout' => logistic_company_storage_get_array('sc_services_data', 'style')));
			$image = logistic_company_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => logistic_company_storage_get_array('sc_services_data', 'style'),
			'number' => logistic_company_storage_get_array('sc_services_data', 'counter'),
			'columns_count' => logistic_company_storage_get_array('sc_services_data', 'columns'),
			'slider' => logistic_company_storage_get_array('sc_services_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => logistic_company_storage_get_array('sc_services_data', 'css_wh')
		);
		$output = logistic_company_show_post_layout($args, $post_data);
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	logistic_company_require_shortcode('trx_services_item', 'logistic_company_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('logistic_company_services_reg_shortcodes')) {
	//Handler of add_filter('logistic_company_action_shortcodes_list',	'logistic_company_services_reg_shortcodes');
	function logistic_company_services_reg_shortcodes() {
		if (logistic_company_storage_isset('shortcodes')) {

			$services_groups = logistic_company_get_list_terms(false, 'services_group');
			$services_styles = logistic_company_get_list_templates('services');
			$controls 		 = logistic_company_get_list_slider_controls();

			logistic_company_sc_map_after('trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'logistic-company'),
					"desc" => wp_kses_data( __("Insert services list in your page (post)", 'logistic-company') ),
					"decorate" => true,
					"container" => false,
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
						"style" => array(
							"title" => esc_html__("Services style", 'logistic-company'),
							"desc" => wp_kses_data( __("Select style to display services list", 'logistic-company') ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"image" => array(
								"title" => esc_html__("Item's image", 'logistic-company'),
								"desc" => wp_kses_data( __("Item's image", 'logistic-company') ),
								"dependency" => array(
									'style' => 'services-5'
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
						),
						"image_align" => array(
							"title" => esc_html__("Image alignment", 'logistic-company'),
							"desc" => wp_kses_data( __("Alignment of the image", 'logistic-company') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => logistic_company_get_sc_param('align')
						),
						"type" => array(
							"title" => esc_html__("Icon's type", 'logistic-company'),
							"desc" => wp_kses_data( __("Select type of icons: font icon or image", 'logistic-company') ),
							"value" => "icons",
							"type" => "hidden",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'logistic-company'),
								'images' => esc_html__('Images', 'logistic-company')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'logistic-company'),
							"desc" => wp_kses_data( __("How many columns use to show services list", 'logistic-company') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'logistic-company'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
							"value" => "",
							"type" => "checklist",
							"options" => logistic_company_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'logistic-company'),
							"desc" => wp_kses_data( __("Use slider to show services", 'logistic-company') ),
							"value" => "no",
							"type" => "switch",
							"options" => logistic_company_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'logistic-company'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'logistic-company') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'logistic-company'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'logistic-company') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'logistic-company'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'logistic-company') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'logistic-company'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'logistic-company') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => logistic_company_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'logistic-company'),
							"desc" => wp_kses_data( __("Alignment of the services block", 'logistic-company') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => logistic_company_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'logistic-company'),
							"desc" => wp_kses_data( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'logistic-company') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => logistic_company_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'logistic-company'),
							"desc" => wp_kses_data( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => logistic_company_array_merge(array(0 => esc_html__('- Select category -', 'logistic-company')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'logistic-company'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'logistic-company'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'logistic-company'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => logistic_company_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'logistic-company'),
							"desc" => wp_kses_data( __("Select desired posts order", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => logistic_company_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'logistic-company'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'logistic-company') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", 'logistic-company'),
							"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'logistic-company') ),
							"value" => "",
							"type" => "text"
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
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'logistic-company'),
						"desc" => wp_kses_data( __("Service item", 'logistic-company') ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'logistic-company'),
								"desc" => wp_kses_data( __("Item's title", 'logistic-company') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'logistic-company'),
								"desc" => wp_kses_data( __('Select icon for the item from Fontello icons set',  'logistic-company') ),
								"value" => "",
								"type" => "icons",
								"options" => logistic_company_get_sc_param('icons')
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'logistic-company'),
								"desc" => wp_kses_data( __("Item's image (if icon not selected)", 'logistic-company') ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'logistic-company'),
								"desc" => wp_kses_data( __("Link on service's item page", 'logistic-company') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'logistic-company'),
								"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'logistic-company') ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'logistic-company'),
								"desc" => wp_kses_data( __("Item's short description", 'logistic-company') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => logistic_company_get_sc_param('id'),
							"class" => logistic_company_get_sc_param('class'),
							"animation" => logistic_company_get_sc_param('animation'),
							"css" => logistic_company_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('logistic_company_services_reg_shortcodes_vc')) {
	//Handler of add_filter('logistic_company_action_shortcodes_list_vc',	'logistic_company_services_reg_shortcodes_vc');
	function logistic_company_services_reg_shortcodes_vc() {

		$services_groups = logistic_company_get_list_terms(false, 'services_group');
		$services_styles = logistic_company_get_list_templates('services');
		$controls		 = logistic_company_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", 'logistic-company'),
				"description" => wp_kses_data( __("Insert services list", 'logistic-company') ),
				"category" => esc_html__('Content', 'logistic-company'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", 'logistic-company'),
						"description" => wp_kses_data( __("Select style to display services list", 'logistic-company') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", 'logistic-company'),
						"description" => wp_kses_data( __("Select type of icons: font icon or image", 'logistic-company') ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'logistic-company') => 'icons',
							esc_html__('Images', 'logistic-company') => 'images'
						),
						"type" => "hidden"
					),
                    array(
                        "param_name" => "size_style",
                        "heading" => esc_html__("Select style (size)", 'logistic-company'),
                        "description" => wp_kses_data( __("Select style (size)", 'logistic-company') ),
                        "class" => "",
                        'dependency' => array(
                            'element' => 'style',
                            'value' => 'services-1'
                        ),
                        "admin_label" => true,
                        "value" => array(
                            esc_html__('Original', 'logistic-company') => 'original',
                            esc_html__('Compact', 'logistic-company') => 'compact'
                        ),
                        "type" => "dropdown"
                    ),

					array(
						"param_name" => "equalheight",
						"heading" => esc_html__("Equal height", 'logistic-company'),
						"description" => wp_kses_data( __("Make equal height for all items in the row", 'logistic-company') ),
						"value" => array("Equal height" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'logistic-company'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'logistic-company') ),
						"class" => "",
						"value" => array_flip(logistic_company_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'logistic-company'),
						"description" => wp_kses_data( __("Item's image", 'logistic-company') ),
						'dependency' => array(
							'element' => 'style',
							'value' => 'services-5'
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_align",
						"heading" => esc_html__("Image alignment", 'logistic-company'),
						"description" => wp_kses_data( __("Alignment of the image", 'logistic-company') ),
						"class" => "",
						"value" => array_flip(logistic_company_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'logistic-company'),
						"description" => wp_kses_data( __("Use slider to show services", 'logistic-company') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'logistic-company'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(logistic_company_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'logistic-company'),
						"description" => wp_kses_data( __("Slider controls style and position", 'logistic-company') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'logistic-company'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'logistic-company'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'logistic-company') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'logistic-company'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'logistic-company'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'logistic-company') ),
						"group" => esc_html__('Slider', 'logistic-company'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'logistic-company'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'logistic-company') ),
						"group" => esc_html__('Slider', 'logistic-company'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'logistic-company'),
						"description" => wp_kses_data( __("Alignment of the services block", 'logistic-company') ),
						"class" => "",
						"value" => array_flip(logistic_company_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'logistic-company'),
						"description" => wp_kses_data( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'logistic-company') ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
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
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'logistic-company'),
						"description" => wp_kses_data( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(logistic_company_array_merge(array(0 => esc_html__('- Select category -', 'logistic-company')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'logistic-company'),
						"description" => wp_kses_data( __("How many columns use to show services list", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'logistic-company'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'logistic-company') ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'logistic-company'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'logistic-company'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(logistic_company_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'logistic-company'),
						"description" => wp_kses_data( __("Select desired posts order", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(logistic_company_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", 'logistic-company'),
						"description" => wp_kses_data( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'logistic-company') ),
						"group" => esc_html__('Query', 'logistic-company'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'logistic-company'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'logistic-company') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'logistic-company'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
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
					logistic_company_vc_width(),
					logistic_company_vc_height(),
					logistic_company_get_vc_param('margin_top'),
					logistic_company_get_vc_param('margin_bottom'),
					logistic_company_get_vc_param('margin_left'),
					logistic_company_get_vc_param('margin_right'),
					logistic_company_get_vc_param('id'),
					logistic_company_get_vc_param('class'),
					logistic_company_get_vc_param('animation'),
					logistic_company_get_vc_param('css')
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'logistic-company' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'logistic-company' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'logistic-company' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'logistic-company' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", 'logistic-company'),
				"description" => wp_kses_data( __("Custom services item - all data pull out from shortcode parameters", 'logistic-company') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'logistic-company'),
						"description" => wp_kses_data( __("Item's title", 'logistic-company') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'logistic-company'),
						"description" => wp_kses_data( __("Select icon for the item from Fontello icons set", 'logistic-company') ),
						"admin_label" => true,
						"class" => "",
						"value" => logistic_company_get_sc_param('icons'),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'logistic-company'),
						"description" => wp_kses_data( __("Item's image (if icon is empty)", 'logistic-company') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'logistic-company'),
						"description" => wp_kses_data( __("Link on item's page", 'logistic-company') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'logistic-company'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'logistic-company') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					logistic_company_get_vc_param('id'),
					logistic_company_get_vc_param('class'),
					logistic_company_get_vc_param('animation'),
					logistic_company_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends LOGISTIC_COMPANY_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}

	}
}
?>