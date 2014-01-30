<?php

namespace Phine\Framework\Access\Base;
use Phine\Framework\Access\Interfaces as AccessInterfaces;
use Phine\Framework\System;
require_once __DIR__ . '/Action.php';

/**
 * Abstract base class for access management 
 */
abstract class Guard
{    
    /**
     *
     * @var AccessInterfaces\IAccessor
     */
    private $accessor;
    
    /**
     * Gets the current accesor
     * @return AccessInterfaces\IAccessor
     */
    function Accessor()
    {
        if (!$this->accessor)
        {
            $this->accessor = $this->CreateAccessor();
            $this->accessor->LoadCurrent();
        }
        return $this->accessor;
    }
    function Login($data)
    {
        $accessor = $this->Accessor();
        if (!$accessor->IsUndefined())
            throw new \Exception('Already logged in');
        
        return $accessor->Verify($data);
    }
    
    function Logout()
    {
        $accessor = $this->Accessor();
        $accessor->Undefine();
    }
    /**
     * Indicates if acion is allowed on object for the current accessor
     * @return GrantResult 
     */
    abstract function Grant(Action $action, $onObject);
    /**
     * 
     * Returns the current accessor
     * @return AccessInterfaces\IAccessor 
     */
    protected abstract function CreateAccessor();
    
    /**
     * Helper function checking if param $action is in $grantedActions
     * @param AccessBase\Action $action
     * @param array $grantedActions Array of actions that shall be granted
     * @return GrantResult Allowed if $action is in $grantedActions, NoAccess otherwise
     */
    protected function GrantActions(Action $action, array $grantedActions)
    {
        foreach ($grantedActions as $grantedAction)
        {
            if ((string)$action == (string)$grantedAction)
                return GrantResult::Allowed();
        }
        return GrantResult::NoAccess();
    }
}