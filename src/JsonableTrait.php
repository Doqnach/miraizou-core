<?php
	/**
	 * @author Doqnach
	 */

	namespace Miraizou\Core;

	/**
	 * Trait to help convert a class to JSON, picking up all public properties and 'get' methods
	 *
	 * @package Miraizou\Core
	 */
	trait JsonableTrait
	{
		public function toJSON()
		{
			$json = array();
			$ref = new \ReflectionObject($this);
			foreach($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
				$func = $method->getShortName();
				// only do 'get' methods that has no paramters (that have no default value)
				if (0 === strpos($func, 'get')) {
					$params = $method->getParameters();
					if (count($params) > 0) {
						foreach($params as $param) {
							if (false === $param->isDefaultValueAvailable()) {
								continue 2;
							}
						}
					}
					$json[lcfirst(substr($func, 3))] = $method->invoke($this);
				}
			}
			foreach($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
				if (false === array_key_exists($prop->getName(), $json)) {
					$json[$prop->getName()] = $prop->getValue($this);
				}
			}
			return $json;
		}
	}
