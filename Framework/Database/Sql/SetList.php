<?php

namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once __DIR__ . '/Object.php';
class SetList extends Object
{
    /**
     * 
     * @var array[string=>Value]
     */
    private $fieldValues;
    
    /**
     * Creates a new Set List for inserts and updates. 
     * @param array $fieldValues 
     * @return SetList
     */
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $fieldName, Value $value)
    {
        parent::__construct($connection);
        $this->fieldValues = array($fieldName=>$value);
    }
    
    function Add($fieldName, Value $value)
    {
        $this->fieldValues[$fieldName] = $value;
    }
    
    function __toString()
    {
        $setArr = array();
        foreach ($this->fieldValues as $field=>$value)
        {
            $setArr[] = $this->escaper->EscapeIdentifier($field) . ' = ' .  $value;
        }
        $result = join(', ' , $setArr);

        return $result;
    }
}