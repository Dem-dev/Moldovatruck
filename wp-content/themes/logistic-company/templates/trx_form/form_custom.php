<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'logistic_company_template_form_custom_theme_setup' ) ) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_template_form_custom_theme_setup', 1 );
	function logistic_company_template_form_custom_theme_setup() {
		logistic_company_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'logistic-company')
			));
	}
}

// Template output
if ( !function_exists( 'logistic_company_template_form_custom_output' ) ) {
	function logistic_company_template_form_custom_output($post_options, $post_data) {
		$form_style = logistic_company_get_theme_option('input_hover');
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?>
			class="sc_input_hover_<?php echo esc_attr($form_style); ?>"
			data-formtype="<?php echo esc_attr($post_options['layout']); ?>" 
			method="post" 
			action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php
			logistic_company_sc_form_show_fields($post_options['fields']);
			logistic_company_show_layout($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>