<?php
namespace Phine\Framework\Validation;
require_once __DIR__ . '/Validator.php';

class InArray extends Validator
{
    /**
     * @var array Allowed values 
     */
    private $values;
    
    /**
     * @var string The error label
     */
    const NotInArray = 'Validation.InArray.NotInArray';
    /**
     * Creates a validator checking for specific values
     * @param array $values An array of allowed values
     * @param string $errorLabelPrefix An error label prefix
     */
    function __construct(array $values, $errorLabelPrefix = '')
    {
        $this->values = $values;
        $this->errorLabelPrefix = $errorLabelPrefix;
    }
    
    /**
     * Checks if the value is in the array of allowed values
     * @param string $value
     * @return bool
     */
    public function Check($value)
    {
        if (!\in_array($value, $this->values))
            $this->error = self::NotInArray;
        
        return $this->error == '';
    }
}
