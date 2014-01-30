<?php
namespace Phine\Framework\System\Http;

class Request
{    
    /**
        * Returns the data for the given request method
        * @param RequestMethod $method The request method, the current method if omitted or null
        * @param string $param If given and not null, the data of the given param is returned only
        */
    static function MethodData(RequestMethod $method = null, $param = null)
    {
        if (!$method)
            $method = self::Method();

        switch ($method)
        {
            case RequestMethod::Get():
                return self::GetData($param);

            case RequestMethod::Post():
                return self::PostData($param);

                //Todo: What about put and head data?
        }
        return null;
    }
        
    static function PostData($param = null, $default = '')
    {
        if ($param !== null)
        {
            if (!isset($_POST[$param]))
                return $default;

            return $_POST[$param];
        }
        return $_POST;
    }
    
    /**
    * Returns The GET data
    * @param string $param
    * @param string $default
    * @return string
    */
    static function GetData($param = null, $default = '')
    {
        if ($param !== null)
        {
            if (!isset($_GET[$param]))
                return $default;

            return $_GET[$param];
        }
        return $_GET;
    }
    
    static function Data($param = null, $default = '')
    {
        if ($param !== null)
        {
            if (!isset($_REQUEST[$param]))
                return $default;
            
            return $_REQUEST[$param];
        }
        return $_REQUEST;
    }

    static function IsPost()
    {
        return (string)self::Method() == (string)RequestMethod::Post();
    }
    
    static function IsGet()
    {
        return (string)self::Method() == (string)RequestMethod::Get();
    }
    
    static function IsHead()
    {
        return (string)self::Method() == (string)RequestMethod::Head();
    }
    
    static function IsPut()
    {
        return (string)self::Method() == (string)RequestMethod::Put();
    }
    
    static function IsHttps()
    {
        $https = isset ($_SERVER['HTTPS']) ?: false;
        if ($https && $https != "off")
            return true;
    }
    /**
     * Gets the current request method
     * @return RequestMethod
     */
    static function Method()
    {
        return RequestMethod::FromString( $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Gets the current request uri as string
     * @return string 
     */
    static function Uri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Gets the current request query string
     * @return string 
     */
    static function QueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }
}