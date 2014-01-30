<?php
namespace Phine\Framework\Database\Types;

use Phine\Framework\Database\Interfaces as DBInterfaces;

/**
 * Big integers are saved as strings so they are not cut off.
 * @author Klaus
 *
 */
class BigInt implements DBInterfaces\IDatabaseType
{
    function FromDBString($value)
    {
        if ($value === null)
            return null;
        
        return (string)$value;
    }

    function ToDBString($value)
    {
        if ($value === null)
            return null;

        return (string)$value;
    }
    
    function DefaultInstance()
    {
        return '0';
    }
}