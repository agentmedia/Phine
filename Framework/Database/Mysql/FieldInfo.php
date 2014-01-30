<?php
namespace Phine\Framework\Database\Mysql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\System\Php;

class FieldInfo extends Php\WritableClass implements DBInterfaces\IDatabaseFieldInfo
{
    /**
     * 
     * @var string
     */
    private $fullName;
    
    /**
     * 
     * @var TypeDef
     */
    private $typeDef;
    
    /**
     * 
     * @var bool
     */
    private $isUnique;
    
    /**
     * 
     * @var bool
     */
    private $isNullable;
    
    
    /**
     * 
     * @var bool
     */
    private $isPrimary;
    
    /**
     * 
     * @var string
     */
    private $relatedTable;
    
    /**
     * 
     * @var string
     */
    private $relatedField;
    function __construct($fullName, $relatedTable = '', $relatedField = '', $isUnique = false, $isPrimary = false, $isNullable = false)
    {
        $this->fullName = $fullName;
        $this->typeDef = new TypeDef($fullName);
        $this->isPrimary = $isPrimary;
        $this->isUnique = $isUnique;
        $this->isNullable = $isNullable;
        $this->relatedTable = $relatedTable;
        $this->relatedField= $relatedField;
    }
    
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Php/PhpWritableClass#GetConstructParams()
     */
    protected function GetConstructParams()
    {
        return array($this->fullName, $this->relatedTable, $this->relatedField, $this->isUnique, $this->isPrimary, $this->isNullable);
    }
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseFieldInfo#GetTypeDef()
     * @return TypeDef
     */
    function GetTypeDef()
    {
        return $this->typeDef;
    }
    
    function IsPrimary()
    {
        return $this->isPrimary;
    }
    
    
    function IsUnique()
    {
        return $this->isUnique;
    }
    
    function IsNullable()
    {
        return $this->isNullable;
    }
    
    function RelatedField()
    {
        return $this->relatedField;
    }
    
    function RelatedTable()    
    {
        return $this->relatedTable;
    }
}
