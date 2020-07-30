<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move logistic_company_set_post_views to the javascript - counter will work under cache system
	if (logistic_company_get_custom_option('use_ajax_views_counter')=='no') {
		logistic_company_set_post_views(get_the_ID());
	}

	logistic_company_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !logistic_company_param_is_off(logistic_company_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>