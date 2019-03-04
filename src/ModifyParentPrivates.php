<?php
declare(strict_types=1);

namespace Miraizou\Core;

use ReflectionClass;
use ReflectionException;

trait ModifyParentPrivates
{
    /** @var ReflectionClass */
    private $reflectedParentClass;

    /**
     * @throws ReflectionException
     */
    public function enableModifyParentPrivates() : void
    {
        $this->reflectedParentClass = (new ReflectionClass($this))->getParentClass();
    }

    /**
     * @param string $property
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function getParentPrivateProperty(string $property)
    {
        if ($this->reflectedParentClass === null) {
            $this->enableModifyParentPrivates();
        }

        ($prop = $this->reflectedParentClass->getProperty($property))->setAccessible(true);
        return $prop->getValue($this);
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return self
     * @throws ReflectionException
     */
    public function setParentPrivateProperty(string $property, $value) : self
    {
        if ($this->reflectedParentClass === null) {
            $this->enableModifyParentPrivates();
        }

        ($prop = $this->reflectedParentClass->getProperty($property))->setAccessible(true);
        $prop->setValue($this, $value);

        return $this;
    }

    /**
     * @param string $method
     * @param mixed[] $params
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function invokeParentPrivateMethod(string $method, ...$params)
    {
        if ($this->reflectedParentClass === null) {
            $this->enableModifyParentPrivates();
        }

        ($func = $this->reflectedParentClass->getMethod($method))->setAccessible(true);
        return $func->invoke($this, ...$params);
    }
}
