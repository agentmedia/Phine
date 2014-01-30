<?php

namespace Phine\Framework\Validation;
require_once __DIR__ . '/Validator.php';
use Phine\Framework\Access\Base as AccessBase;

class Access extends Validator
{
    private $guard;
    private $verifyOnly = false;
    const NotGranted = 'Validation.Access.NotGranted';
    const NotVerified = 'Validation.Access.NotVerified';
    
    function __construct(AccessBase\Guard $guard, $verifyOnly = false, $errorLabelPrefix = '')
    {
        $this->guard = $guard;
        $this->verifyOnly = $verifyOnly;
        parent::__construct($errorLabelPrefix);
    }

    public function Check($data)
    {
        if ($this->verifyOnly)
            return $this->CheckVerified($data);
        else
            return $this->TryLogin($data);
    }
    
    private function CheckVerified($data)
    {
        if (!$this->guard->Accessor()->Verify($data, true))
        {
            $this->error = self::NotVerified;
            return false;
        }
        return true;
    }
    
    private function TryLogin($data)
    {
        if (!$this->guard->Login($data))
        {
            $this->error = self::NotGranted;
            return false;
        }
        return true;
    }
}
