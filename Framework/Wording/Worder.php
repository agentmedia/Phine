<?php

namespace Phine\Framework\Wording;

/**
 * Provides methods to replace placeholders with senseful contents
 *
 * @author klaus
 */
class Worder
{
    /**
     * 
     * @var Interfaces\IRealizer
     */
    private static $defaultRealizer;
    
    /**
     * Sets a default realizer
     * @param Interfaces\IRealizer $realizer 
     */
    static function SetDefaultRealizer(Interfaces\IRealizer $realizer)
    {
        self::$defaultRealizer = $realizer;
    }
    
    /**
     * 
     * Returns the given realizer or default, if none given
     * @param Interfaces\IRealizer $realizer
     * @return Interfaces\IRealizer
     * @throws \Exception Exception if neither the parameter nor a default realizer is set
     */
    private static function RetrieveRealizer(Interfaces\IRealizer $realizer = null)
    {
       if (!$realizer)
            $realizer = self::$defaultRealizer;
        
        if (!$realizer)
            throw new \Exception('No wording realizer set');
            
        return $realizer;
    }
    
    /**
     * Replaces the placeholder using the given realizer and arguments
     * @param string $placeholder
     * @param Interfaces\IRealizer $realizer
     * @param array $args
     * @return string
     */
    static function ReplaceArgsUsing($placeholder, Interfaces\IRealizer $realizer = null, array $args = array())
    {
        return self::RetrieveRealizer($realizer)->RealizeArgs($placeholder, $args);
    }
    
    /**
     * Replaces 
     * @param string $placeholder
     * @param Interfaces\IRealizer $realizer
     * @param $realizer,... Optional strings inserted via String::Format
     * @return string 
     */
    static function ReplaceUsing($placeholder, Interfaces\IRealizer $realizer = null)
    {
        echo($placeholder);
        $args = func_get_args();    
        array_shift($args);
        if (count($args) >= 2)
        {
            array_shift($args);   
        }
        return self::ReplaceArgsUsing($placeholder, $realizer, $args);
    }
    
    /**
     * Replaces the placeholder using the default wording realizer
     * @param string $placeholder
     * @param $placeholder,... Optional strings inserted via String::Format
     * @return string 
     */
    static function Replace($placeholder)
    {
        $args = func_get_args();    
        array_shift($args);
        return self::ReplaceArgsUsing($placeholder, null, $args);
    }
    /**
     * Replaces the placeholder using the default realizer
     * @param string $placeholder
     * @param array $args Optional array of strings inserted via String::Format
     * @return string 
     */
    static function ReplaceArgs($placeholder, array $args = array())
    {
        return self::ReplaceArgsUsing($placeholder, null, $args);
    }
}