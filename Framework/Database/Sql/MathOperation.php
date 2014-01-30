<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;

class MathOperation extends Selectable
{
    /**
     *
     * @var MathOperator
     */
    private $operator;
    /**
     *
     * @var array
     */
    private $operands;
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, MathOperator $operator, Selectable $operand1, Selectable $operand2, $alias = '')
    {
        parent::__construct($connection, $alias);
        $this->operator = $operator;
        $this->operands = array($operand1, $operand2);
    }
    /**
     * Adds an operand to the math operation
     * @param Selectable $operand
     */
    function AddOperand(Selectable $operand)
    {
        $this->operands[] = $operand;
    }
    
    
    function FullName()
    {
        $strParams = \join($this->operator, $this->operands);        
        return '(' . $strParams . ')';
    }
}
