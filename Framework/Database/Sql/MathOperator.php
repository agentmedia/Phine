<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\System\Enum;
class MathOperator extends Enum
{
    /**
     * The Addition operator "+" 
     * @return MathOperator
     */
    static function Plus()
    {
        return new self('+');
    }
    /**
     * The Subtraction operator "-" 
     * @return MathOperator
     */
    static function Minus()
    {
        return new self('-');
    }
    
    /**
     * The Multiplication operator "*" 
     * @return MathOperator
     */
    static function Times()
    {
        return new self('*');
    }
    
    /**
     * The Division operator "+" 
     * @return MathOperator
     */
    static function Divide()
    {
        return new self('/');
    }
}
