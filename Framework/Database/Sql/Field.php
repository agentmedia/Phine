<?php
namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once __DIR__ . '/Selectable.php';

class Field extends Selectable
{
    private $name;
    private $prefix;
    
    function __construct(DBInterfaces\IDatabaseConnection $connection, $name, $prefix ='', $alias = '')
    {
        parent::__construct($connection, $alias);
        $this->name = $name;
        $this->prefix = $prefix;
    }
    
    
    /**
     * SQL escaped name with prefix, if given.
     * @return string
     */
    public function FullName()
    {
        $fullName = $this->Name();
        if ($this->prefix)
            $fullName =  $this->escaper->EscapeIdentifier($this->prefix) . '.' . $fullName;
            
        return $fullName;
    }
    
    /**
     * SQL escaped name without prefix.
     * @return string
     */
    public function Name()
    {
        return $this->escaper->EscapeIdentifier($this->name);
    }
    
}