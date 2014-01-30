<?php
namespace Phine\Framework\Validation;
use Phine\Framework\Wording\Worder;
abstract class Validator implements Interfaces\IValidator
{
    
    protected $trimValue = true;
    protected function __construct($errorLabelPrefix = '', $trimValue = true)
    {
        $this->errorLabelPrefix = $errorLabelPrefix;
        $this->trimValue = $trimValue;
    }
    
    /**
     * The current error message label.
     * @var string
     */
    protected $error = '';
    
    /**
     * The error label prefix
     * @var string
     */
    protected $errorLabelPrefix = '';
    
    /**
     * Sets an individual prefix to error labels to customize error messages
     * @param string $prefix 
     */
    public function SetErrorLabelPrefix($prefix)
    {
        $this->errorLabelPrefix = $prefix;
    }
    
    /**
     * Gets the error label prefix
     * @return string
     */
    public function GetErrorLabelPrefix()
    {
        return $this->errorLabelPrefix;
    }
    
    function GetError(array $params = array())
    {
            $allParams = array_merge($params, $this->ErrorParams());
            if ($this->error)
                return Worder::ReplaceArgs($this->errorLabelPrefix . $this->error, $allParams);

            return '';
    }
    /**
    * Can be overriden to get internal params for error message.
    * @return array Returns an empty array here in the base class
    */
    protected function ErrorParams()
    {
        return array();
    }
}