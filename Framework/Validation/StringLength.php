<?php

namespace Phine\Framework\Validation;

require_once  __DIR__ . '/Validator.php';
use Phine\Framework\System;
/**
 * Provides methods to validate string length 
 */
class StringLength extends Validator
{
    private $minLength;
    private $maxLength;
    
    const TooShort = 'Validation.StringLength.TooShort_{0}';
    const TooLong = 'Validation.StringLength.TooLong_{0}';
    
    /**
     * Creates a validator for a maximun string length
     * @param int $maxLength The maximum length
     * @param bool $trimValue If true, the value is trimmed before length is measured
     * @param string $errorLabelPrefix Label prefix for errors
     * @return StringLength
     */
    static function MaxLength($maxLength, $trimValue = true, $errorLabelPrefix = '')
    {
        return new self(0, $maxLength, $trimValue, $errorLabelPrefix);
    }
    
    /**
     * Creates a validator for a minimum string length
     * @param int $minLength The minimum length
     * @param bool $trimValue If true, the value is trimmed before length is measured
     * @param string $errorLabelPrefix Label prefix for errors
     * @return StringLength
     */
    static function MinLength($minLength, $trimValue = true, $errorLabelPrefix = '')
    {
        return new self($minLength, -1, $trimValue, $errorLabelPrefix);
    }
    /**
    * Returns a validator checking if value is not empty
    * @bool $errorLabelPrefix
    * @string $errorLabelPrefix
    * @return StringLength
    */
    static function NotEmpty($trimValue = true, $errorLabelPrefix = '')
    {
        return new self(1, -1, $trimValue, $errorLabelPrefix);
    }
        
    /**
    * Creates a new length validator
    * @param int $minLength
    * @param int $maxLength
    * @param bool $trimValue 
    * @param string errorLabelPrefix;
    */
    function __construct($minLength, $maxLength, $trimValue, $errorLabelPrefix = '')
    {
        if ($maxLength >= 0 &&  $maxLength < $minLength)
            throw new \InvalidArgumentException('max length must be greater than or equal min length');
        
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
        parent::__construct($errorLabelPrefix, $trimValue);
    }   
    
    private function IsTooShort($length)
    {
        return $length < $this->minLength;
    }
    
    private function IsTooLong($length)
    {
        return $this->maxLength > 0 && $length > $this->maxLength;
    }

    function Check($value)
    {
            $value = (string)$value;
            
            if ($this->trimValue)
                $value = trim($value);
            
            $length = System\String::Length($value);

            if ($this->IsTooShort($length))
                $this->error = self::TooShort;
            
            else if ($this->IsTooLong($length))
                $this->error = self::TooLong;
            
            else
                $this->error = '';
            
            return $this->error == '';
    }
        protected function ErrorParams()
        {
            $params = array();
            if ($this->minLength > 0)
                $params[] = $this->minLength;
            
            if ($this->maxLength > 0)
                $params[] = $this->maxLength;
            return $params;
        }
}