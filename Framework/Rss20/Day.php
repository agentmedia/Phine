<?php
namespace Phine\Framework\Rss20;
use Phine\Framework\Rss20\Enums\DayOfWeek;

/**
 * 
 * Represents an rss 2.0 hour as needed by SkipHours element
 */
class Day
{
    /**
     * The hour as integer between 0 and 23
     * 
     */
    private $dayOfWeek;
    
    /**
     * Creates a new day of week object
     * @param DayOfWeek $dayOfWeek
     */
    function __construct(DayOfWeek $dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }
    /**
     * Gets the day of week
     * @return DayOfWeek
     */
    function DayOfWeek()
    {
        return $this->dayOfWeek;
    }
}