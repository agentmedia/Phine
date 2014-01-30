<?php
namespace Phine\Framework\Validation;
use Phine\Framework\System\String;
require_once __DIR__ . '/Validator.php';

class Integer extends Validator
{
    private $min = -1;
    private $max = PHP_INT_MAX;
    
    const HasNonDigits = 'Validation.Integer.HasNonDigits';
    const ExceedsMin = 'Validation.Integer.ExceedsMin_{0}';
    const ExceedsMax = 'Validation.Integer.ExceedsMax_{1}';
    
    /**
     * Validator for integers greater than or equal 0.
     * @param string $errorLabelPrefix
     * @return Integer
     */
    static function PositiveOrNull($errorLabelPrefix = '')
    {
        return new self(0, PHP_INT_MAX, $errorLabelPrefix);    
    }
    
    /**
     * Validator for integers greater than 0.
     * @param string $errorLabelPrefix
     * @return Integer
     */
    static function Positive($errorLabelPrefix = '')
    {
        return new self(1, PHP_INT_MAX, $errorLabelPrefix);    
    }
    
    /**
     * Validator for integers less than 0.
     * @return Integer
     */
    static function Negative($errorLabelPrefix = '')
    {
        return new self(-PHP_INT_MAX, -1, $errorLabelPrefix);
    }
    
    /**
     * Validator for negative integers less than or equal 0.
     * @return Integer
     */
    static function NegativeOrNull($errorLabelPrefix = '')
    {
        return new self(-PHP_INT_MAX, 0, $errorLabelPrefix);
    }
    /**
     * Creates an Integer validator
     * @param int $min
     * @param int $max 
     * @param string $errorLabelPrefix
     */
    function __construct($min, $max = PHP_INT_MAX, $errorLabelPrefix = '')
    {
        $this->min = $min;
        $this->max = $max;
        parent::__construct($errorLabelPrefix);
    }
    /**
     * Checks the given value
     * @param string $value
     * @return bool True if check was passed
     */
    function Check($value)
    {
        if (!ctype_digit(String::TrimLeft($value, '-')))
            $this->error = self::HasNonDigits;
        else
        {
            $floatValue = (float)$value;
            if ($floatValue < $this->min)
            {
                 $this->error = self::ExceedsMin;
            }
            else if ($floatValue > $this->max)
                $this->error = self::ExceedsMax;
        }
        return $this->error == '';
    }
    
    function ErrorParams()
    {
        if ($this->error == self::ExceedsMax)
            return array($this->max);
        
        else if ($this->error == self::ExceedsMin)
            return array($this->min);
        
        return parent::ErrorParams();
    }
}