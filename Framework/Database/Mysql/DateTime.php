<?php
namespace Phine\Framework\Database\Mysql;

use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\System;

class DateTime implements DBInterfaces\IDatabaseType
{
    /**
     * @return System\Date
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseType#FromDBString($value)
     */
    function FromDBString($value)
    {
        if ($value === null)
            return null;
        
      $date = substr($value, 0, 10);
       $parts1 = explode("-", $date);
       $parts2 = explode(":", substr($value, strlen($date) + 1));

       return new System\Date($parts1[2], $parts1[1], $parts1[0], $parts2[0], $parts2[1], $parts2[2]);
    }
    
    /**
     * Returns the database string representation of the date.
     * @return string
     * @param System\Date $value
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseType#ToDBString($value)
     */
    function ToDBString($value)
    {
        //Typ save:
        return $this->_ToDBString($value);
    }
    /**
     * 
     * @param System\Date $value
     * @return string
     */
    private function _ToDBString(System\Date $value = null)
    {
        if ($value === null)
         return $value;
        
        return $value->ToString('Y-m-d H:i:s');
    }
    
        /**
         *
         * @return \Phine\Framework\System\Date 
         */
    function DefaultInstance()
    {
        return new System\Date(0, 0, 0, 0, 0, 0);
    }
}