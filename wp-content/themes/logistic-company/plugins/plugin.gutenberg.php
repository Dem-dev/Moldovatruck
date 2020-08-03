<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('logistic_company_gutenberg_theme_setup')) {
    add_action( 'logistic_company_action_before_init_theme', 'logistic_company_gutenberg_theme_setup', 1 );
    function logistic_company_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'logistic_company_filter_required_plugins', 'logistic_company_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'logistic_company_exists_gutenberg' ) ) {
    function logistic_company_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'logistic_company_gutenberg_required_plugins' ) ) {
    //add_filter('logistic_company_filter_required_plugins',    'logistic_company_gutenberg_required_plugins');
    function logistic_company_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)logistic_company_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'logistic-company'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}