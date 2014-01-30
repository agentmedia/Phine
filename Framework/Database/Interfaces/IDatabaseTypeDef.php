<?php
namespace Phine\Framework\Database\Interfaces;

/**
 * 
 * Empty Interface for sql engine specific field type definition data.
 * @author Klaus
 *
 */
interface IDatabaseTypeDef
{
    /**
     * Gets the corresponding database type
     * @return IDatabaseType
     */
    function GetType();
    
    /**
     * Gets length of the field if available.
     * @return int
     */
    function GetLength();
    
    /**
     * Gets set items for the "enum" or "set" type fields if available.
     * @return array
     */
    function GetSet();
}