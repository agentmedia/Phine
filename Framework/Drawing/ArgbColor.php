<?php

namespace Phine\Framework\System\Drawing;
/**
 * An argb color
 */
class ArgbColor
{
    /**
     * Alpha channel
     * @var int
     */
    private $a;
    
    /**
     * Red color part
     * @var int
     */
    private $r;
    
    /**
     * Green color part
     * @var int
     */
    private $g;
    
    /**
     * Blue color part
     * @var int
     */
    private $b;
    
    /**
     * 
     * @param int $a Opacity part
     * @param int $r Red part
     * @param int $g Green part
     * @param int $b Blue part
     */
    function __construct($a, $r, $g, $b)
    {
        $this->SetAlpha($a);
        $this->SetRed($r);
        $this->SetGreen($g);
        $this->SetBlue($b);
    }
    
    /**
     * Gets full opacity argb color from RGB
     * @param int $r
     * @param int $g
     * @param int $b
     * @return ArgbColor
     */
    static function FromRgb($r, $g, $b)
    {
        return new self(0xff, $r, $g, $b);
    }
    
    /**
     * Checks color part to be between 0 and 255
     */
    private function CheckColorValue($value)
    {
        if ($value < 0 && $value > 255)
            throw new \InvalidArgumentException("$value is not a valid color integer between 0 and 255");
    }
    /**
     * Sets opacity
     * @param int $a Alpha channel
     */
    function SetAlpha($a)
    {
        $this->CheckColorValue($a);
        $this->a = $a;
    }
    
    /**
     * Gets opacity
     * @return int
     */
    function GetAlpha()
    {
        return $this->a;
    }
    
    /**
     * Sets red color part
     * @param int $r
     */
    function SetRed($r)
    {
        $this->CheckColorValue($r);
        $this->r = $r;
    }
    
    /**
     * Gets red color part
     * @return int
     */
    function GetRed()
    {
        return $this->r;
    }
    
    /**
     * Sets green color part
     * @param int $g
     */
    function SetGreen($g)
    {
        $this->CheckColorValue($g);
        $this->g = $g;
    }
    /**
     * Gets green color part
     * @return int
     */
    function GetGreen()
    {
        return $this->g;
    }
    
    /**
     * Sets blue color part
     * @param int $r
     */
    function SetBlue($b)
    {
        $this->CheckColorValue($b);
        $this->b = $b;
    }
    
    /**
     * Gets blue color part
     * @return int
     */
    function GetBlue()
    {
        return $this->b;
    }
    
    /**
     * Returns white color
     * @return ArgbColor
     */
    static function White()
    {
        return self::FromRgb(0xff, 0xff, 0xff);
    }
    
    /**
     * Returns black color
     * @return ArgbColor
     */
    static function Black()
    {
        return self::FromRgb(0x00, 0x00, 0x00);
    }
}
