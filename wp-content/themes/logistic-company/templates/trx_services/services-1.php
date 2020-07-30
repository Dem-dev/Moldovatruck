<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'logistic_company_template_services_1_theme_setup' ) ) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_template_services_1_theme_setup', 1 );
	function logistic_company_template_services_1_theme_setup() {
		logistic_company_add_template(array(
			'layout' => 'services-1',
			'template' => 'services-1',
			'mode'   => 'services',
			'title'  => esc_html__('Services /Style 1/', 'logistic-company'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'logistic-company'),
			'w'		 => 370,
			'h'		 => 209
		));
	}
}

// Template output
if ( !function_exists( 'logistic_company_template_services_1_output' ) ) {
	function logistic_company_template_services_1_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (logistic_company_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!logistic_company_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(logistic_company_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
					<div class="sc_services_item_featured post_featured">
						<?php
						logistic_company_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(logistic_company_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
				<div class="sc_services_item_content">
					<?php
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h5 class="sc_services_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php logistic_company_show_layout($post_data['post_title']); ?></a></h5><?php
						} else {
							?><h5 class="sc_services_item_title"><?php logistic_company_show_layout($post_data['post_title']); ?></h5><?php
						}
					}
					?>

					<div class="sc_services_item_description">
						<?php
                        if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
                            echo '<a href="'.esc_url($post_data['post_link']).'">';
                        }
						if ($post_data['post_protected']) {
							logistic_company_show_layout($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(logistic_company_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : logistic_company_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
						}
                        if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
                            echo '</a>';
                        }
						?>
					</div>
				</div>
			</div>
		<?php
		if (logistic_company_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>