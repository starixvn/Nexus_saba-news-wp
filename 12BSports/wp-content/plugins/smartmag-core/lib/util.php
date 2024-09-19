<?php
namespace Bunyad\Util;

/**
 * Utility PHP functions and other utils.
 */

/**
 * Insert into array after a certain key.
 * 
 * Note: Doesn't play with numeric indices in inserted arrays when 
 * dealing with associative arrays.
 *
 * @param array $array         The array to modify.
 * @param integer|string $key  Target key to insert before or after.
 * @param array $insert        Array to insert.
 * @param string $position     'before' or 'after'.
 * 
 * @return void
 */
function array_insert(&$array, $key, $insert, $position = 'after') 
{
	$index = array_search($key, array_keys($array));

	// Inserting after or before.
	if ($position === 'after') {
		$pos = false === $index ? count($array) : $index + 1;
	}
	else {
		$pos = false === $index ? 0 : $index;
	}

	// Add at beginning.
	if ($key === null || $pos < 0) {
		$pos = 0;
	}

	$array = array_merge(
		array_slice($array, 0, $pos), 
		$insert, 
		array_slice($array, $pos)
	);
}

/**
 * Modify both keys and values in a an array recursively, while preserving sort.
 *
 * @param array    $array    Original array.
 * @param callable $callback Callback function takes two params (key, value) and 
 *                           returns the new [key, value] to as an array.
 *
 * @return array
 */
function array_modify_recursive(array $array, callable $callback) 
{
	$output = [];

	foreach ($array as $key => $value) {

		if (is_array($value)) {
			$value = array_modify_recursive($value, $callback);
		}

		$new_key   = $key;
		$new_value = $value;

		// Get output from callback and use as key/value.
		$replace   = $callback($key, $value);
		if (is_array($replace)) {
			list($new_key, $new_value) = $replace;
		}

		// If callback returns false, skip.
		if ($replace !== false) {
			$output[$new_key] = $new_value;
		}
	}

	return $output;
}


/**
 * Remove prefix/suffix from set of keys, recursively.
 *
 * @param array   $array
 * @param string  $affix
 * @param boolean $is_prefix
 * @return array
 */
function deaffix_keys(array $array, $affix, $is_prefix = true, $args = []) 
{
	$args = array_replace([
		'require_affix' => false
	], $args);

	$new_array = [];

	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$val = deaffix_keys($val, $affix, $is_prefix);
		}

		// Flag to denote if an affix was found and changes made.
		$affixed = false;

		// Prefix
		if ($is_prefix && strpos($key, $affix) === 0) {
			$affixed = true;
			$key     = substr($key, strlen($affix));
		}
		elseif (!$is_prefix) {
			
			// Finding and removing a suffix.
			$offset = strlen($key) - strlen($affix);
			if (strpos($key, $affix, $offset) !== false) {
				$affixed = true;
				$key     = substr($key, 0, -strlen($affix));
			}
		}

		// Discard if affix is required.
		if ($args['require_affix'] && !$affixed) {
			continue;
		}
		
		$new_array[$key] = $val;
	}

	return $new_array;
}

/**
 * Remove affix in keys of array and pick only the affixed keys.
 *
 * @see defaffix_keys()
 * @return array
 */
function pick_deaffixed(array $array, $affix, $is_prefix = true) 
{
	return deaffix_keys($array, $affix, $is_prefix, ['require_affix' => true]);
}

/**
 * Create multiple options based on provided replacements and a template array.
 *
 * @param array $templates
 * @param array $field_types
 * @param array $options
 * @param array $config
 * @return void
 */
