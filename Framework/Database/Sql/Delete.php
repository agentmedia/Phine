<?php

namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once __DIR__ . '/Object.php';

class Delete extends Object
{    
    /**
     *
     * @var Table
     */
    private $table;
    
    /**
     *
     * @var Condition
     */
    private $condition;
    
    /**
     *
     * @var OrderList
     */
    private $orderList;
    
    
    /**
     * 
     * @var int
     */
    private $offset;
    
    /**
     * 
     * @var int
     */
    private $count;
    
    
    /**
     * Creates a new Delete object. 
     * @param Table $table
     * @param Condition $condition
     * @param OrderList $orderList
     * @param int $offset
     * @param int $count
     */
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, Table $table, Condition $condition = null, OrderList $orderList = null, $offset = 0, $count = 0)
    {
        parent::__construct($connection);
        $this->table = $table;
        $this->condition = $condition;
        $this->orderList = $orderList;
        if ($count > 0)
        {
            $this->offset = (int)$offset;
            $this->count = (int)$count;
        }
    }
    
    function __toString()
    {
        $result = 'DELETE ';
        $result .= ' FROM ' . $this->table;
        
        if ($this->condition)
            $result .= ' WHERE ' . $this->condition;
        
        if ($this->orderList)
            $result .= ' ORDER BY ' . $this->orderList;
        
        if ($this->count > 0)
            $result = $this->sqlLimiter->ApplySqlLimit($result, $this->offset, $this->count);

        return $result . ';';
    }
}