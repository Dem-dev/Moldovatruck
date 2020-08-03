<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('logistic_company_wp_gdpr_compliance_theme_setup')) {
    add_action( 'logistic_company_action_before_init_theme', 'logistic_company_wp_gdpr_compliance_theme_setup', 1 );
    function logistic_company_wp_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'logistic_company_filter_required_plugins', 'logistic_company_wp_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'logistic_company_exists_wp_gdpr_compliance' ) ) {
    function logistic_company_exists_wp_gdpr_compliance() {
        return defined( 'WP_GDPR_Compliance_VERSION' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'logistic_company_wp_gdpr_compliance_required_plugins' ) ) {
    //add_filter('logistic_company_filter_required_plugins',    'logistic_company_wp_gdpr_compliance_required_plugins');
    function logistic_company_wp_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp_gdpr_compliance', (array)logistic_company_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'logistic-company'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}
