<?php
namespace Phine\Framework\Validation;
require_once __DIR__ . '/Validator.php';

class CompareCheck extends Validator
{
    /**
     *
     * @var string
     */
    private $compareValue;
    /**
     *
     * @var bool 
     */
    private $equalsNot = false;
    
            
    const EqualsNot = 'Validation.CompareCheck.EqualsNot_{0}';
    const Equals = 'Validation.CompareCheck.Equals_{0}';
    
    protected function __construct($compareValue, $equalsNot = false, $errorLabelPrefix = '', $trimValue = true)
    {
        $this->compareValue = $compareValue;
        $this->equalsNot = $equalsNot;
        parent::__construct($errorLabelPrefix, $trimValue);
    }
    
    static function Equals($compareValue, $errorLabelPrefix = '', $trimValue = true)
    {
        return new self($compareValue, false, $errorLabelPrefix, $trimValue);
    }
    
    static function EqualsNot($compareValue, $errorLabelPrefix = '', $trimValue = true)
    {
        return new self($compareValue, true, $errorLabelPrefix, $trimValue);
    }
    
    function Check($value)
    {
       if ($this->equalsNot && $value == $this->compareValue)
           $this->error = self::Equals;
       
       else if (!$this->equalsNot && $value != $this->compareValue)
           $this->error = self::EqualsNot;
       
       return $this->error == '';
    }
    
    function ErrorParams()
    {
        if ($this->error)
            return array($this->compareValue);
        
        return parent::ErrorParams();
    }
}