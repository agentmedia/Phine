<?php

namespace Phine\Framework\Access\Base;
use Phine\Framework\System;

class Action extends System\Enum
{
    static function Read()
    {
        return new self('Read');
    }
    
    static function Create()
    {
        return new self('Create');
    }
    static function Edit()
    {
        return new self('Edit');
    }
    
    static function Delete()
    {
        return new self('Delete');
    }
    
    static function UseIt()
    {
        return new self('UseIt');
    }
}

