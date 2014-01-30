<?php

namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once __DIR__ . '/Object.php';

class Condition extends Object
{
    private $statement;
    /**
     * 
     * @param $field
     * @param $value
     * @return Condition
     */
    protected static function Equals(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = $value === null ? 'IS' : ' = ';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * 
     * @param $field
     * @param $value
     * @return Condition
     */
    protected static function EqualsNot(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {    
        $op = $value === null ? 'IS NOT' : '<>';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * Greater than condition
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function GT(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = '>';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * Greater than equals condition
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function GTE(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = '>=';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * Less than condition
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function LT(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = '<';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * Less than equals condition
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function LTE(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = '<=';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * 
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function Like(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = 'LIKE';
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    /**
     * 
     * @param $field
     * @param $value
     * @param $startsWith
     * @param $endsWith
     * @return Condition
     */
    protected static function NotLike(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value)
    {
        $op = 'NOT LIKE';
            
        return self::CreateElementary($connection, $field, $value, $op);
    }
    
    protected static function IsNull(DBInterfaces\IDatabaseConnection $connection, Selectable $field)
    {
        return new self($connection, $field . ' IS NULL');
    }
    
    protected static function In(DBInterfaces\IDatabaseConnection $connection, Selectable $field, InList $inList)
    {
        return new self($connection, $field . ' IN ' . $inList);
    }
    
    protected static function NotIn(DBInterfaces\IDatabaseConnection $connection, Selectable $field, InList $inList)
    {
        return new self($connection, $field . ' NOT IN ' . $inList);
    }
    
    
    
    protected static function IsNotNull(DBInterfaces\IDatabaseConnection $connection, Selectable $field)
    {
        return new self($connection, $field . ' IS NOT NULL');
    }
    
    
    /**
     * 
     * @param Condition $where
     * @return Condition
     */
    function Or_(Condition $cond)
    {
        return $this->Combine($cond, 'OR');
    }
    
    /**
     * Combines this condition with the given one by AND.
     * @param Condition $cond
     * @return Condition
     */
    function And_(Condition $cond)
    {
        return $this->Combine($cond, 'AND');
    }
    /**
     * Combines this condition with the given.
     * @param Condition $cond
     * @param $combiner
     * @return Condition
     */
    private function Combine(Condition $cond, $combiner)
    {
        $statement = '(' . $this->statement . ') ' . $combiner . ' (' . $cond->statement . ')';
        return new self($this->connection, $statement);
    }
    /**
     * Returns an elementary condition.
     * @param $field
     * @param $value
     * @param $operator
     * @return Condition
     */
    private static function CreateElementary(DBInterfaces\IDatabaseConnection $connection, Selectable $field, Selectable $value, $operator)
    {
        return new self($connection, $field . ' ' . $operator .  ' ' . $value);
    }
    
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $statement)
    {
        parent::__construct($connection);
        $this->statement = $statement;    
    }
    
    function __toString()
    {
        return $this->statement;
    }
}