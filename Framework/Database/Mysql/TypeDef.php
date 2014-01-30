<?php
namespace Phine\Framework\Database\Mysql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\Database\Types as DBTypes;

class TypeDef implements DBInterfaces\IDatabaseTypeDef
{
    /**
     * 
     * @var string
     */
    private $name;
    /**
     * 
     * @var int
     */
    private $length;
    /**
     * Allowed values for Type Enum and Set.
     * @var array string
     */
    private $set;

    /**
     * 
     * @param string $name
     * @param string $length
     * @param string $set
     * @param string $attributes
     */
    function __construct($fullName)
    {
        $this->ParseFullName($fullName);
    }
    
    /**
     *
     * Gets field length if available.
     * @return int
     * 
     */
    function GetLength()
    {
        return $this->length;
    }
    
    
    /**
     *
     * Gets set items for 'enum' or 'set' type fields if available.
     * @return array
     * 
     */
    function GetSet()
    {
        return $this->set;
    }
    
    /**
     * Parses pure parse name and set or length from full type name.
     * @param $fullName
     * @return unknown_type
     */
    private function ParseFullName($fullName)
    {
        $fullName = strtolower($fullName);
        $this->name = $fullName;
        $this->length = 0;
        $this->set = array();
        
        $bracePos1 = strpos($fullName, '(');
        if ($bracePos1 > 0)
        {
            $this->name = substr($fullName, 0, $bracePos1);
            $bracePos2 = strpos($fullName, ')', $bracePos1);
            if ($bracePos2 > 0)
            {
                $inBrace = trim(substr($fullName, $bracePos1 + 1, $bracePos2 - $bracePos1 - 1));
                if (ctype_digit($inBrace))
                    $this->length = (int)$inBrace;
                else
                {
                    $set = explode(',', $inBrace);
                    foreach ($set as $set)
                    {
                        $this->set[] = trim($set, " '");
                    }
                }
            }
        }
    }
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseTypeDef#GetType()
     * @return DBInterfaces\IDatabaseType
     */
    function GetType()
    {
        switch (strtolower($this->name))
        {
            case 'bigint':
            case 'int':                
            case 'tinyint':
                if ($this->length == 1) //Save as bool
                    return new DBTypes\Bool();
    
                else if ($this->length > 11) //Save as Big int then.
                    return new DBTypes\BigInt();
                
                return new DBTypes\Int();    
                
            case 'datetime':
                    return new DateTime();

            case 'date':
                    return new Date();

            case 'timestamp':
                    return new TimeStamp();
    
            default:
                return new DBTypes\String();
        }
    }
}