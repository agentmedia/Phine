<?php
namespace Phine\Framework\Database\Interfaces;

interface IDatabaseEscaper
{
    /**
     * Escaping of field values.
     * @param $identifier
     * @return string
     */
    function EscapeValue($value);
    
    /**
     * Escaping of field and table names.
     * @param $identifier
     * @return string
     */
    function EscapeIdentifier($identifier);
}