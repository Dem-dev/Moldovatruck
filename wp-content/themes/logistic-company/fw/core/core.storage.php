<?php
/**
 * Logistic Company Framework: theme variables storage
 *
 * @package	logistic_company
 * @since	logistic_company 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('logistic_company_storage_get')) {
	function logistic_company_storage_get($var_name, $default='') {
		global $LOGISTIC_COMPANY_STORAGE;
		return isset($LOGISTIC_COMPANY_STORAGE[$var_name]) ? $LOGISTIC_COMPANY_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('logistic_company_storage_set')) {
	function logistic_company_storage_set($var_name, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		$LOGISTIC_COMPANY_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('logistic_company_storage_empty')) {
	function logistic_company_storage_empty($var_name, $key='', $key2='') {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($LOGISTIC_COMPANY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($LOGISTIC_COMPANY_STORAGE[$var_name][$key]);
		else
			return empty($LOGISTIC_COMPANY_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('logistic_company_storage_isset')) {
	function logistic_company_storage_isset($var_name, $key='', $key2='') {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key]);
		else
			return isset($LOGISTIC_COMPANY_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('logistic_company_storage_inc')) {
	function logistic_company_storage_inc($var_name, $value=1) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (empty($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = 0;
		$LOGISTIC_COMPANY_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('logistic_company_storage_concat')) {
	function logistic_company_storage_concat($var_name, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (empty($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = '';
		$LOGISTIC_COMPANY_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('logistic_company_storage_get_array')) {
	function logistic_company_storage_get_array($var_name, $key, $key2='', $default='') {
		global $LOGISTIC_COMPANY_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key]) ? $LOGISTIC_COMPANY_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key][$key2]) ? $LOGISTIC_COMPANY_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('logistic_company_storage_set_array')) {
	function logistic_company_storage_set_array($var_name, $key, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if ($key==='')
			$LOGISTIC_COMPANY_STORAGE[$var_name][] = $value;
		else
			$LOGISTIC_COMPANY_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('logistic_company_storage_set_array2')) {
	function logistic_company_storage_set_array2($var_name, $key, $key2, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key])) $LOGISTIC_COMPANY_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$LOGISTIC_COMPANY_STORAGE[$var_name][$key][] = $value;
		else
			$LOGISTIC_COMPANY_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('logistic_company_storage_set_array_after')) {
	function logistic_company_storage_set_array_after($var_name, $after, $key, $value='') {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if (is_array($key))
			logistic_company_array_insert_after($LOGISTIC_COMPANY_STORAGE[$var_name], $after, $key);
		else
			logistic_company_array_insert_after($LOGISTIC_COMPANY_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('logistic_company_storage_set_array_before')) {
	function logistic_company_storage_set_array_before($var_name, $before, $key, $value='') {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if (is_array($key))
			logistic_company_array_insert_before($LOGISTIC_COMPANY_STORAGE[$var_name], $before, $key);
		else
			logistic_company_array_insert_before($LOGISTIC_COMPANY_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('logistic_company_storage_push_array')) {
	function logistic_company_storage_push_array($var_name, $key, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($LOGISTIC_COMPANY_STORAGE[$var_name], $value);
		else {
			if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key])) $LOGISTIC_COMPANY_STORAGE[$var_name][$key] = array();
			array_push($LOGISTIC_COMPANY_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('logistic_company_storage_pop_array')) {
	function logistic_company_storage_pop_array($var_name, $key='', $defa='') {
		global $LOGISTIC_COMPANY_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($LOGISTIC_COMPANY_STORAGE[$var_name]) && is_array($LOGISTIC_COMPANY_STORAGE[$var_name]) && count($LOGISTIC_COMPANY_STORAGE[$var_name]) > 0) 
				$rez = array_pop($LOGISTIC_COMPANY_STORAGE[$var_name]);
		} else {
			if (isset($LOGISTIC_COMPANY_STORAGE[$var_name][$key]) && is_array($LOGISTIC_COMPANY_STORAGE[$var_name][$key]) && count($LOGISTIC_COMPANY_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($LOGISTIC_COMPANY_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('logistic_company_storage_inc_array')) {
	function logistic_company_storage_inc_array($var_name, $key, $value=1) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if (empty($LOGISTIC_COMPANY_STORAGE[$var_name][$key])) $LOGISTIC_COMPANY_STORAGE[$var_name][$key] = 0;
		$LOGISTIC_COMPANY_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('logistic_company_storage_concat_array')) {
	function logistic_company_storage_concat_array($var_name, $key, $value) {
		global $LOGISTIC_COMPANY_STORAGE;
		if (!isset($LOGISTIC_COMPANY_STORAGE[$var_name])) $LOGISTIC_COMPANY_STORAGE[$var_name] = array();
		if (empty($LOGISTIC_COMPANY_STORAGE[$var_name][$key])) $LOGISTIC_COMPANY_STORAGE[$var_name][$key] = '';
		$LOGISTIC_COMPANY_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('logistic_company_storage_call_obj_method')) {
	function logistic_company_storage_call_obj_method($var_name, $method, $param=null) {
		global $LOGISTIC_COMPANY_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($LOGISTIC_COMPANY_STORAGE[$var_name]) ? $LOGISTIC_COMPANY_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($LOGISTIC_COMPANY_STORAGE[$var_name]) ? $LOGISTIC_COMPANY_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('logistic_company_storage_get_obj_property')) {
	function logistic_company_storage_get_obj_property($var_name, $prop, $default='') {
		global $LOGISTIC_COMPANY_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($LOGISTIC_COMPANY_STORAGE[$var_name]->$prop) ? $LOGISTIC_COMPANY_STORAGE[$var_name]->$prop : $default;
	}
}
?>