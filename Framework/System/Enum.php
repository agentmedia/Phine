<?php

namespace Phine\Framework\System;

/** 
 * Base class for enums
 */

abstract class Enum
{
    protected $value;
    private static $allowedValues = array();
    /**
     * Creates a new Enum
     * @param mixed $value A primitive type, string or integer
     */
    protected function __construct($value)
    {
        $this->value = $value;
    }
    
    /**
     * Returns the value as string
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
    
    /**
     * Gets the value
     * @return mixed A primitive type (string or integer) 
     */
    public function Value()
    {
        return $this->value;
    }
    
    /**
     * Checks if right hand side has the same class and value as this
     * @param Enum $rhs
     * @return bool 
     */
    public function Equals(Enum $rhs = null)
    {
        return $rhs != null && 
            get_class($rhs) == get_class($this) &&
            $rhs->Value() === $this->Value();
    }
    
    /**
     * Creates the enum by value
     * @param string $value
     * @return Enum
     * @throws \InvalidArgumentException 
     */
    static function ByValue($value)
    {
        $calledClass = get_called_class();
        $allowed = self::AllowedClassValues($calledClass);
        if (!in_array($value, $allowed, true))
            throw new \InvalidArgumentException('Value ' . $value . ' not allowed for enum ' . $calledClass);
        
        return new $calledClass($value);
    }
    /**
     * Searches the creation method with the given name and invokes it
     * @param string $name
     * @return Enum
     * @throws \InvalidArgumentException 
     */
    static function ByCreationMethod($name)
    {
        $enumClass = new \ReflectionClass(get_class());
        $calledClass = get_called_class();
        $reflectionClass = new \ReflectionClass($calledClass);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_STATIC);
        foreach ($methods as $method)
        {
            if ($method->name != $name)
                continue;
            
            if ($method->isPublic() && $method->getNumberOfParameters() == 0)
            {
                if (!$enumClass->hasMethod($method->name))
                {
                    $result = $method->invoke(null);
                    if (get_class($result) == $calledClass)
                        return $result;
                }
            }
        }
        throw new \InvalidArgumentException("'Creation method '$name' not found");
    }
    
    /**
     * Returns the allowed values for the given class
     * @param string $calledClass The class name
     * @return type 
     */
    private static function AllowedClassValues($calledClass)
    {
        if (!isset(self::$allowedValues[$calledClass]))
        {
            $values = array();
            
            $enumClass = new \ReflectionClass(get_class());
            $reflectionClass = new \ReflectionClass($calledClass);
            
            $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_STATIC);
            foreach ($methods as $method)
            {
                if ($method->isPublic() && $method->getNumberOfParameters() == 0)
                {
                    //avoid self references
                    if (!$enumClass->hasMethod($method->name))
                    {
                        $result = $method->invoke(null);
                        if (get_class($result) == $calledClass)
                            $values[] = $result->Value();
                    }
                }
            }
            self::$allowedValues[$calledClass] = $values;
        }
        return self::$allowedValues[$calledClass];
    }
    
    /**
     * Returns the allowed values for derived enum types
     * @return array An array of all allowed values
     */
    public static function AllowedValues()
    {
        $calledClass = get_called_class();
        return self::AllowedClassValues($calledClass);
    }
}