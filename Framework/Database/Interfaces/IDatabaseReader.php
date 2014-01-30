<?php
namespace Phine\Framework\Database\Interfaces;

interface IDatabaseReader
{
    /**
     * Reads the next dataset and returns a boolean, if false the end is reached.
     * @return bool
     */
    function Read();
    /**
     * All names accepted in method ByName().
     * @return array 
     */
    function Names();
    function ByIndex($idx);
    function ByName($name);
    function Close();
}