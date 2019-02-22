<?php
declare(strict_types=1);

namespace Miraizou\Core;

use ReflectionClass;
use ReflectionException;

trait ModifyParentPrivates
{
    /** @var ReflectionClass */
    private $parent;

    /**
     * @throws ReflectionException
     */
    public function enableModifyParentPrivates()
    {
        $this->parent = (new ReflectionClass($this))->getParentClass();
    }

    public function getPrivateProperty($property)
    {
        if ($this->parent === null) {
            $this->enableModifyParentPrivates();
        }

        ($prop = $this->parent->getProperty($property))->setAccessible(true);
        return $prop->getValue($this);
    }

    public function setPrivateProperty($property, $value)
    {
        if ($this->parent === null) {
            $this->enableModifyParentPrivates();
        }

        ($prop = $this->parent->getProperty($property))->setAccessible(true);
        $prop->setValue($this, $value);
    }

    public function invokePrivateMethod($method, ...$params)
    {
        if ($this->parent === null) {
            $this->enableModifyParentPrivates();
        }

        ($func = $this->parent->getMethod($method))->setAccessible(true);
        return $func->invoke($this, ...$params);
    }
}
