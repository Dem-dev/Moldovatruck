<?php 
if (is_singular()) {
	if (logistic_company_get_theme_option('use_ajax_views_counter')=='yes') {
		logistic_company_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => logistic_company_get_post_views(get_the_ID())
		));
	} else
		logistic_company_set_post_views(get_the_ID());
}
?>