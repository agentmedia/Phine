<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once 'Object.php';

/**
 * 
 * Provides  a list of sql selectables to us in an IN clause
 */
class InList extends Object
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
    
    function Add(Selectable $selectable)
    {
        $this->selectables[] = $selectable;
    }
    
    function __toString()
    {
        return '(' . \join(', ', $this->selectables) . ')';
    }
}

?>
