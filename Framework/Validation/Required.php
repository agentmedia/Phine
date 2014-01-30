<?php
namespace Phine\Framework\Validation;
use Phine\Framework\System\String;
require_once __DIR__ . '/Validator.php';
class Required extends Validator
{
    public function __construct($errorLabelPrefix = '', $trimValues = true)
    {
        parent::__construct($errorLabelPrefix, $trimValues);
    }
    const Missing ='Validation.Required.Missing';
    public function Check($value)
    {
        $this->error = '';
        if ($this->trimValue)
            $value = String::Trim($value);
            
        if ($value === '')
            $this->error = self::Missing;
        
        return $this->error == '';
    }
}
