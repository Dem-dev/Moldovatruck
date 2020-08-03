<?php
/**
 * Logistic Company Framework: messages subsystem
 *
 * @package	logistic_company
 * @since	logistic_company 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('logistic_company_messages_theme_setup')) {
	add_action( 'logistic_company_action_before_init_theme', 'logistic_company_messages_theme_setup' );
	function logistic_company_messages_theme_setup() {
		// Core messages strings
		add_filter('logistic_company_filter_localize_script', 'logistic_company_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('logistic_company_get_error_msg')) {
	function logistic_company_get_error_msg() {
		return logistic_company_storage_get('error_msg');
	}
}

if (!function_exists('logistic_company_set_error_msg')) {
	function logistic_company_set_error_msg($msg) {
		$msg2 = logistic_company_get_error_msg();
		logistic_company_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('logistic_company_get_success_msg')) {
	function logistic_company_get_success_msg() {
		return logistic_company_storage_get('success_msg');
	}
}

if (!function_exists('logistic_company_set_success_msg')) {
	function logistic_company_set_success_msg($msg) {
		$msg2 = logistic_company_get_success_msg();
		logistic_company_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('logistic_company_get_notice_msg')) {
	function logistic_company_get_notice_msg() {
		return logistic_company_storage_get('notice_msg');
	}
}

if (!function_exists('logistic_company_set_notice_msg')) {
	function logistic_company_set_notice_msg($msg) {
		$msg2 = logistic_company_get_notice_msg();
		logistic_company_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('logistic_company_set_system_message')) {
	function logistic_company_set_system_message($msg, $status='info', $hdr='') {
		update_option(logistic_company_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('logistic_company_get_system_message')) {
	function logistic_company_get_system_message($del=false) {
		$msg = get_option(logistic_company_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			logistic_company_del_system_message();
		return $msg;
	}
}

if (!function_exists('logistic_company_del_system_message')) {
	function logistic_company_del_system_message() {
		delete_option(logistic_company_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('logistic_company_messages_localize_script')) {
	//Handler of add_filter('logistic_company_filter_localize_script', 'logistic_company_messages_localize_script');
	function logistic_company_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'logistic-company'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'logistic-company'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'logistic-company'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'logistic-company'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'logistic-company'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'logistic-company'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'logistic-company'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'logistic-company'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'logistic-company'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'logistic-company'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'logistic-company'),
			'error_global'		=> esc_html__('Global error text', 'logistic-company'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'logistic-company'),
			'name_long'			=> esc_html__('Too long name', 'logistic-company'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'logistic-company'),
			'email_long'		=> esc_html__('Too long email address', 'logistic-company'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'logistic-company'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'logistic-company'),
			'subject_long'		=> esc_html__('Too long subject', 'logistic-company'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'logistic-company'),
			'text_long'			=> esc_html__('Too long message text', 'logistic-company'),
			'send_complete'		=> esc_html__("Send message complete!", 'logistic-company'),
			'send_error'		=> esc_html__('Transmit failed!', 'logistic-company'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'logistic-company'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'logistic-company'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'logistic-company'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'logistic-company'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'logistic-company'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'logistic-company'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'logistic-company'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'logistic-company'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'logistic-company'),
			'editor_caption_close'	=> esc_html__('Close', 'logistic-company')
			);
		return $vars;
	}
}
?>