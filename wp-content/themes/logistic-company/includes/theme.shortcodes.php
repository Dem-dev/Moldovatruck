<?php
if (!function_exists('logistic_company_theme_shortcodes_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_theme_shortcodes_setup', 1 );
	function logistic_company_theme_shortcodes_setup() {
		add_filter('logistic_company_filter_googlemap_styles', 'logistic_company_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'logistic_company_theme_shortcodes_googlemap_styles' ) ) {
	function logistic_company_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'logistic-company');
		$list['greyscale']	= esc_html__('Greyscale', 'logistic-company');
		$list['inverse']	= esc_html__('Inverse', 'logistic-company');
		$list['apple']		= esc_html__('Apple', 'logistic-company');
		return $list;
	}
}
?>