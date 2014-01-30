<?php

namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once __DIR__ . '/Object.php';
 
class Insert extends Object
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
    
    function __construct(DBInterfaces\IDatabaseConnection $connection, Table $table, SetList $setList)
    {
        parent::__construct($connection);
        $this->table = $table;
        $this->setList = $setList;
    }
    
    function __toString()
    {
        return 'INSERT INTO ' . $this->table . ' SET ' . $this->setList;
    }
}