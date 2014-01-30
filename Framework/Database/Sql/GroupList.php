<?php
namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once 'Object.php';
class GroupList extends Object
{
    private $selectables = array();
    
    /**
     * 
     * @param array[Selectable] $selectables
     */
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, Selectable $selectable)
    {
        parent::__construct($connection);
        $this->selectables = array($selectable);
    }
    
    function Add(Sql\Selectable $selectable)
    {
        $this->selectables[] = $selectable;
    }
    
    function __toString()
    {
        return \join(', ', $this->selectables);
    }
}