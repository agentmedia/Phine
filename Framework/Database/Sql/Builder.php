<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;
require_once __DIR__ . '/Object.php';

class Builder extends Object
{
    function __construct(DBInterfaces\IDatabaseConnection $connection)
    {
        parent::__construct($connection);
    }
    
    /**
     * Returns sql condition for equality
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    function Equals(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionEquals($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for greater than
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    function GT(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionGT($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for greater than equals
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    function GTE(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionGTE($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for less than
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    function LT(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionLT($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for less than equals
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    function LTE(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionLTE($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for none equality
     * @param Selectable $leftHandSide The left hand side of none equality check
     * @param Selectable $rightHandSide The right hand side of none equality check
     * @return Condition
     */
    function EqualsNot(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionEqualsNot($leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for is null
     * @param Selectable $selectable The field to check
     * @return Condition The sql condition object
     */
    function IsNull(Selectable $selectable)
    {
        return parent::CreateConditionIsNull($selectable);
    }
    
    /**
     * Returns sql condition for IN
     * @param Selectable $selectable The field to check
     * @param InList $inList The list of selectables in the IN list
     * @return Condition The sql condition object
     */
    function In(Selectable $selectable, InList $inList)
    {
        return parent::CreateConditionIn($selectable, $inList);
    }
    
    
    /**
     * Returns sql condition for NOT IN
     * @param Selectable $selectable The field to check
     * @param InList $inList The list of selectables in the NOT IN list
     * @return Condition The sql condition object
     */
    function NotIn(Selectable $selectable, InList $inList)
    {
        return parent::CreateConditionNotIn($selectable, $inList);
    }
    
    /**
     * Returns sql condition for not null comparison
     * @param Selectable $selectable The field to check
     * @return Condition The sql condition object
     */
    function IsNotNull(Selectable $selectable)
    {
        return parent::CreateConditionIsNotNull($selectable);
    }
    
    /**
     * Returns conditon for LIKE comparison
     * @param Selectable $leftHandSide The left hand side of the LIKE expression
     * @param Selectable $rightHandSideThe The right hand side of the LIKE expression
     * @return Condition
     */
    function Like(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionLike($leftHandSide, $rightHandSide);
    }
    
    
    /**
     * Returns conditon for NOT LIKE comparison
     * @param Selectable $leftHandSide The left hand side of the NOT LIKE expression
     * @param Selectable $rightHandSideThe The right hand side of the NOT LIKE expression
     * @return Condition
     */
    function NotLike(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return parent::CreateConditionNotLike($leftHandSide, $rightHandSide);
    }
    /**
     * Creates a delete statement
     * @return Delete 
     */
    function Delete(Table $table, Condition $condition = null, OrderList $orderList = null, $offset = 0, $count = 0)
    {
        return parent::CreateDelete($table, $condition, $orderList, $offset, $count);
    }
    
    /**
     * Creates a sql field object
     * @return Field
     */
    function Field($name, $prefix = '', $alias = '')
    {
        return parent::CreateField($name, $prefix, $alias);
    }
    
    
    /**
     * Creates a sql function call
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionCall($functionName, array $params = array(), $alias = '', $distinctParams = false)
    {
        return parent::CreateFunctionCall($functionName, $params, $alias, $distinctParams);
    }
    
    /**
     * Creates a sql AVG function
     * @param array $params The params of the average function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionAvg(array $params, $alias = '')
    {
        return parent::CreateFunctionAvg($params, $alias);
    }
    /**
     * Creates a sql COS function
     * @param array $params The params of the cos function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionCos(array $params, $alias = '')
    {
        return parent::CreateFunctionCos($params, $alias);
    }
    
    /**
     * Creates a sql SIN function
     * @param array $params The params of the sin function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionSin(array $params, $alias = '')
    {
        return parent::CreateFunctionSin($params, $alias);
    }
    
    /**
     * Creates a sql arcus cosinus function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionACos(array $params, $alias = '')
    {
        return parent::CreateFunctionACos($params, $alias);
    }
    
    /**
     * Creates a sql concat function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionConcat(array $params, $alias = '')
    {
        return parent::CreateFunctionConcat($params, $alias);
    }
    
    /**
     * Creates a sql arcus sinus function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionASin(array $params, $alias = '')
    {
        return parent::CreateFunctionASin($params, $alias);
    }
    
    /**
     * Creates a sql MAX function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionMax(array $params, $alias = '')
    {
        return parent::CreateFunctionMax($params, $alias);
    }
    
    /**
     * Creates a sql MIN function* 
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionMin(array $params, $alias = '')
    {
        return parent::CreateFunctionMin($params, $alias);
    }
    
    /**
     * Creates a sql SUM function
     * @param array $params The params of the sum function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    function FunctionSum(array $params, $alias = '')
    {
        return parent::CreateFunctionSum($params, $alias);
    }
    
    /**
     * Creates a mathematical addition in sql (conjunction with "+")
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    function Addition(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateAddition($operand1, $operand2, $alias);
    }
    
     /**
     * Creates a mathematical subtraction in sql (conjunction with "-")
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    function Subtraction(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateSubtraction($operand1, $operand2, $alias);
    }
    
     /**
     * Creates a mathematical multiplication in sql (conjunction with "*")
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    function Multiplication(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateMultiplication($operand1, $operand2, $alias);
    }
    
    /**
     * Creates a mathematical division in sql (conjunction with "/")
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    function Division(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateDivision($operand1, $operand2, $alias);
    }
    
    /**
     * Creates a sql MIN function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @distinctParams If true, prepends DISTINCT to the params
     * @return FunctionCall
     */
    function FunctionCount(array $params, $alias = '', $distinctParams = false)
    {
        return parent::CreateFunctionCount($params, $alias, $distinctParams);
    }
    
    /**
     * Creates a sql ABS function
     * @param array $params The params of the abs function
     * @alias The alias of the function
     * @return FunctionCall
     */
    function FunctionAbs(array $params, $alias = '')
    {
        return parent::CreateFunctionAbs($params, $alias);
    }
    
    /**
     * Creates a sql group list
     * @param Selectable $selectable The first grouping selectable
     * @return GroupList 
     */
    function GroupList(Selectable $selectable)
    {
        return parent::CreateGroupList($selectable);
    }
    
    /**
     * Creates a sql IN list
     * @param Selectable $selectable The first selectable for IN right hand side
     * @return InList 
     */
    function InList(Selectable $selectable)
    {
        return parent::CreateInList($selectable);
    }
    
    /**
     * Creates a new INSERT statement
     * @param Table $table
     * @param SetList $setList
     * @return Insert 
     */
    function Insert(Table $table, SetList $setList)
    {
        return parent::CreateInsert($table, $setList);
    }
    
    /**
     * Creates a sql JOIN initially from a table
     * @param Table $table
     * @return Join 
     */
    function Join(Table $table)
    {
        return parent::CreateJoin($table);
    }
    
    
    /**
     * Creates a sql order object for sorting ascendingly
     * @param Selectable $selectable
     * @return Order
     */
    function OrderAsc(Selectable $selectable)
    {
        return parent::CreateOrderAsc($selectable);
    }
    
    /**
     * Creates a sql order object for sorting descendingly
     * @param Selectable $selectable
     * @return Order
     */
    function OrderDesc(Selectable $selectable)
    {
        return parent::CreateOrderDesc($selectable);
    }
    
    /**
     * Creates a sql order list object for sorting
     * @param Order $order
     * @return OrderList
     */
    function OrderList(Order $order)
    {
        return parent::CreateOrderList($order);
    }
    /**
     * Creates a new placeholder object
     * @return Placeholder 
     */
    function Placeholder()
    {
        return parent::CreatePlaceholder();
    }
    
    /**
     * Creates a sql select object
     * @param \bool $distinct
     * @param SelectList $selectList
     * @param Source $source
     * @param Condition $condition
     * @param OrderList $orderList
     * @param GroupList $groupList
     * @param int $offset
     * @param int $count
     * @return Select 
     */
    function Select($distinct, SelectList $selectList, Source $source, Condition $condition = null, OrderList $orderList = null, GroupList $groupList = null, $offset = 0, $count = 0)
    {
        return parent::CreateSelect($distinct, $selectList, $source, $condition, $orderList, $groupList, $offset, $count); 
    }
    
    /**
     * Creates a sql select list
     * @param Selectable $selectable
     * @return SelectList 
     */
    function SelectList(Selectable $selectable)
    {
        return parent::CreateSelectList($selectable);
    }
    
    /**
     * Creates a new set list for update or insert
     * @param string $fieldName
     * @param Value $value
     * @return SetList 
     */
    function SetList($fieldName, Value $value)
    {
        return parent::CreateSetList($fieldName, $value);
    }
    
    /**
     * Creates a sql table object
     * @param string $name
     * @param array[string] $fieldNames
     * @param string $alias
     * @return Table 
     */
    function Table($name, array $fieldNames, $alias = '')
    {
        return parent::CreateTable($name, $fieldNames, $alias);
    }
    
    /**
     * Creates a sql update object
     * @param Table $table
     * @param SetList $setList
     * @param Condition $condition
     * @param OrderList $orderBy
     * @param int $offset Limits the update to datasets beginning on offset
     * @param int $count Limits the update to this amount of datasets
     * @return Update 
     */
    function Update(Table $table, SetList $setList, Condition $condition = null, OrderList $orderBy = null, $offset = 0, $count = 0)
    {
        return parent::CreateUpdate($table, $setList, $condition, $orderBy, $offset, $count);
    }
    
    /**
     * Creates a sql value
     * @param mixed $value A primitive type or a type implementing __toString or null
     */
    function Value($value)
    {
        return parent::CreateValue($value);
    }
}
