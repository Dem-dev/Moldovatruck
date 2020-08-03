<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'logistic_company_exists_wpml' ) ) {
	function logistic_company_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'logistic_company_wpml_required_plugins' ) ) {
	//Handler of add_filter('logistic_company_filter_required_plugins',	'logistic_company_wpml_required_plugins');
	function logistic_company_wpml_required_plugins($list=array()) {
		if (in_array('wpml', logistic_company_storage_get('required_plugins'))) {
			$path = logistic_company_get_file_dir('plugins/install/wpml.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPML', 'logistic-company'),
					'slug' 		=> 'wpml',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>