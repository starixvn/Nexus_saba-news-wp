<?php

namespace Sphere\PostViews;

class Helper
{
	/**
	 * Create a token with a custom life specified. Note: Life isn't really the exact
	 * seconds (unlike an expiry), as the concept is based on ticks of the half-time. 
	 * 
	 * Example: A 3 day day life may still expire in 1.5 days depending if it were created 
	 * at the end range of the tick. Or a 1 day hash may expire in 12 hours.
	 * 
	 * @author ThemeSphere
	 * 
	 * @param string $action  An ID for the action.
	 * @param string $life    Defaults to 3 days.
	 * @return string
	 */
	public static function create_token($action, $life = '')
	{
		$key = self::nonce_tick($life) . '|' . $action;
		return substr(wp_hash($key, 'nonce'), -12, 10);
	}

	/**
	 * Returns the time-dependent variable for nonce creation.
	 *
	 * A nonce has a lifespan of two ticks. See notes in create_token().
	 * 
	 * @author ThemeSphere
	 * 
	 * @param int $life
	 * @return int
	 */
	public static function nonce_tick($life)
	{
		$life = $life ?: 3 * DAY_IN_SECONDS;
		return ceil(time() / ($life / 2));
	}

	/**
	 * Verify an nonce token.
	 *
	 * @author ThemeSphere
	 * 
	 * @param string $nonce
	 * @param string $action
	 * @param string $life
	 * @return bool|int
	 */
	public static function verify_token($nonce, $action, $life = '')
	{
		if (empty($nonce)) {
			return false;
		}

		$tick = self::nonce_tick($life);

		// Nonce generated 0 - (life/2) hours ago.
		$expected = substr(wp_hash($tick . '|' . $action, 'nonce'), -12, 10);
		if (hash_equals($expected, $nonce)) {
			return 1;
		}

		// Nonce generated (life/2) - life hours ago.
		$expected = substr(wp_hash(($tick - 1) . '|' . $action, 'nonce'), -12, 10);
		if (hash_equals($expected, $nonce)) {
			return 2;
		}

		return false;
	}

	/**
	 * Converts a number into a short version, eg: 1000 -> 1k
	 *
	 * @see     https://gist.github.com/RadGH/84edff0cc81e6326029c
	 * @param   int
	 * @param   int
	 * @return  mixed   string|bool
	 */
	public static function prettify_number($number, $precision = 1)
	{
		if (!is_numeric($number))
			return false;

		if ($number < 900) {
			// 0 - 900
			$n_format = number_format($number, $precision);
			$suffix = '';
		} elseif ($number < 900000) {
			// 0.9k-850k
			$n_format = number_format($number / 1000, $precision);
			$suffix = 'k';
		} elseif ($number < 900000000) {
			// 0.9m-850m
			$n_format = number_format($number / 1000000, $precision);
			$suffix = 'm';
		} elseif ($number < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($number / 1000000000, $precision);
			$suffix = 'b';
		} else {
			// 0.9t+
			$n_format = number_format($number / 1000000000000, $precision);
			$suffix = 't';
		}

		// Remove unnecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
		// Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ($precision > 0) {
			$dotzero = '.' . str_repeat('0', $precision);
			$n_format = str_replace($dotzero, '', $n_format);
		}

		return $n_format . $suffix;
	}

	/**
	 * Checks for valid date.
	 *
	 * @param   string   $date
	 * @param   string   $format
	 * @return  bool
	 */
	public static function is_valid_date($date = null, $format = 'Y-m-d')
	{
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	/**
	 * Returns an array of dates between two dates.
	 *
	 * @param   string   $start_date
	 * @param   string   $end_date
	 * @param   string   $format
	 * @return  array|bool
	 */
	public static function get_date_range($start_date = null, $end_date = null, $format = 'Y-m-d')
	{
		if (
			self::is_valid_date($start_date, $format)
			&& self::is_valid_date($end_date, $format)
		) {
			$dates = [];

			$begin = new \DateTime($start_date, new \DateTimeZone(Helper::get_timezone()));
			$end = new \DateTime($end_date, new \DateTimeZone(Helper::get_timezone()));

			if ($begin < $end) {
				while ($begin <= $end) {
					$dates[] = $begin->format($format);
					$begin->modify('+1 day');
				}
			} else {
				while ($begin >= $end) {
					$dates[] = $begin->format($format);
					$begin->modify('-1 day');
				}
			}

			return $dates;
		}

		return false;
	}

	/**
	 * Returns server date.
	 *
	 * @access   private
	 * @return   string
	 */
	public static function curdate()
	{
		return current_time('Y-m-d', false);
	}

	/**
	 * Returns mysql datetime.
	 *
	 * @access   private
	 * @return   string
	 */
	public static function now()
	{
		return current_time('mysql');
	}

	/**
	 * Returns current timestamp.
	 *
	 * @return  string
	 */
	public static function timestamp()
	{
		// current_datetime() is WP 5.3+
		return (function_exists('current_datetime')) ? current_datetime()->getTimestamp() : current_time('timestamp');
	}

	/**
	 * Checks whether a string is a valid timestamp.
	 *
	 * @param   string  $string
	 * @return  bool
	 */
	public static function is_timestamp($string)
	{
		if (
			(is_int($string) || ctype_digit($string))
			&& strtotime(date('Y-m-d H:i:s', $string)) === (int) $string
		) {
			return true;
		}

		return false;
	}

	/**
	 * Returns site's timezone.
	 *
	 * Code borrowed from Rarst's awesome WpDateTime class: https://github.com/Rarst/wpdatetime
	 *
	 * @return  string
	 */
	public static function get_timezone()
	{
		$timezone_string = get_option('timezone_string');

		if (!empty($timezone_string)) {
			return $timezone_string;
		}

		$offset = get_option('gmt_offset');
		$sign = $offset < 0 ? '-' : '+';
		$hours = (int) $offset;
		$minutes = abs(($offset - (int) $offset) * 60);
		$offset = sprintf('%s%02d:%02d', $sign, abs($hours), $minutes);

		return $offset;
	}

	/**
	 * Debug function.
	 *
	 * @param   mixed $v variable to display with var_dump()
	 * @param   mixed $v,... unlimited optional number of variables to display with var_dump()
	 */
	public static function debug($v)
	{
		if (!defined('SPV_DEBUG') || !SPV_DEBUG)
			return;

		foreach (func_get_args() as $arg) {
			print "<pre>";
			var_dump($arg);
			print "</pre>";
		}
	}

	/**
	 * Gets post/page ID if current page is singular
	 */
	public static function is_single()
	{
		// $trackable = [];
		// $registered_post_types = get_post_types(['public' => true], 'names');

		// foreach ($registered_post_types as $post_type) {
		// 	$trackable[] = $post_type;
		// }

		$trackable = ['post'];
		$trackable = apply_filters('sphere/post_views/post_types', $trackable);

		if (
			is_singular($trackable)
			&& !is_front_page()
			&& !is_preview()
			&& !is_trackback()
			&& !is_feed()
			&& !is_robots()
			&& !is_customize_preview()
		) {
			return get_queried_object_id();
		}

		return false;
	}
}
