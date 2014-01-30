<?php

namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once  __DIR__ . '/Selectable.php';
class Value extends Selectable
{
    private $value;
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $value, $alias = '')
    {
        parent::__construct($connection, $alias);
        $this->value = $value;
    }
    
    public function FullName()
    {
        if (!$this->IsNull())
            return $this->escaper->EscapeValue($this->value);
        
        return 'NULL';
    }
    
    public function IsNull()
    {
        return $this->value === null;
    }
}