<?php
namespace Phine\Framework\Validation\Interfaces;
interface IValidator
{
    /**
     * True if value is successfully validated.
     * @param mixed $value Value That fits for this validator.
     * @return bool
     */
    function Check($value);
    
    /**
     * Get error string of last check.
     * @return string
     */
    function GetError(array $params = array());
    
}