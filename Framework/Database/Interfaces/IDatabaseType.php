<?php
namespace Phine\Framework\Database\Interfaces;

interface IDatabaseType
{
    /**
     * Returns an item (object or value type) related to the database type.
     * @param string $value A value string, integer or null as returned by database.
     * @return mixed
     */
    function FromDBString($value);
    
    
    /**
     * Returns an item object or value type related to the database type.
     * @param mixed $value A related value type.
     * @return string
     */
    function ToDBString($value);
    
    /**
     * Returns an item object or value type related to the database type.
     * @param string $value A value string, int or null as return by database.
     * @return mixed
     */
    function DefaultInstance();
}