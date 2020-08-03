<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('logistic_company_sc_slider_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_sc_slider_theme_setup' );
	function logistic_company_sc_slider_theme_setup() {
		add_action('logistic_company_action_shortcodes_list', 		'logistic_company_sc_slider_reg_shortcodes');
		if (function_exists('logistic_company_exists_visual_composer') && logistic_company_exists_visual_composer())
			add_action('logistic_company_action_shortcodes_list_vc','logistic_company_sc_slider_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('logistic_company_sc_slider')) {	
	function logistic_company_sc_slider($atts, $content=null){	
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => logistic_company_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && logistic_company_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		logistic_company_storage_set('sc_slider_data', array(
			'engine' => $engine,
            'width'  => logistic_company_prepare_css_value($width),
            'height' => logistic_company_prepare_css_value($height),
            'links'  => logistic_company_param_is_on($links),
            'bg_image' => logistic_company_get_theme_setting('slides_type')=='bg',
            'crop_image' => $crop
            )
        );
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$class2 = logistic_company_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = logistic_company_get_css_dimensions_from_values($width);
		$hs = logistic_company_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class2 ? ' '.esc_attr($class2) : '')
							. '"'
						. (!logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
						. ($hs ? ' style="'.esc_attr($hs).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (logistic_company_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (logistic_company_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (logistic_company_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. (logistic_company_storage_get_array('sc_slider_data', 'bg_image') ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) 
							? ($class2 ? ' '.esc_attr($class2) : '') . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							: '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !logistic_company_param_is_off($animation) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per-view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && logistic_company_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && logistic_company_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		logistic_company_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (!empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && logistic_company_storage_get_array('sc_slider_data', 'bg_image') ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
			$content = do_shortcode($content);
			
			if (logistic_company_param_is_on($custom) && $content) {
				$output .= $content;
			} else {
				global $post;
		
				if (!empty($ids)) {
					$posts = explode(',', $ids);
					$count = count($posts);
				}
			
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = logistic_company_query_add_sort_order($args, $orderby, $order);
				$args = logistic_company_query_add_filters($args, 'thumbs');
				$args = logistic_company_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating

				$post_rating = logistic_company_storage_get('options_prefix').'_reviews_avg'.(logistic_company_get_theme_option('reviews_first')=='author' ? '' : '2');
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (logistic_company_param_is_on($crop)) {
						$post_attachment = logistic_company_storage_get_array('sc_slider_data', 'bg_image')
							? logistic_company_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: logistic_company_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!logistic_company_storage_get_array('sc_slider_data', 'bg_image')) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(logistic_company_get_file_dir('templates/_parts/widgets-posts.php'))) {
							logistic_company_template_set_args('widgets-posts', array(
								'post_number' => $post_number,
								'post_rating' => $post_rating,
								'show_date' => $show_date,
								'show_image' => $show_image,
								'show_author' => $show_author,
								'show_links' => $show_links,
								'show_counters' => $show_counters
							));
							get_template_part(logistic_company_get_file_slug('templates/_parts/widgets-posts.php'));
							$output .= logistic_company_storage_get('widgets_posts_output');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. (logistic_company_storage_get_array('sc_slider_data', 'bg_image') ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (logistic_company_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!logistic_company_storage_get_array('sc_slider_data', 'bg_image') ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!logistic_company_param_is_off($titles)) {
						$post_hover_bg  = logistic_company_get_scheme_color('text_link');
						$post_bg = '';
						if ($post_hover_bg!='' && !logistic_company_is_inherit_option($post_hover_bg)) {
							$rgb = logistic_company_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = logistic_company_get_post_excerpt();
						if (logistic_company_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = logistic_company_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = logistic_company_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(logistic_company_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (logistic_company_get_custom_option('show_reviews')=='yes' && logistic_company_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = logistic_company_reviews_marks_to_display(get_post_meta($post_id, logistic_company_storage_get('options_prefix').'_reviews_avg'.((logistic_company_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (logistic_company_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(logistic_company_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (logistic_company_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (logistic_company_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(logistic_company_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (logistic_company_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (logistic_company_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (logistic_company_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	logistic_company_require_shortcode('trx_slider', 'logistic_company_sc_slider');
}


if (!function_exists('logistic_company_sc_slider_item')) {	
	function logistic_company_sc_slider_item($atts, $content=null) {
		if (logistic_company_in_shortcode_blogger()) return '';
		extract(logistic_company_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}

		$engine = logistic_company_storage_get_array('sc_slider_data', 'engine');
		$crop_image = logistic_company_storage_get_array('sc_slider_data', 'crop_image');
		$bg_image = logistic_company_storage_get_array('sc_slider_data', 'bg_image');
		$links = logistic_company_storage_get_array('sc_slider_data', 'links');
		$width = logistic_company_storage_get_array('sc_slider_data', 'width');
		$height = logistic_company_storage_get_array('sc_slider_data', 'height');
	
		if ($src && logistic_company_param_is_on($crop_image)) {
			$src = logistic_company_storage_get_array('sc_slider_data', 'bg_image')
				? logistic_company_get_resized_image_url($src, !empty($width) && logistic_company_strpos($width, '%')===false ? $width : null, !empty($height) && logistic_company_strpos($height, '%')===false ? $height : null)
				: logistic_company_get_resized_image_tag($src, !empty($width) && logistic_company_strpos($width, '%')===false ? $width : null, !empty($height) && logistic_company_strpos($height, '%')===false ? $height : null);
		} else if ($src && !$bg_image) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($bg_image ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($width)  ? 'width:'  . esc_attr($width)  . ';' : '')
				. (!empty($height) ? 'height:' . esc_attr($height) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($engine).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && logistic_company_param_is_on($links) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$bg_image ? $src : $content)
				. ($src && logistic_company_param_is_on($links) ? '</a>' : '')
			. '</div>';
		return apply_filters('logistic_company_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	logistic_company_require_shortcode('trx_slider_item', 'logistic_company_sc_slider_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_slider_reg_shortcodes' ) ) {
	//add_action('logistic_company_action_shortcodes_list', 'logistic_company_sc_slider_reg_shortcodes');
	function logistic_company_sc_slider_reg_shortcodes() {
	
		logistic_company_sc_map("trx_slider", array(
			"title" => esc_html__("Slider", 'logistic-company'),
			"desc" => wp_kses_data( __("Insert slider into your post (page)", 'logistic-company') ),
			"decorate" => true,
			"container" => false,
			"params" => array_merge(array(
				"engine" => array(
					"title" => esc_html__("Slider engine", 'logistic-company'),
					"desc" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'logistic-company') ),
					"value" => "swiper",
					"type" => "checklist",
					"options" => logistic_company_get_sc_param('sliders')
				),
				"align" => array(
					"title" => esc_html__("Float slider", 'logistic-company'),
					"desc" => wp_kses_data( __("Float slider to left or right side", 'logistic-company') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => logistic_company_get_sc_param('float')
				),
				"custom" => array(
					"title" => esc_html__("Custom slides", 'logistic-company'),
					"desc" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'logistic-company') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				)
				),
				function_exists('logistic_company_exists_revslider') && logistic_company_exists_revslider() ? array(
				"alias" => array(
					"title" => esc_html__("Revolution slider alias", 'logistic-company'),
					"desc" => wp_kses_data( __("Select Revolution slider to display", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('revo')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"options" => logistic_company_get_sc_param('revo_sliders')
				)) : array(), array(
				"cat" => array(
					"title" => esc_html__("Swiper: Category list", 'logistic-company'),
					"desc" => wp_kses_data( __("Select category to show post's images. If empty - select posts from any category or from IDs list", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => logistic_company_array_merge(array(0 => esc_html__('- Select category -', 'logistic-company')), logistic_company_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Swiper: Number of posts", 'logistic-company'),
					"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Swiper: Offset before select posts", 'logistic-company'),
					"desc" => wp_kses_data( __("Skip posts before select next part.", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Swiper: Post order by", 'logistic-company'),
					"desc" => wp_kses_data( __("Select desired posts sorting method", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "date",
					"type" => "select",
					"options" => logistic_company_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Swiper: Post order", 'logistic-company'),
					"desc" => wp_kses_data( __("Select desired posts order", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => logistic_company_get_sc_param('ordering')
				),
				"ids" => array(
					"title" => esc_html__("Swiper: Post IDs list", 'logistic-company'),
					"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Swiper: Show slider controls", 'logistic-company'),
					"desc" => wp_kses_data( __("Show arrows inside slider", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"pagination" => array(
					"title" => esc_html__("Swiper: Show slider pagination", 'logistic-company'),
					"desc" => wp_kses_data( __("Show bullets for switch slides", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						'no'   => esc_html__('None', 'logistic-company'),
						'yes'  => esc_html__('Dots', 'logistic-company'), 
						'full' => esc_html__('Side Titles', 'logistic-company'),
						'over' => esc_html__('Over Titles', 'logistic-company')
					)
				),
				"titles" => array(
					"title" => esc_html__("Swiper: Show titles section", 'logistic-company'),
					"desc" => wp_kses_data( __("Show section with post's title and short post's description", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						"no"    => esc_html__('Not show', 'logistic-company'),
						"slide" => esc_html__('Show/Hide info', 'logistic-company'),
						"fixed" => esc_html__('Fixed info', 'logistic-company')
					)
				),
				"descriptions" => array(
					"title" => esc_html__("Swiper: Post descriptions", 'logistic-company'),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"desc" => wp_kses_data( __("Show post's excerpt max length (characters)", 'logistic-company') ),
					"value" => 0,
					"min" => 0,
					"max" => 1000,
					"step" => 10,
					"type" => "spinner"
				),
				"links" => array(
					"title" => esc_html__("Swiper: Post's title as link", 'logistic-company'),
					"desc" => wp_kses_data( __("Make links from post's titles", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"crop" => array(
					"title" => esc_html__("Swiper: Crop images", 'logistic-company'),
					"desc" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"autoheight" => array(
					"title" => esc_html__("Swiper: Autoheight", 'logistic-company'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => logistic_company_get_sc_param('yes_no')
				),
				"slides_per_view" => array(
					"title" => esc_html__("Swiper: Slides per view", 'logistic-company'),
					"desc" => wp_kses_data( __("Slides per view showed in this slider", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 1,
					"min" => 1,
					"max" => 6,
					"step" => 1,
					"type" => "spinner"
				),
				"slides_space" => array(
					"title" => esc_html__("Swiper: Space between slides", 'logistic-company'),
					"desc" => wp_kses_data( __("Size of space (in px) between slides", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"step" => 10,
					"type" => "spinner"
				),
				"interval" => array(
					"title" => esc_html__("Swiper: Slides change interval", 'logistic-company'),
					"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'logistic-company') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 5000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
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
			)),
			"children" => array(
				"name" => "trx_slider_item",
				"title" => esc_html__("Slide", 'logistic-company'),
				"desc" => wp_kses_data( __("Slider item", 'logistic-company') ),
				"container" => false,
				"params" => array(
					"src" => array(
						"title" => esc_html__("URL (source) for image file", 'logistic-company'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'logistic-company') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"id" => logistic_company_get_sc_param('id'),
					"class" => logistic_company_get_sc_param('class'),
					"css" => logistic_company_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'logistic_company_sc_slider_reg_shortcodes_vc' ) ) {
	//add_action('logistic_company_action_shortcodes_list_vc', 'logistic_company_sc_slider_reg_shortcodes_vc');
	function logistic_company_sc_slider_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_slider",
			"name" => esc_html__("Slider", 'logistic-company'),
			"description" => wp_kses_data( __("Insert slider", 'logistic-company') ),
			"category" => esc_html__('Content', 'logistic-company'),
			'icon' => 'icon_trx_slider',
			"class" => "trx_sc_collection trx_sc_slider",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_slider_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "engine",
					"heading" => esc_html__("Engine", 'logistic-company'),
					"description" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('sliders')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Float slider", 'logistic-company'),
					"description" => wp_kses_data( __("Float slider to left or right side", 'logistic-company') ),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom slides", 'logistic-company'),
					"description" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'logistic-company') ),
					"class" => "",
					"value" => array(esc_html__('Custom slides', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				)
				),
				function_exists('logistic_company_exists_revslider') && logistic_company_exists_revslider() ? array(
				array(
					"param_name" => "alias",
					"heading" => esc_html__("Revolution slider alias", 'logistic-company'),
					"description" => wp_kses_data( __("Select Revolution slider to display", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'engine',
						'value' => array('revo')
					),
					"value" => array_flip(logistic_company_array_merge(array('none' => esc_html__('- Select slider -', 'logistic-company')), logistic_company_get_sc_param('revo_sliders'))),
					"type" => "dropdown"
				)) : array(), array(
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", 'logistic-company'),
					"description" => wp_kses_data( __("Select category. If empty - show posts from any category or from IDs list", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(logistic_company_array_merge(array(0 => esc_html__('- Select category -', 'logistic-company')), logistic_company_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Swiper: Number of posts", 'logistic-company'),
					"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Swiper: Offset before select posts", 'logistic-company'),
					"description" => wp_kses_data( __("Skip posts before select next part.", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Swiper: Post sorting", 'logistic-company'),
					"description" => wp_kses_data( __("Select desired posts sorting method", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Swiper: Post order", 'logistic-company'),
					"description" => wp_kses_data( __("Select desired posts order", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(logistic_company_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Swiper: Post IDs list", 'logistic-company'),
					"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'logistic-company') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Swiper: Show slider controls", 'logistic-company'),
					"description" => wp_kses_data( __("Show arrows inside slider", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pagination",
					"heading" => esc_html__("Swiper: Show slider pagination", 'logistic-company'),
					"description" => wp_kses_data( __("Show bullets or titles to switch slides", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"std" => "no",
					"value" => array(
							esc_html__('None', 'logistic-company') => 'no',
							esc_html__('Dots', 'logistic-company') => 'yes', 
							esc_html__('Side Titles', 'logistic-company') => 'full',
							esc_html__('Over Titles', 'logistic-company') => 'over'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "titles",
					"heading" => esc_html__("Swiper: Show titles section", 'logistic-company'),
					"description" => wp_kses_data( __("Show section with post's title and short post's description", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(
							esc_html__('Not show', 'logistic-company') => "no",
							esc_html__('Show/Hide info', 'logistic-company') => "slide",
							esc_html__('Fixed info', 'logistic-company') => "fixed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "descriptions",
					"heading" => esc_html__("Swiper: Post descriptions", 'logistic-company'),
					"description" => wp_kses_data( __("Show post's excerpt max length (characters)", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Swiper: Post's title as link", 'logistic-company'),
					"description" => wp_kses_data( __("Make links from post's titles", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Titles as a links', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "crop",
					"heading" => esc_html__("Swiper: Crop images", 'logistic-company'),
					"description" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Crop images', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Swiper: Autoheight", 'logistic-company'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'logistic-company') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "slides_per_view",
					"heading" => esc_html__("Swiper: Slides per view", 'logistic-company'),
					"description" => wp_kses_data( __("Slides per view showed in this slider", 'logistic-company') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Swiper: Space between slides", 'logistic-company'),
					"description" => wp_kses_data( __("Size of space (in px) between slides", 'logistic-company') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Swiper: Slides change interval", 'logistic-company'),
					"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'logistic-company') ),
					"group" => esc_html__('Details', 'logistic-company'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "5000",
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_slider_item",
			"name" => esc_html__("Slide", 'logistic-company'),
			"description" => wp_kses_data( __("Slider item - single slide", 'logistic-company') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_slider_item',
			"class" => "trx_sc_single trx_sc_slider_item",
			"as_child" => array('only' => 'trx_slider'),
			"as_parent" => array('except' => 'trx_slider'),
			"params" => array(
				array(
					"param_name" => "src",
					"heading" => esc_html__("URL (source) for image file", 'logistic-company'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'logistic-company') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				logistic_company_get_vc_param('id'),
				logistic_company_get_vc_param('class'),
				logistic_company_get_vc_param('css')
			)
		) );
		
		class WPBakeryShortCode_Trx_Slider extends LOGISTIC_COMPANY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Slider_Item extends LOGISTIC_COMPANY_VC_ShortCodeSingle {}
	}
}
?>