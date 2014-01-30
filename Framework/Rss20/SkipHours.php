<?php
namespace Phine\Framework\Rss20;

/**
 * 
 * Represents the skiphours rss element telling when no update will happen
 */
class SkipHours
{
    /**
     *
     * @var array[Hour] 
     */
    private $hours = array();
    
    /**
     * Creates
     * @param array[Hour] $hours
     */
    function __construct($hours = array())
    {
        $this->hours = $hours;
    }
    
    /**
     * Adds an hour, if not already added
     * @param Hour $hour
     * @return void
     */
    function AddHour(Hour $hour)
    {
        foreach ($this->hours as $currentHour)
        {
            if ($currentHour->Hour == $hour->Hour)
                return;
        }
        $this->hours[] = $hour;
    }
    /**
     * Removes an hour from the list of hours, if present
     * @param Hour $hour
     * @return void
     */
    function RemoveHour(Hour $hour)
    {
        $result = array();
        foreach ($this->hours as $currentHour)
        {
            if ($currentHour->Hour != $hour->Hour)
                $result[] = $currentHour;
        }
        $this->hours = $result;
    }
    /**
     * Gets the lsit of hours
     * @return array[Hour]
     */
    function GetHours()
    {
        return $this->hours;
    }
}
