<?php

namespace Sphere\PostViews;

/**
 * A minimal DI Container.
 * 
 * Example usage:
 * 
 * <code>
 *   $container = new Container;
 *   $container['service'] = new class;
 *   $container['service'] = 'className';
 *   $container['service'] = function() {}
 *   $container['service'] = $container->shared('className', [...]);
 * </code>
 * 
 * @author ThemeSphere
 */
class Container implements \ArrayAccess
{
	private $container = [];

	/**
	 * Creates a single instance class for container.
	 * 
	 * @param string     $class  Fully-qualifed class name
	 * @param array|null $args   Bound args to pass to constructor
	 */
	public function shared($class, $args = null) 
	{
		return function($fresh = false) use ($class, $args) {
			static $object;

			if (!$object || $fresh) {

				if (!$args) {
					$object = new $class;
				}
				else {
					$ref = new \ReflectionClass($class);
					$object = $ref->newInstanceArgs($args);
				}
			}

			return $object;
		};
	}

	/**
	 * Gets an object from container.
	 */
	public function get($name, $args = []) 
	{
		if (!isset($this->container[$name])) {
			throw new \InvalidArgumentException("No container exists with key '{$name}'");
		}

		$object = $this->container[$name];

		if (is_callable($object)) {
			return call_user_func_array($object, $args);
		}
		else if (is_string($object)) {
			$object = new $object;
		}

		return $object;
	}

	public function offsetSet($offset, $value): void
	{
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset): bool
	{
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset): void
	{
		unset($this->container[$offset]);
	}

	// Cannot use mixed (8.0+) return type or object (7.2+)
	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}
}