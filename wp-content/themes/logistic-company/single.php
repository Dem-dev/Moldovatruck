<?php
/**
 * Single post
 */
get_header(); 

$single_style = logistic_company_storage_get('single_style');
if (empty($single_style)) $single_style = logistic_company_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	logistic_company_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !logistic_company_param_is_off(logistic_company_get_custom_option('show_sidebar_main')),
			'content' => logistic_company_get_template_property($single_style, 'need_content'),
			'terms_list' => logistic_company_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>