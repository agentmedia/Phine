<?php
namespace Phine\Framework\Webservice\Json;
use Phine\Framework\Webservice\Interfaces as WSInterfaces;
class Service
{
    /**
     *
     * @var WSInterfaces\ISchema
     */
    private $schema;
    /**
     *
     * @var WSInterfaces\IProvider
     */
    private $provider;
    function __construct(WSInterfaces\IProvider $provider)
    {
        $this->provider = $provider;
        $this->schema = $provider->GetSchema();
    }
    
    function GetResponse()
    {
        try
        {
            return json_encode($this->BuildResponse());
        }
        catch (\Exception $e)
        {
            return json_encode(array('error'=>$e->getMessage()));
        }   
    }
    
    private function BuildResponse()
    {
        $class = new \ReflectionClass($this->provider);
        $methodName = $this->schema->RequestedMethod();
        
        if ($class->hasMethod($methodName))
        {
            $method = $class->getMethod($methodName);
            $params = $method->getParameters();
            $args = array();
            foreach ($params as $param)
            {
                $args[] = $this->schema->MethodArgument($method, $param->name);
            }
            return $method->invokeArgs($this->provider, $args);
        }
        else
            throw new \Exception('Invalid method called');
    }
}
