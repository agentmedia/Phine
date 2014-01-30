<?php
namespace Phine\Framework\Rss20\Enums;
use Phine\Framework\System\Enum;

/**
 * Represents a day of week as needed in the date element
 */
class DayOfWeek extends Enum
{
    /**
     * Represents monday 
     * @return DayOfWeek
     */
    static function Monday()
    {
        return new self("Monday");
    }
    
    /**
     * Represents tuesday
     * @return DayOfWeek
     */
    static function Tuesday()
    {
        return new self("Tuesday");
    }
    
    /**
     * Represents wednesday 
     * @return DayOfWeek
     */
    static function Wednesday()
    {
        return new self("Wednesday");
    }
    
    /**
     * Represents thursday
     * @return DayOfWeek
     */
    static function Thursday()
    {
        return new self("Thursday");
    }
    
    /**
     * Represents friday 
     * @return DayOfWeek
     */
    static function Friday()
    {
        return new self("Friday");
    }
    
    /**
     * Represents saturday
     * @return DayOfWeek
     */
    static function Saturday()
    {
        return new self("Saturday");
    }
    
    /**
     * Represents sunday
     * @return DayOfWeek
     */
    static function Sunday()
    {
        return new self("Sunday");
    }
}