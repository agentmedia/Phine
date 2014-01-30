<?php
namespace Phine\Framework\Database\Sql;
require_once __DIR__ . '/Object.php';
use Phine\Framework\Database\Interfaces as DBInterfaces;

class Order extends Object
{
    private static $desc = 'DESC';
    private static $asc = 'ASC';
    
    /**
     * 
     * @var \string
     */
    private $direction;
    
    
    /**
     * 
     * @var Selectable
     */
    private $selectable;
    /**
     * 
     * @param Selectable $selectable
     * @param $direction One of ASC, DESC
     */
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, Selectable $selectable, $direction)
    {
        parent::__construct($connection);
        $this->selectable = $selectable;
        $this->direction= $direction;
    }
    
    /**
     * String representation for select statement
     * @return \string 
     */
    function __toString()
    {
        return $this->selectable . ' ' . $this->direction;
    }
    /**
     * Am ascending order of the selectable
     * @param Selectable $selectable
     * @return Order 
     */
    protected static function Asc(DBInterfaces\IDatabaseConnection $connection, Selectable $selectable)
    {
        return new self($connection, $selectable, self::$asc);    
    }
    
    /**
     * A descending order of the selectable
     * @param Selectable $selectable
     * @return Order
     */
    protected static function Desc(DBInterfaces\IDatabaseConnection $connection, Selectable $selectable)
    {
        return new self($connection, $selectable, self::$desc);    
    }
}