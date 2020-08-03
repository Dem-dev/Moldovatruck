<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'logistic_company_template_404_theme_setup' ) ) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_template_404_theme_setup', 1 );
	function logistic_company_template_404_theme_setup() {
		logistic_company_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'logistic_company_template_404_output' ) ) {
	function logistic_company_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e( '404', 'logistic-company' ); ?></h1>
				<h2 class="page_subtitle"><?php esc_html_e('The requested page cannot be found', 'logistic-company'); ?></h2>
				<p class="page_description"><?php echo wp_kses_data( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'logistic-company'), esc_url(home_url('/')) ) ); ?></p>
				<div class="page_search"><?php if (function_exists('logistic_company_sc_search')) logistic_company_show_layout(logistic_company_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'logistic-company')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>