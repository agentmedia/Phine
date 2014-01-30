<?php
namespace Phine\Framework\Access\Interfaces;

/**
 * Provides an interface for an accessor whose actions can be granted
 */
interface IAccessor
{
    /**
     * True if the accessor is not defined
     * @return bool 
     */
    function IsUndefined();
    
    
    /**
     * 
     * Verifies access data and saves current user if successful and flag is set
     * @param mixed data The user verification data, Usually an array of user name and password
     * @param bool $dontSave If true, the data is checked, but the user is not stored (logged in).
     * @return bool
     */
    function Verify($data, $dontSave = false);
    
    /**
     * 
     *  Undefines this accessor
     */
    function Undefine();
    
    /**
     * Loads the currently active accessor into this instance
     * @return bool
     */
    function LoadCurrent();
}
