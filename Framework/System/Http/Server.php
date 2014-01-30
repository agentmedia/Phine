<?php
namespace Phine\Framework\System\Http;

class Server
{
    
    /**
     * URL name of the server; for example www.mydomain.com.
     * @return string
     */
    static function Name()
    {
        return $_SERVER['SERVER_NAME'];
    }
    
    /**
     * Server Protocol as called by client; Usually HTTP or HTTPS with version number.
     * @return string
     */
    static function Protocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }
    
    /**
     * Returns the port of the connection; default = 80
     * @return int 
     */
    static function Port()
    {
        return $_SERVER['SERVER_PORT'];
    }
    
    static function BaseUrl()
    {
        $result = Request::IsHttps() ? 'https' : 'http';
         $result .= '://' . self::Name();
        $port = $port = self::Port();
        
        if ($port && $port != '80')
                $result .= ':' . $port;
        
        $result .= '/';
        return $result;
    }
    
    /**
     * The remote ip, if available
     * @return string
     */
    static function RemoteAddress()
    {
        if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
    }
}