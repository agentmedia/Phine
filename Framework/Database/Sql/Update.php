<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once  __DIR__ . '/Object.php';

class Update extends Object
{
    /**
     * 
     * @var Table
     */
    private $table;
    
    /**
     * 
     * @var array setList
     */
    private $setList;
    
    /**
     * 
     * @var Condition
     */
    private $condition;
    
    /**
     *
     * @var OrderList
     */
    private $orderBy;
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
    
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, Table $table, SetList $setList, Condition $condition = null, OrderList $orderBy = null, $offset = 0, $count = 0)
    {
        parent::__construct($connection);
        $this->table = $table;
        $this->setList = $setList;
        $this->condition = $condition;
        if ($count)
        {
            $this->offset = (int)$offset;
            $this->count = (int)$count;
        }
    }
    
    function __toString()
    {
        $result =  'UPDATE ' . $this->table . ' SET ' . $this->setList;
        if ($this->condition)
            $result .= ' WHERE ' . $this->condition;
        
        if ($this->orderBy)
            $result .= ' ORDER BY ' . $this->orderBy;
        
        if ($this->count > 0)
            $result .= $this->sqlLimiter->ApplySqlLimit($result, $this->offset, $this->count);
        
        return $result;
    }
}