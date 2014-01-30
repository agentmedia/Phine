<?php

namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once __DIR__ . '/Object.php';

class Select extends Source
{
    
    /**
     * 
     * @var bool
     */
    private $distinct;
    /**
     *
     * @var SelectList
     */
    private $selectList;
    
    /**
     *
     * @var Source
     */
    private $source;
    
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
     * @var GroupList
     */
    private $groupList;
    
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
     * Alias name for usage as subselect
     * @var string
     */
    private $alias;
    
    /**
     * Creates a new Select object. 
     * @param bool $distinct Perform Distinct selecition
     * @param SelectList $selectList
     * @param Source $source
     * @param Condition $condition
     * @param OrderList $orderList
     * @param GroupList $groupList
     */
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $distinct, SelectList $selectList, Source $source, Condition $condition = null, OrderList $orderList = null, GroupList $groupList = null, $offset = 0, $count = 0)
    {
        parent::__construct($connection);
        $this->distinct= (bool)$distinct;
        $this->selectList = $selectList;
        $this->source = $source;
        $this->condition = $condition;
        $this->orderList = $orderList;
        $this->groupList = $groupList;
        if ($count)
        {
            $this->offset = (int)$offset;
            $this->count = (int)$count;
        }
    }
        
  
        
        /**
         * Sets the condition of the select
         * @param Condition $condition 
         */
        public function SetCondition(Condition $condition = null)
        {
            $this->condition = $condition;   
        }
        
        /**
         * Gets the condition of the select
         * @return $condition 
         */
        public function GetCondition()
        {
            return $this->condition;
        }
        
        /**
         * Sets the distinct flag of the select
         * @param bool $distinct 
         */
        public function SetDistinct($distinct)
        {
            $this->distinct = (bool)$distinct;
        }
        
        /**
         * Gets the distinct flag
         * @return bool
         */
        public function GetDistinct()
        {
            return $this->distinct;
        }
        
        /**
         * Sets the count of the select statement
         * @param int|null $count 
         */
        public function SetCount($count)
        {
            $this->count = $count;
        }
        
        /**
         * Gets the count of the select statement
         * @return int|null
         */
        public function GetCount()
        {
            return $this->count;
        }
        
        /**
         * Sets the group (by) list of the select
         * @param GroupList $groupList 
         */
        public function SetGroupList(GroupList $groupList = null)
        {
            $this->groupList = $groupList;
        }
        /**
         * 
         * Gets the group (by) list
         * return GroupList
         */
        public function GetGroupList()
        {
            return $this->groupList;
        }
        
        /**
         * Gets the offset to limit the select query result
         * @return int|null
         */
        public function GetOffset()
        {
            return $this->offset;
        }
        
        
        /**
         * Sets the offset to limit the select query result
         * @param int|null $offset 
         */
        public function SetOffset($offset = null)
        {
            $this->offset = $offset;
        }
        
        /**
         * Gets the order list of the select statement
         * @return OrderList
         */
        public function GetOrderList()
        {
            return $this->orderList;
        }
        
        /**
         * Sets the order list of the select statement
         * @param OrderList $orderList 
         */
        public function SetOrderList(OrderList $orderList = null)
        {
            $this->orderList = $orderList;
        }
        
        /**
         * Gets the list of fields to be selected
         * @return SelectList
         */
        public function GetSelectList()
        {
            return $this->selectList;
        }
        
        public function SetSelectList(SelectList $selectList)
        {
            $this->selectList = $selectList;
        }
        
        /**
         * Gets the source ('FROM' parts) of the select statement
         * @return Source
         */
        public function GetSource()
        {
            return $this->source;
        }
        
        /**
         * Sets the source of the select statement
         * @param Source $source 
         */
        public function SetSource(Source $source)
        {
            $this->source = $source;
        }
        
        
        /**
         * Sets the alias for use in Sub selects
         * @return string
         */
        public function SetAlias($alias)
        {
            $this->alias = $alias;
        }
        /**
         * Gets the alias for use in Sub selects
         * @return string
         */
        public function GetAlias()
        {
            return $this->alias;
        }

    public function ToString()
    {
        $result = 'SELECT ';
        if ($this->distinct)
            $result .= 'DISTINCT ';
            
        $result .= $this->selectList;
        $result .= ' FROM ' . $this->source;
        
        if ($this->condition)
            $result .= ' WHERE ' . $this->condition;
        
        if ($this->groupList)
            $result .= ' GROUP BY ' . $this->groupList;
        
        if ($this->orderList)
            $result .= ' ORDER BY ' . $this->orderList;
        
        if ($this->count > 0)
            $result = $this->sqlLimiter->ApplySqlLimit($result, $this->offset, $this->count);
        
        if ($this->alias)
            return '(' . $result . ') AS ' . $this->escaper->EscapeIdentifier($this->alias);
        
        return $result;
    }
}