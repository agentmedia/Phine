<?php
namespace Phine\Framework\Validation;
use Phine\Framework\System\Date;

class DateCheck extends Validator
{
    /**
     *
     * @var string
     */
    private $format;
    
    /**
     *
     * @var Date
     */
    private $minDate = null;
    
    /**
     *
     * @var Date
     */
    private $maxDate = null;
    
    const TooEarly = 'Validation.DateCheck.TooEarly_{0}';
    
    const TooLate = 'Validation.DateCheck.TooLate_{0}';
    
    const ParseFailed = 'Validation.DateCheck.ParseFailed_{0}';
    
    function __construct($format, Date $minDate = null, Date $maxDate = null, $errorLabelPrefix = '', $trimValue = true)
    {
        $this->format = $format;
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
        
        parent::__construct($errorLabelPrefix, $trimValue);
    }
    public function Check($value)
    {
        $this->error = '';
        
        $dt = Date::Parse($this->format, $value);
        
        if (!$dt)
            $this->error = self::ParseFailed;
        else if ($this->maxDate && $dt->IsAfter($this->maxDate))
            $this->error = self::TooLate;
        else if ($this->minDate && $dt->IsBefore($this->minDate))
            $this->error = self::TooEarly;
        
        return $this->error == '';
    }
    
    function ErrorParams()
    {
        switch ($this->error)
        {
            case self::ParseFailed:
                return array($this->format);
                
            case self::TooEarly:
                return array($this->minDate->ToString($this->format));
            
            case self::TooLate:
                return array($this->maxDate->ToString($this->format));
        }
        return parent::ErrorParams();
    }
    
}
