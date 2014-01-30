<?php

namespace Phine\Framework\Sitemap\Enums;

use Phine\Framework\System\Enum;

/**
 * Change frequency of a web page
 */
class ChangeFrequency extends Enum
{
    /**
     * Web page changes always
     * @return ChangeFrequency
     */
    static function Always()
    {
        return new self('always');
    }
    
    /**
     * Web page changes hourly
     * @return ChangeFrequency
     */
    static function Hourly()
    {
        return new self('hourly');
    }
    
    
    /**
     * Web page changes daily
     * @return ChangeFrequency
     */
    static function Daily()
    {
        return new self('daily');
    }
    
    /**
     * Web page changes weekly
     * @return ChangeFrequency
     */
    static function Weekly()
    {
        return new self('weekly');
    }
    
    /**
     * Web page changes monthly
     * @return ChangeFrequency
     */
    static function Monthly()
    {
        return new self('monthly');
    }
    
    /**
     * Web page changes yearly
     * @return ChangeFrequency
     */
    static function Yearly()
    {
        return new self('yearly');
    }
    
    /**
     * Web page changes never
     * @return ChangeFrequency
     */
    static function Never()
    {
        return new self('never');
    }
}
