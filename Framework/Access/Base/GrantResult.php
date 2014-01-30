<?php

namespace Phine\Framework\Access\Base;

use Phine\Framework\System\Enum as Enum;

class GrantResult extends Enum
{
    /**
     * Result if access is allowed
     * @return GrantResult
     */
    static function Allowed()
    {
        return new self('Allowed');
    }
    
    /**
     * Result if accessor is logged in, but object not accessible
     * @return GrantResult
     */
    static function NoAccess()
    {
        return new self('NoAccess');
    }
    
    /**
     * Result if accessor is not logged in, but at least login is required
     * @return GrantResult
     */
    static function LoginRequired()
    {
        return new self('LoginRequired');
    }
}

