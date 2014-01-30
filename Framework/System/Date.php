<?php
namespace Phine\Framework\System;

/*
 * Class for date time
 *
 */
 class Date
 {
     private $year;
     private $month;
     private $day;
     private $hour;
     private $minute;
     private $dayOfWeek;
     private $second;
     private $timeStamp;

     static function FromTimeStamp($timeStamp)
     {
         $dt = new Date();
         $dt->timeStamp = $timeStamp;
         $dt->FitToTimeStamp();
         return $dt;
     }
     function AddDays($days)
     {
         $this->timeStamp += $days * 60 * 60 * 24;
         $this->FitToTimeStamp();
     }
     
     
     function AddSeconds($seconds)
     {
         $this->timeStamp += $seconds;
         $this->FitToTimeStamp();
     }

     function AddMinutes($minutes)
     {
         $this->timeStamp += $minutes * 60;
         $this->FitToTimeStamp();
     }

     private function FitToTimeStamp()
     {
         $dt = getdate($this->timeStamp);
         $this->year = $dt["year"];
         $this->month = $dt["mon"];
         $this->day = $dt["mday"];
         $this->hour = $dt["hours"];
         $this->minute = $dt["minutes"];
         $this->second = $dt["seconds"];
         $this->dayOfWeek = $dt["wday"];
     }

     function __construct($day=null, $month=null, $year=null, $hour = null, $minute = null, $sec = null)
     {
        $now = getdate();
        if (!isset($year))
             $year = $now["year"];

        if (!isset($month))
          $month = $now["mon"];

        if (!isset($day))
          $day = $now["mday"];

        if (!isset($hour))
             $hour = $now["hours"];

        if (!isset($minute))
          $minute = $now["minutes"];

        if (!isset($sec))
          $sec = $now["seconds"];

        if (!isset($day))
          $day = $now["mday"];

        if (checkdate($month, $day, $year))
        {
          $this->timeStamp = mktime($hour,$minute,$sec, $month, $day, $year);
          $this->FitToTimeStamp();
        }
     }
     /**
      * A unix Timestamp
      * @return int
      */
     function TimeStamp()
     {
       return $this->timeStamp;
     }
     function IsValid()
     {
       return isset($this->timeStamp);
     }

     function Year()
     {
       return $this->year;
     }

     function Month()
     {
       return $this->month;
     }

     function Day()
     {
       return $this->day;
     }

     function Hour()
     {
         return $this->hour;
     }
     function Second()
     {
         return $this->second;
     }
     function Minute()
     {
         return $this->minute;
     }

     function DayOfWeek()
     {
         return $this->dayOfWeek;
     }

     function IsBefore($date)
     {
       if ($this->year < $date->year)
         return true;

       if ($this->year > $date->year)
         return false;

       if ($this->month < $date->month)
         return true;

       if ($this->month > $date->month)
         return false;

       if ($this->day < $date->day)
         return true;

       if ($this->day > $date->day)
         return false;

       if ($this->hour < $date->hour)
         return true;

      if ($this->minute < $date->minute)
         return true;

       if ($this->minute > $date->minute)
         return false;

        if ($this->second < $date->second)
          return true;

        if ($this->second > $date->second)
          return false;

       return false;
     }

     function IsAfter($date)
     {
        return $date->IsBefore($this);
     }

     function IsEqual($date)
     {
       return !$this->IsBefore($date) && !$date->IsBefore($this);
     }
     
     /**
      * Returns formatted date using php date() function
      * @param string $format
      * @return string
      */
     function ToString($format)
     {
       return date($format, $this->TimeStamp());
     }
     
     /**
      * Returns formatted date using php strftime() function
      * @param string $format
      * @return string
      */
     function Format($format = "%c")
     {
       return strftime($format, $this->TimeStamp());
     }

     /*function ToDBDateTime()
     {
         return $this->ToString("%Y-%m-%d %H:%M:%S");
     }
     */
     
     /**
      * A copy that points to the same time.
      * @return Date
      */
     function Copy()
     {
         return new Date($this->Day(), $this->Month(), $this->Year(),
                         $this->Hour(), $this->Minute(), $this->Second());
     }
     /**
      * The current date and time.
      * @return Date
      */
     static function Now()
     {
         return new Date();
     }
 
     /**
      * The current day midnight.
      * @return Date
      */
     static function Today()
     {
         $now = new Date();
         return new Date($now->Day(), $now->Month(), $now->Year(), 0, 0, 0);
     }
     
     /**
      * 
      * Converts this to the php built in DateTime class
      * @return \DateTime
      */
     function ToDateTime()
     {
         return new \DateTime('@' . $this->TimeStamp());
     }
     
     /**
      * 
      * Creates a date object from a php built in DateTime object
      * @return Date
      */
     static function FromDateTime(\DateTime $dateTime)
     {
         return self::FromTimeStamp($dateTime->getTimestamp());
     }
     
     /**
      * Creates date time from a format string and string representation
      * @param string $format The format date
      * @param The date time as string $dateTimeString
      * @return Date Returns date if parsing was successful, null otherwise
      */
     static function Parse($format, $dateTimeString)
     {
         $dt = \DateTime::createFromFormat($format, $dateTimeString);
         if (!$dt)
             return null;
         
         return self::FromDateTime($dt);
     }
 }
?>