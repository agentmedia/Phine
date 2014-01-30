<?php

namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once __DIR__ . '/Source.php';

class Join extends Source
{
    /**
     * 
     * @var array[Table]
     */    
    private $tables = array();

    /**
     * @var array[Join]
     */
    private $joins = array();
    
    /**
     * @var array[Condition]
     */
    private $conditions = array();
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, Table $table = null)
    {
        parent::__construct($connection);
        $this->tables[] = $table;
    }
    
    /**
     * Returns a new source as inner join of this and the given source.
     * @param Source $source
     * @param Condition $condition
     * @return Join
     */
    function InnerJoin(Table $table, Condition $condition, $prepend = false)
    {
        return $this->Join($table, JoinType::Inner(), $condition, $prepend);
    }
    
    /**
     * Returns a new source as left join of this and the given source.
     * @param Source $source
     * @param Condition $condition
     * @return Join
     */
    function LeftJoin(Table $table, Condition $condition, $prepend = false)
    {
        return $this->Join($table, JoinType::Left(), $condition, $prepend);
    }

    
    /**
     * Returns a new source as right join of this and the given source.
     * @param Source $source
     * @param Condition $condition
     * @return Join
     */
    function RightJoin(Table $table, Condition $condition, $prepend = false)
    {
        return $this->Join($table, JoinType::Right(), $condition, $prepend);
    }
    
    /**
     * Returns a new source as outer join of this and the given source.
     * @param Source $source
     * @param Condition $condition
     * @return Join
     */
    function OuterJoin(Table $table, Condition $condition, $prepend = false)
    {
        return $this->Join($table, JoinType::Outer(), $condition, $prepend);
    }
    
    
    /**
     * Returns a new source as given join of this and the given source.
     * @param Source $source
     * @param Join $join
     * @param Condition $condition
     * @return Join
     */
    function Join(Table $table, JoinType $join, Condition $condition, $prepend = false)
    {
        $result = $this->Duplicate();
        if ($prepend)
        {
            array_unshift($result->tables, $table);
            array_unshift($result->joins, $join);
            array_unshift($result->conditions, $condition);
        }
        else
        {    
            $result->tables[] = $table;
            $result->joins[] = $join;
            $result->conditions[] = $condition;
        }
        return $result;
    }
    
    /**
     * String representation as needed in FROM statement.
     * @return string
     */
    function ToString()
    {
        $resultArr = array((string)$this->tables[0]);
        foreach ($this->tables as $key=>$table)
        {
            if ($key == 0)
                 continue;
                
            $part = (string)$this->joins[$key - 1]; 
            $part .= ' ' . (string)$table;
            $part .= ' ON ' . (string)$this->conditions[$key - 1];

            $resultArr[] = $part;
        }
        return join(' ', $resultArr);
    }
    
    /**
     * Clones this object.
     * @return Source
     */
    private function Duplicate()
    {
        $result = new Join($this->connection);
        $result->tables = $this->tables;
        $result->joins = $this->joins;
        $result->conditions = $this->conditions;
        return $result;
    }
}