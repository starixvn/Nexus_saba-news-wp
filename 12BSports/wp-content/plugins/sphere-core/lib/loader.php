<?php

namespace Bunyad\Lib;

/**
 * Autoloader for loading classes off defined namespaces or a class map.
 */
class Loader
{
	public $class_map;
	public $namespaces = [];
	protected $is_theme = false;

	public function __construct($namespaces = null, $prepend = false)
	{
		if (is_array($namespaces)) {
			$this->namespaces = $namespaces;
		}

		spl_autoload_register([$this, 'load'], true, $prepend);
	}

	/**
	 * Set if the loader is being used in a theme or not.
	 */
	public function set_is_theme($value = true)
	{
		$this->is_theme = $value;
		return $this;
	}

	/**
	 * Autoloader the class either using a class map or via conversion of 
	 * class name to file.
	 * 
	 * @param string $class
	 */
	public function load($class)
	{
		if (isset($this->class_map[$class])) {
			$file = $this->class_map[$class];
		}
		else {
			foreach ($this->namespaces as $namespace => $options) {
				if (strpos($class, $namespace) !== false) {
					$file = $this->get_file_by_namespace($class, $namespace, $options);
					break;
				}
			}
		}

		if (!empty($file)) {
			require_once $file;
		}
	}

	/**
	 * Locate file path for the provided class, given a namespace and directory.
	 *
	 * @param string $class         Fully qualified class name.
	 * @param string $namespace     Namespace associated with the paths.
	 * @param string|array $options Either the string path or an array of options.
	 * @return string|boolean
	 */
	public function get_file_by_namespace($class, $namespace, $options)
	{
		$path = $options;
		if (is_array($options)) {
			$path = $options['paths'];
		}

		if (is_array($path)) {
			foreach ($path as $dir) {
				$options['paths'] = $dir;
				$file = $this->get_file_by_namespace($class, $namespace, $options);

				// Found. Don't have to search in alternate paths.
				if ($file) {
					return $file;
				}
			}
		}

		return $this->get_file_path(
			$class, $namespace, $path, $options['search_reverse'] ?? false
		);
	}

	/**
	 * Get file path to include.
	 * 
	 * @param string $class
	 * @param string $prefix
	 * @param string $path
	 * @param boolean $search_reverse  Set true to set search order in reverse of default.
	 * 
	 * Examples:
	 * 
	 *  Bunyad_Theme_Foo_Bar to inc/foo/bar/bar.php (fallback to inc/foo/bar.php)
	 *  Bunyad\Blocks\FooBar to blocks/foo-bar.php (fallback to blocks/foo-bar/foo-bar.php)
	 * 
	 * @return string  Relative path to the file from the theme dir
	 */
	public function get_file_path($class, $prefix = '', $path = '', $search_reverse = false) 
	{
		// Enable reverse search order for non-namespaced classes.
		if (!$search_reverse && strpos($class, '\\') === false) {
			$search_reverse = true;
		}

		// Remove namespace and convert underscore as a namespace delim.
		$class = str_replace($prefix, '', $class);
		$class = str_replace('_', '\\', $class);
		
		// Split to convert CamelCase.
		$parts = explode('\\', $class);
		foreach ($parts as $key => $part) {
			
			$test = substr($part, 1); 
					
			// Convert CamelCase to Camel-Case
			if (strtolower($test) !== $test) {
				$part = preg_replace('/(.)(?=[A-Z])/u', '$1-', $part);
			}

			$parts[$key] = $part;
		}

		$name = strtolower(array_pop($parts));
		$path = $path . '/' . strtolower(
			implode('/', $parts)
		);
		$path = trailingslashit($path);

		// Preferred and fallback file path.
		$pref_file = $path . "{$name}.php";
		$alt_file  = $path . "{$name}/{$name}.php";

		// Swap file search order.
		if ($search_reverse) {
			list($pref_file, $alt_file) = [$alt_file, $pref_file];
		}

		// Try with directory path pattern first.
		if (file_exists($pref_file)) {
			return $pref_file;
		}
		else if (file_exists($alt_file)) {
			return $alt_file;
		}

		return false;

		// DEBUG: 
		// trigger_error('Class file not found: ' . $class . " - Pref: {$pref_file} - Alt: {$alt_file} ");
	}
}