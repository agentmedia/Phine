<?php

namespace Phine\Framework\Database\ObjectGeneration;
use Phine\Framework\System;


/**
 * 
 * Maps the class name camel case starting with capital letter.
 * @author Klaus Potzesny
 * 
 *
 */
class CamelCaseTableNameMapper implements Interfaces\ITableNameMapper
{
    /**
     *
     * @var array
     */
    private $prefixNamespaces;
    
    /**
     *
     * @var string
     */
    private $rootNamespace = '';
    /**
     *
     * @var type 
     */
    private $prefixesCaseSensitive = false;
    function __construct($rootNamespace = '', array $prefixNamespaces = array(), $prefixesCaseSensitive = false)
    {
        $this->prefixNamespaces = $prefixNamespaces;
        $this->rootNamespace = $rootNamespace;
        $this->prefixesCaseSensitive= $prefixesCaseSensitive;
        
    }
    
    
    function ClassName($tableName)
    {
        $result = $tableName;
        
        foreach ($this->prefixNamespaces as $prefix=>$namespace)
        {
            if ($prefix && System\String::StartsWith($prefix, $result, !$this->prefixesCaseSensitive))
            {
                $result = System\String::Part($result, System\String::Length($prefix));
                break;
            }            
        }
        return $this->ToCamelCase($result);
    }
    
    /*private function HandlePrefix($name)
    {
        $result = $name;
        if ($this->omitPrefix != '')
        {
            if (System\String::StartsWith($this->omitPrefix, $name, !$this->prefixCaseSensitive))
                $result = System\String::Part($name, System\String::Length($this->omitPrefix));
        }
        return $result != '' ? $result : $name;
    }*/
    
    private function ToCamelCase($name)
    {
        $result = '';
        $reader = new System\StringReader($name);
        $nextUp = true;
        while (false !== ($ch = $reader->ReadChar()))
        {
            if (ctype_punct($ch) || ctype_space($ch))
            {
                $nextUp = true;
                continue;
            }
            
            if (System\String::IsLetter($ch))
            {
                if ($nextUp)
                {
                    $result .= System\String::ToUpper($ch);
                    $nextUp = false;
                }
                else
                    $result .= $ch;
                
            }//else skip.
        }
        return $result;
    }
    
    public function FullNamespace($tableName)
    {
        $result = join ('\\', array($this->rootNamespace, $this->RelativeNamespace($tableName)));
        return trim($result, '\\');
    }
    
    public function RelativeNamespace($tableName)
    {
        foreach ($this->prefixNamespaces as $prefix=>$namespace)
        {
            if (System\String::StartsWith($prefix, $tableName, !$this->prefixesCaseSensitive))
                return $namespace;
        }
        return '';
    }
    
    function RootNamespace()
    {
        return trim($this->rootNamespace, '\\');
    }
}