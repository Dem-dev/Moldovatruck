<?php
/**
 * Logistic Company Framework: strings manipulations
 *
 * @package	logistic_company
 * @since	logistic_company 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'LOGISTIC_COMPANY_MULTIBYTE' ) ) define( 'LOGISTIC_COMPANY_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('logistic_company_strlen')) {
	function logistic_company_strlen($text) {
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('logistic_company_strpos')) {
	function logistic_company_strpos($text, $char, $from=0) {
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('logistic_company_strrpos')) {
	function logistic_company_strrpos($text, $char, $from=0) {
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('logistic_company_substr')) {
	function logistic_company_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = logistic_company_strlen($text)-$from;
		}
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('logistic_company_strtolower')) {
	function logistic_company_strtolower($text) {
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('logistic_company_strtoupper')) {
	function logistic_company_strtoupper($text) {
		return LOGISTIC_COMPANY_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('logistic_company_strtoproper')) {
	function logistic_company_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<logistic_company_strlen($text); $i++) {
			$ch = logistic_company_substr($text, $i, 1);
			$rez .= logistic_company_strpos(' .,:;?!()[]{}+=', $last)!==false ? logistic_company_strtoupper($ch) : logistic_company_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('logistic_company_strrepeat')) {
	function logistic_company_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('logistic_company_strshort')) {
	function logistic_company_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= logistic_company_strlen($str)) 
			return strip_tags($str);
		$str = logistic_company_substr(strip_tags($str), 0, $maxlength - logistic_company_strlen($add));
		$ch = logistic_company_substr($str, $maxlength - logistic_company_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = logistic_company_strlen($str) - 1; $i > 0; $i--)
				if (logistic_company_substr($str, $i, 1) == ' ') break;
			$str = trim(logistic_company_substr($str, 0, $i));
		}
		if (!empty($str) && logistic_company_strpos(',.:;-', logistic_company_substr($str, -1))!==false) $str = logistic_company_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('logistic_company_strclear')) {
	function logistic_company_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (logistic_company_substr($text, 0, logistic_company_strlen($open))==$open) {
					$pos = logistic_company_strpos($text, '>');
					if ($pos!==false) $text = logistic_company_substr($text, $pos+1);
				}
				if (logistic_company_substr($text, -logistic_company_strlen($close))==$close) $text = logistic_company_substr($text, 0, logistic_company_strlen($text) - logistic_company_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('logistic_company_get_slug')) {
	function logistic_company_get_slug($title) {
		return logistic_company_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('logistic_company_strmacros')) {
	function logistic_company_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('logistic_company_unserialize')) {
	function logistic_company_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>