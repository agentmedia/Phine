<?php

namespace Phine\Framework\Webservice\Base;
use Phine\Framework\Webservice\Interfaces as WSInterfaces;
use Phine\Framework\System\Http;
class Schema implements WSInterfaces\ISchema
{
    /**
     * The request param used to identify the service method
     * @var string 
     */
    private $methodParam;
    
    /**
     *
     * @param string $methodParam The request parameter used to identify the service method
     */
    function __construct($methodParam = 'method')
    {
        $this->methodParam = $methodParam;
    }
    /**
     * Gets the requested service method
     * @return string
     */
    function RequestedMethod()
    {
        return Http\Request::GetData($this->methodParam);
    }
    /**
     * Gets the method argument of current request
     * @param string $method The called method
     * @param string $paramName The parameter name of the called function
     * @return string
     */
    function MethodArgument($method, $paramName)
    {
        return Http\Request::GetData($paramName);
    }
}