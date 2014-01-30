<?php
namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once __DIR__ . '/Selectable.php';

class FunctionCall extends Selectable
{
    /**
     * 
     * @var string
     */
    private $functionName;
    /**
     * 
     * @var string
     */
    private $params;
    
    /**
     * 
     * @var bool
     */
    private $distinctParams = false;
    
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $functionName, array $params = array(), $alias = '', $distinctParams = false)
    {
        parent::__construct($connection, $alias);
        $this->functionName = $functionName;
        $this->params = $params;
        $this->distinctParams = $distinctParams;
    }
    
    function FullName()
    {
        $strParams = \join(', ', $this->params);
        if ($this->distinctParams)
            $strParams = 'DISTINCT ' . $strParams;
    
        return $this->functionName . '(' . $strParams . ')';
    }
    
    protected static function Abs(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'ABS', $params, $alias);
    }
    
    protected static function Max(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'MAX', $params, $alias);
    }
    
    protected static function Min(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'MIN', $params, $alias);
    }
    
    protected static function Avg(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'AVG', $params, $alias);
    }
    
    protected static function Cos(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'COS', $params, $alias);
    }
    protected static function Sin(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'SIN', $params, $alias);
    }
    protected static function ACos(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'ACOS', $params, $alias);
    }
    protected static function ASin(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'ASIN', $params, $alias);
    }
    protected static function Count(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '', $distinctParams = false)
    {    
        return new self($connection, 'COUNT', $params, $alias, $distinctParams);
    }
    protected static function Concat(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'CONCAT', $params, $alias);
    }
    protected static function Sum(DBInterfaces\IDatabaseConnection $connection, array $params = array(), $alias = '')
    {
        return new self($connection, 'SUM', $params, $alias);
    }
}