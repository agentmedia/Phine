<?php
namespace Phine\Framework\System\Php;

abstract class WritableClass
{
    final function GetNewStatement()
    {
        $result = 'new \\' . get_class($this);
        $params = $this->GetConstructParams();
        $strParams = array();
        foreach ($params as $param)
        {
            $strParams[] = $this->GetParamString($param);
        }
        return $result . '(' .  join(', ', $strParams) . ')';
    }
    
    private function GetParamString($param)
    {
        if ($param === null)
            return "null";
        if (is_string($param))
            return "'" .  addcslashes($param, "'") . "'";
        
        else if (is_bool($param))
            return $param ? "true" : "false";
        
        else if (is_int($param) || is_float($param))
            return (string)$param;
        
        else if ($param instanceof WritableClass)
            return $param->GetNewStatement();
        
        else if (is_array($param))
        {
            $resultArr = array();
            foreach ($param as $key=>$val)
            {
                $resultArr[] = $this->GetParamString($key) . '=>' . $this->GetParamString($val);
            }
            return 'array(' . join (', ' , $resultArr) .')';
        }
        throw new \InvalidArgumentException('A writable class must have primitive types and PhpWritable classes as construct params only.');
    }
    /**
     * 
     * @return unknown_type
     */
    protected abstract function GetConstructParams();
}