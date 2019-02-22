<?php
	/**
	 * @author Doqnach
	 */

	namespace Miraizou\Core;


	trait ObjectTrait
	{
		/** Basic get/set/has/is/unset method implementation
		 *
		 * @param string $name
		 * @param array $args
         *
		 * @return mixed
		 */
		public function __call($name, array $args = array())
		{
            $func = array();
            if (preg_match('/^(?<method>_*\p{Ll}+)(?<prop>\p{Lu}.*)/u', $name, $func) === 1) {
                $method = $func['method'];
                $prop = lcfirst($func['prop']);
                switch($method) {
                    case 'get':
                        if (true === property_exists($this, $prop)) {
					        return $this->{$prop};
                        }
                        break;
                    case 'set':
					    $this->{$prop} = array_shift($args);
					    return $this;
                    case 'has':
                        return property_exists($this, $prop);
                    case 'is':
                        if (true === property_exists($this, $prop)) {
					return (bool)$this->{$prop};
                        }
                        break;
                    case 'unset':
                        try {
                            $ref = new \ReflectionObject($this);
                            if (true === $ref->hasProperty($prop) && $ref->getProperty($prop)->isPublic()) {
                                unset($this->{$prop});
                                return $this;
                            }
                        } catch (\ReflectionException $e) {
                            return $this;
                        }
                        break;
                }
            }

            $trace = debug_backtrace();
            trigger_error(
              'Undefined method via __call(): ' . static::class . '->' . $name .
              '() in ' . $trace[1]['file'] .
			  ' on line ' . $trace[1]['line']
            );

            return null;
        }
    }
