<?php
namespace Phine\Framework\Rss20;

class SkipDays
{
    /**
     *
     * @var array[Day] 
     */
    private $days = array();
    
    /**
     * Creates
     * @param array[Day] $hours
     */
    function __construct($days = array())
    {
        $this->days = $days;
    }
    
    /**
     * Adds an day to the list, if not already added
     * @param Day $day
     * @return void
     */
    function AddDay(Day $day)
    {
        foreach ($this->days as $currentDay)
        {
            if ($currentDay->DayOfWeek() == $day->DayOfWeek())
                return;
        }
        $this->days[] = $day;
    }
    
    /**
     * Removes a day from the list of days, if added before
     * @param Day $day
     */
    function RemoveDay(Day $day)
    {
        $result = array();
        foreach ($this->days as $currentDay)
        {
            if ($currentDay->DayOfWeek != $day->DayOfWeek())
                $result[] = $currentDay;
        }
        $this->days = $result;
    }
    
    /**
     * Gets the list of days
     * @return array[Day]
     */
    function GetDays()
    {
        return $this->days;
    }
}
