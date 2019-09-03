<?php

declare(strict_types=1);

/**
 * @author Doqnach
 */

namespace Miraizou\Core;

/**
 * PHP doesn't support the language construct so we'll just close our eyes and pretend...
 *
 * // @ method static MyEnum VAL_A() VAL_A()
 * // @ method static MyEnum VAL_B() VAL_B()
 * // @ method static MyEnum VAL_C() VAL_C()
 * final class MyEnum extends \Miraizou\Core\Enum
 * {
 *     protected static $_values = array(
 *         'VAL_A' => 1,
 *         'VAL_B' => 2,
 *         'VAL_C' => 4,
 *     );
 *     protected static $_enums = array();
 * }
 *
 * e.g.: function myFunc(MyEnum $e) { ... }
 *       myFunc(MyEnum::VAL_A());
 *
 * @author Doqnach <doqnach@miraizou.net>
 * @copyright Miraizou.net: Vision of the Future
 * @package Miraizou\Core
 */
abstract class Enum implements \JsonSerializable
{
	/** @var string */
	private $_name;
	/** @var mixed */
	private $_value;
	/** @var array */
	const _values = array();
	/** @var Enum[] */
	protected static $_enums = array();

	/**
	 * Enum constructor.
	 * @param $name
	 * @throws \UnexpectedValueException
	 */
	protected function __construct($name)
	{
		$this->_name = $name;
		if (array_key_exists($name, static::_values) === true) {
			$this->_value = static::_values[$name];
		} else {
			throw new \UnexpectedValueException('Unknown value `' . $name . '` in Enum `' . static::class . '`');
		}
	}

	public function __get($name)
	{
		trigger_error('This is an enum... deal with it!', E_USER_WARNING);
	}

	public function __set($name, $value)
	{
		trigger_error('This is an enum... deal with it!', E_USER_WARNING);
	}

	public function __call($name, $args)
	{
		trigger_error('This is an enum... deal with it!', E_USER_WARNING);
	}

	public function __unset($name)
	{
		trigger_error('This is an enum... deal with it!', E_USER_WARNING);
	}

	/**
	 * because __getStatic() doesn't exist, abuse static methods to get the same result
	 *
	 * @param string $name
	 * @param array $args
	 *
	 * @return $this
	 *
	 * @throws \UnexpectedValueException
	 */
	public static function __callStatic($name, $args)
	{
		if (array_key_exists($name, static::$_enums) === false) {
			static::$_enums[$name] = new static($name);
		}

		return static::$_enums[$name];
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	public function __toString()
	{
		return (string)$this->_value;
	}

	public function __toInt()
	{
		return (int)('0' . $this->_value);
	}

	public function jsonSerialize()
	{
		return true === is_numeric($this->_value) && (int)$this->_value == $this->_value ? $this->__toInt() : $this->__toString();
	}
}
