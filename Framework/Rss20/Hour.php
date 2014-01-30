<?php
namespace Phine\Framework\Rss20;
/**
 * 
 * Represents an rss 2.0 hour as needed by SkipHours element
 */
class Hour
{
    /**
     * The hour as integer between 0 and 23
     */
    public $Hour;
    
    /**
     * Creates a new hour element
     * @param int $hour The hour as integer between 0 and 23
     */
    function __construct($hour)
    {
        $this->Hour = $hour;
    }
}