function repeat_options($templates, $field_types, &$options, $config = []) 
{
	$config = array_replace([

		// Main dynamic key to be replaced by $field_types[$key] in loop.
		'key'          => '{key}',

		// By default, {key} is replaced in 'name' and recursively for 'css'.
		'replace_in'   => ['css'],
		
		// Static keys to replace besides main dynamic key in {key}.
		// Replacements can also be added to $field_types[replacements]
		'replacements' => [],
	], $config);

	foreach ($field_types as $key => $type) {
		foreach ($templates as $id => $template) {

			// Skip this specific field.
			if (isset($type['skip']) && in_array($template['name'], $type['skip'])) {
				continue;
			}

			// Copy all provided data except for internal function-specific configs.
			$copy_data = array_diff_key($type, array_flip([
				'overrides', 
				'replace_in', 
				'fields_css', 
				'skip',
				'replacements'
			]));

			$to_add         = array_replace_recursive($template, $copy_data);
			$to_add['name'] = str_replace($config['key'], $key, $to_add['name']);

			// Overrides for specific keys.
			if (!empty($type['overrides']) && isset($type['overrides'][ $template['name'] ])) {
					$to_add = array_replace(
						$to_add, 
						(array) $type['overrides'][ $template['name'] ]
				);
			}

			// Override 'css' with data from 'fields_css'.
			if (!empty($type['fields_css'][$id])) {
				$to_add['css'] = $type['fields_css'][$id];
			}

			// Use substitute options.
			if (isset($template['template']) && !empty($template['template'][$key])) {

				// Not doing recursive replace as things like 'css' and 'options' have to
				// replaced, not necessarily merged.
				$to_add  = array_replace($to_add, $template['template'][$key]);

				// 'template' is intentionally preserved for a second run at replacement.
				// unset($to_add['template']);
			}

			/**
			 * All the replacements the keys in replace_in.
			 */
			if ($config['replace_in']) {
				// {key} already done above. But we need to perform for 'replacements' too.
				array_push($config['replace_in'], 'name');
			}

			// Use config replacements map and add main key from fields loop.
			$replacements = array_replace(
				$config['replacements'],
				(isset($type['replacements']) ? $type['replacements'] : []),
				[
					$config['key'] => $key
				]
			);

			$replace_keys = array_keys($replacements);
			$replace_vals = array_values($replacements);

			foreach ($config['replace_in'] as $replace) {
				if (empty($to_add[ $replace ])) {
					continue;
				}

				if (is_array($to_add[ $replace ])) {

					/**
					 * Recursively replace provided key => value map in both keys and values.
					 */
					$to_add[ $replace ] = \Bunyad\Util\array_modify_recursive($to_add[ $replace ], function($new_key, $new_val) use($replace_keys, $replace_vals) {
						$new_key = str_replace($replace_keys, $replace_vals, $new_key);

						if (is_string($new_val)) {
							$new_val = str_replace($replace_keys, $replace_vals, $new_val);
						}

						return [$new_key, $new_val];
					});
				}
				else {
					$to_add[ $replace ] = str_replace($replace_keys, $replace_vals, $to_add[ $replace ]);
				}
			}

			$options[] = $to_add;
		}
	}
}

/**
 * Format a provided number into a readable shorter version with K or M.
 *
 * @param int|float $number
 * @return int|float
 */
function readable_number($number)
{
	if ($number < 1000) {
		return $number;
	}

	if ($number < 10^6) {
		return number_format_i18n(round($number / 1000, 1)) . 'K';
	}
	
	return number_format_i18n(round($number / 10^6, 1)) . 'M';
}

/**
 * Get any callback as string.
 */
function get_as_string($callback, ...$args)
{
	if (!is_callable($callback)) {
		throw new \Exception('Invalid callback provided.');
	}

	ob_start();
	$return_value = call_user_func_array($callback, $args);
	$capture = ob_get_clean();

	if ($return_value && is_string($return_value)) {
		return $return_value;
	}

	return $capture;
}

/**
 * Remove empty string values from an array.
 *
 * @param array $array
 * @return array
 */
function filter_empty_strings($array)
{
	return array_filter($array, static function($v) {
		return $v !== '';
	});
}