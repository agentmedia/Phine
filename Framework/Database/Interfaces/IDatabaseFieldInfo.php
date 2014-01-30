<?php
namespace Phine\Framework\Database\Interfaces;

interface IDatabaseFieldInfo
{
    /**
     * Is the fiels unique?
     * @return bool
     */
    public function IsUnique();
    
    /**
     * Is the field a primary key?
     * @return bool
     */
    public function IsPrimary();
    
    /**
     * Is the fiels nullable?
     * @return bool
     */
    public function IsNullable();
    
    /**
     * Related table name, if relation exists.
     * @return string
     */
    public function RelatedTable();
    
    /**
     * Related field name, if relation exists.
     * @return string
     */
    public function RelatedField();
    
    /**
     * Returns database type definition.
     * @return IDatabaseTypeDef
     */
    function  GetTypeDef();    
}