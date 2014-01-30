<?php
namespace Phine\Framework\Validation;
require_once __DIR__ . '/Validator.php';

class PhpFilter extends Validator
{
    
    const InvalidEMail = 'Validation.PhpFilter.InvalidEmail';
    const InvalidUrl = 'Validation.PhpFilter.InvalidUrl';
    private $constant;
    /**
     * _constructs a new php filter value
     * @param int $constant Must be one of the FILTER_VALIDATE_ constants
     * @param string $errorLabelPrefix
     */
    protected function __construct($constant, $errorLabelPrefix)
    {
        $this->constant = $constant;
        parent::__construct($errorLabelPrefix);
    }
    
    /**
     * A validator for e-mails
     * @return PhpFilter 
     */
    public static function EMail($errorLabelPrefix = '')
    {
        return new self(FILTER_VALIDATE_EMAIL, $errorLabelPrefix);
    }
    /**
     * A Validator for urls
     * @return PhpFilter 
     */
    public static function Url($errorLabelPrefix = '')
    {
        return new self(FILTER_VALIDATE_URL, $errorLabelPrefix);
    }
    /**
     *
     * @param string $value
     * @return bool Returns true if filtering was successfull
     */
    public function Check($value)
    {
        $this->error = '';
        if (filter_var($value, $this->constant) === false)
        {
            if ($this->constant == FILTER_VALIDATE_URL)
                $this->error = self::InvalidUrl;
            
            else if ($this->constant == FILTER_VALIDATE_EMAIL)
                $this->error = self::InvalidEMail;
        }
        return $this->error == '';
    }
}
