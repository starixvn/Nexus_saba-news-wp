<?php

namespace Sphere\PostViews;

/**
 * Obtains translation data from objects.
 * 
 * @author Hector Cabrera <me@cabrerahector.com>
 * @author ThemeSphere
 */
class Translate
{
	/**
	 * Default language code.
	 *
	 * @var string
	 */
	private $default_language;

	/**
	 * Current language code.
	 * 
	 * @var string
	 */
	private $current_language;

	/**
	 * Retrieves the code of the default language.
	 *
	 * @return string|null
	 */
	public function get_default_language()
	{
		if (!$this->default_language) {
			$this->default_language = 
				function_exists('pll_default_language')
					? pll_default_language()
					: apply_filters('wpml_default_language', NULL);
		}

		return $this->default_language;
	}

	/**
	 * Retrieves the code of the currently active language.
	 *
	 * @return string|null
	 */
	public function get_current_language()
	{
		if (!$this->current_language) {
			$this->current_language = 
				function_exists('pll_current_language')
					? pll_current_language() 
					: apply_filters('wpml_current_language', NULL);
		}

		return $this->current_language;
	}

	/**
	 * Sets the code of the currently active language.

	 * @return string|null
	 */
	public function set_current_language($code = null)
	{
		$this->current_language = $code;
	}

	/**
	 * Gets language locale.
	 *
	 * @param   string      $lang   Language code (eg. 'es')
	 * @return  string|bool
	 */
	public function get_locale($lang = null)
	{
		// Polylang support
		if (function_exists('PLL')) {
			$lang_object = PLL()->model->get_language($lang);
			if ($lang_object && isset($lang_object->locale)) {
				return $lang_object->locale;
			}
			
		} else {

			// WPML support
			global $sitepress;
			if (is_object($sitepress) && method_exists($sitepress, 'get_locale_from_language_code')) {
				return $sitepress->get_locale_from_language_code($lang);
			}
		}

		return false;
	}

	/**
	 * Retrieves the ID of an object.
	 *
	 * @param    integer    $object_id
	 * @param    string     $object_type
	 * @param    boolean    $return_original_if_missing
	 * @param    string     $lang_code
	 * @return   integer
	 */
	public function get_object_id($object_id = null, $object_type = '', $return_original_if_missing = true, $lang_code = null)
	{
		$object_type = $object_type ?: get_post_type();
		if (!$object_type) {
			$object_type = 'post';
		}

		return apply_filters(
			'wpml_object_id',
			$object_id,
			$object_type,
			$return_original_if_missing,
			null == $lang_code ? $this->get_current_language() : $lang_code
		);
	}

	/**
	 * Translates URL.
	 *
	 * @param   string      $original_permalink
	 * @param   string      $lang
	 * @return  string
	 */
	public function url($original_permalink, $lang)
	{
		return apply_filters('wpml_permalink', $original_permalink, $lang);
	}

	/**
	 * Retrieves the language code of an object.
	 *
	 * @param    integer    $object_id
	 * @param    string     $object_type
	 * @return   string|null
	 */
	public function get_object_lang_code($object_id = null, $object_type = 'post')
	{
		return apply_filters(
			'wpml_element_language_code',
			null,
			[
				'element_id' => $object_id,
				'element_type' => $object_type
			]
		);
	}
}
