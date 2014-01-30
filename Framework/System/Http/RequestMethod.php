<?php

namespace Phine\Framework\System\Http;
use Phine\Framework\System;
/**
 * Represents the request method 
 */
class RequestMethod extends System\Enum
{
    /**
     * Request method 'POST'
     * @return RequestMethod 
     */
    public static function Post()
    {
        return new self('POST');
    }
    
    /**
     * Request method 'GET'
     * @return RequestMethod 
     */
    public static function Get()
    {
        return new self('GET');
    }
    
    /**
     * Request method 'PUT'
     * @return RequestMethod 
     */
    public static function Put()
    {
        return new self('PUT');
    }
    
    /**
     * Request method 'HEAD'
     * @return RequestMethod 
     */
    public static function Head()
    {
        return new self('HEAD');
    }
    
    /**
     * 
     * Creates request method enum by its string representation
     * @param string $method
     * @return RequestMethod;
     * @throws \InvalidArgumentException Raises error if method parameter isn't valid
     */
    public static function FromString($method)
    {
        switch ($method)
        {
            case self::Get():
                    return self::Get();
                
            case self::Post():
                return self::Post();
                
            case self::Head():
                return self::Head();
                
            case self::Put():
                return self::Put();
                
            default:
                throw new \InvalidArgumentException('Cannot create request method from string ' . $method);
        }
    }
}
