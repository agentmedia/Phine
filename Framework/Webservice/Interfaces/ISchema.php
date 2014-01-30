<?php
namespace Phine\Framework\Webservice\Interfaces;
interface ISchema
{
    /**
     * Gets the requested service method
     * @return string
     */
    function RequestedMethod();
    /**
     * Gets the method argument that was passed, using the param name
     * @param string $method
     * @param string $paranName
     * @return string
     */
    function MethodArgument($method, $paramName);
}
