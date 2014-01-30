<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\System;
class JoinType extends System\Enum
{   
    /**
     * A Left Join obect
     * @return JoinType
     */
    static function Left()
    {
        return new self('LEFT OUTER JOIN');
    }
    /**
     * A Right Join obect
     * @return JoinType
     */
    static function Right()
    {
        return new self('RIGHT OUTER JOIN');
    }
    
    /**
     * An Inner Join object
     * @return JoinType
     */
    static function Inner()
    {
        return new self('INNER JOIN');
    }
    
    /**
     * A full outer join type object
     * @return JoinType
     */
    static function Full()
    {
        return new self('FULL OUTER JOIN');
    }
}