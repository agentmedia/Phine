<?php

namespace Phine\Framework\System\Web;
use Phine\Framework\System\Http;
class Curl
{
    /**
     * 
     * The curl handle 
     */
    private $ch;
    private $baseUrl;
    private $queryParams;
    private $postParams;
    
    /**
     *
     * @var Http\RequestMethod
     */
    private $method;
    
    function __construct($baseUrl, Http\RequestMethod $method)
    {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
                
        switch ($method)
        {
            case  Http\RequestMethod::Post():
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_HEADER, false);
                break;
            
            case  Http\RequestMethod::Get():
                curl_setopt($this->ch, CURLOPT_POST, false);
                curl_setopt($this->ch, CURLOPT_HEADER, false);
                break;
            
            case Http\RequestMethod::Head():
                curl_setopt($this->ch, CURLOPT_HEADER, true);
                curl_setopt($this->ch, CURLOPT_NOBODY, true);
                break;
        }
    }
    
    function SetOption($option, $value)
    {
        curl_setopt($this->ch, $option, $value);
    }
    
    function GetInfo($info)
    {
        return curl_getinfo($this->ch, $info);
    }
    
    
    function GetError()
    {
        return curl_error($this->ch);
    }
    
    function GetErrorNo()
    {
        return curl_errno($this->ch);
    }
    
    function Execute()
    {
        $this->SetOption(CURLOPT_URL, $this->BuildUrl());
        
        if ($this->method->Equals(Http\RequestMethod::Post()))
            $this->SetOption(CURLOPT_POSTFIELDS, $this->postParams);
        
        $result = curl_exec($this->ch);
        $errNo= $this->GetErrorNo();
        if ($errNo)
            throw  new \Exception($this->GetError(), $errNo);
        
        return $result;
    }
    
    private function BuildUrl()
    {
        if (count($this->queryParams))
            return $this->baseUrl . '?' . http_build_query($this->queryParams , '', '&');
        
        return $this->baseUrl;
    }
    
    function Close()
    {
        if ($this->ch)
            curl_close($this->ch);
        
        $this->ch = null;
    }
    function __destruct()
    {
        $this->Close();
    }
    
    function SetQueryParam($name, $value)
    {
        $this->queryParams[$name] = $value;
    }
    
    function SetQueryParams(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }
    
    function SetPostParam($name, $value)
    {
        $this->postParams[$name] = $value;
    }
    
    
    function SetPostParams($postParams)
    {
        $this->postParams = $postParams;
    }
}