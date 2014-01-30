<?php
namespace Phine\Framework\Wording\Base;

use Phine\Framework\System;
use Phine\Framework\Wording\Interfaces;

/**
 * Provides a wording realizer replacing format placeholders like {0}, {1} ... 
 */
abstract class FormatRealizer implements Interfaces\IRealizer
{
    /**
     * Realizes a wording placeholder
     * @param string $placeholder The placeholder for the wording
     * @param $placeholder,... Optional strings inserted via String::Format
     * @return string The realized placeholder string
     */
    function Realize($placeholder)
    {
        $args = func_get_args();
        array_shift($args);
        return $this->RealizeArgs($placeholder, $args);
    }
    
    /**
     * Realizes a wording placeholder with formatting arguments as array
     * @param string $placeholder The placeholder for the wording
     * @param array $args Optional array of format arguments
     * @return string The realized placeholder string
     */
    function RealizeArgs($placeholder, array $args = array())
    {
        $result = $this->GetReplacement($placeholder);
        return System\String::FormatArgs($result, $args);
    }
    
    /**
     * Gets the replacement of the placeholder
     * @param string $placeholder The placeholder to be replaced with text
     * @return string The replacement
     */
    protected abstract function GetReplacement($placeholder);
}