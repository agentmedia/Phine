<?php
namespace Phine\Framework\Database\Sql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

abstract class Object
{
    /**
     * Gets the current connection
     * @return DBInterfaces\IDatabaseConnection
     */
    public function Connection()
    {
        return $this->connection;
    }
    protected function __construct(DBInterfaces\IDatabaseConnection $connection)
    {
        $this->connection = $connection;
        $this->escaper = $connection->GetEscaper();
        $this->sqlLimiter = $connection->GetSqlLimiter();
    }
    
    /**
     * 
     * @var DBInterfaces\IDatabaseConnection
     */
    protected $connection;
    /**
     * 
     * @var DBInterfaces\IDatabaseEscaper
     */
    protected $escaper;
    
    /**
     * 
     * @var DBInterfaces\IDatabaseSqlLimiter
     */
    protected $sqlLimiter;
    
    /**
     * Returns sql condition for equality
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    protected function CreateConditionEquals(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::Equals($this->connection, $leftHandSide, $rightHandSide);
    }
    /**
     * Returns sql condition for greater than
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    protected function CreateConditionGT(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::GT($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for greater than equals
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    protected function CreateConditionGTE(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::GTE($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for less than
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    protected function CreateConditionLT(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::LT($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for less than equals
     * @param Selectable $leftHandSide The left hand side of equality check
     * @param Selectable $rightHandSide The right hand side of equality check
     * @return Condition
     */
    protected function CreateConditionLTE(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::LTE($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for none equality
     * @param Selectable $leftHandSide The left hand side of none equality check
     * @param Selectable $rightHandSide The right hand side of none equality check
     * @return Condition
     */
    protected function CreateConditionEqualsNot(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::EqualsNot($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns sql condition for is null
     * @param Selectable $selectable The field to check
     * @return Condition The sql condition object
     */
    protected function CreateConditionIsNull(Selectable $selectable)
    {
        return Condition::IsNull($this->connection, $selectable);
    }
    
    /**
     * Returns sql condition for not null comparison
     * @param Selectable $selectable The field to check
     * @return Condition The sql condition object
     */
    protected function CreateConditionIsNotNull(Selectable $selectable)
    {
        return Condition::IsNotNull($this->connection, $selectable);
    }
    
    /**
     * Returns conditon for LIKE comparison
     * @param Selectable $leftHandSide The left hand side of the LIKE expression
     * @param Selectable $rightHandSideThe The right hand side of the LIKE expression
     * @return Condition
     */
    protected function CreateConditionLike(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::Like($this->connection, $leftHandSide, $rightHandSide);
    }
    
    
    /**
     * Returns conditon for NOT LIKE comparison
     * @param Selectable $leftHandSide The left hand side of the NOT LIKE expression
     * @param Selectable $rightHandSideThe The right hand side of the NOT LIKE expression
     * @return Condition
     */
    protected function CreateConditionNotLike(Selectable $leftHandSide, Selectable $rightHandSide)
    {
        return Condition::NotLike($this->connection, $leftHandSide, $rightHandSide);
    }
    
    /**
     * Returns conditon for IN comparison
     * @param Selectable $leftHandSide The left hand side of the NOT LIKE expression
     * @param InList $inList The right hand side of the IN expression
     * @return Condition
     */
    protected function CreateConditionIn(Selectable $leftHandSide, InList $inList)
    {
        return Condition::In($this->connection, $leftHandSide, $inList);
    }
    
    /**
     * Returns conditon for NOT IN comparison
     * @param Selectable $leftHandSide The left hand side of the NOT LIKE expression
     * @param InList $inList The right hand side of the NOT IN expression
     * @return Condition
     */
    protected function CreateConditionNotIn(Selectable $leftHandSide, InList $inList)
    {
        return Condition::NotIn($this->connection, $leftHandSide, $inList);
    }
    /**
     * Creates a delete statement
     * @return Delete 
     */
    protected function CreateDelete(Table $table, Condition $condition = null, OrderList $orderList = null, $offset = 0, $count = 0)
    {
        return new Delete($this->connection, $table, $condition, $orderList, $offset, $count);
    }
    
    /**
     * Creates a sql field object
     * @return Field
     */
    protected function CreateField($name, $prefix = '', $alias = '')
    {
        return new Field($this->connection, $name, $prefix, $alias);
    }
    
    
    /**
     * Creates a sql function call
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionCall($functionName, array $params = array(), $alias = '', $distinctParams = false)
    {
        return new FunctionCall($this->connection, $functionName, $params, $alias, $distinctParams);
    }
    
    /**
     * Creates a sql function call
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionAvg(array $params = array(), $alias = '')
    {
        return FunctionCall::Avg($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql COS function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionCos(array $params, $alias = '')
    {
        return FunctionCall::Cos($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql SIN function
     * @param array $params The params of the sin function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionSin(array $params, $alias = '')
    {
        return FunctionCall::Sin($this->connection, $params, $alias);
    }
    
    
    /**
     * Creates a mathematical operation of two operands
     * @param MathOperator $operator
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    protected function CreateOperation(MathOperator $operator, Selectable $operand1, Selectable $operand2, $alias)
    {
        return new MathOperation($this->connection, $operator, $operand1, $operand2, $alias);
    }
    
    /**
     * Creates a mathematical addition of two operands
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    protected function CreateAddition(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateOperation(MathOperator::Plus(), $operand1, $operand2, $alias);
    }
    
    /**
     * Creates a mathematical subtraction of two operands
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    protected function CreateSubtraction(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateOperation(MathOperator::Minus(), $operand1, $operand2, $alias);
    }
    
    
    /**
     * Creates a mathematical multiplication of two operands
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    protected function CreateMultiplication(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateOperation(MathOperator::Times(), $operand1, $operand2, $alias);
    }
    
    /**
     * Creates a mathematical division of two operands
     * @param Selectable $operand1
     * @param Selectable $operand2
     * @param string $alias
     * @return MathOperation
     */
    protected function CreateDivision(Selectable $operand1, Selectable $operand2, $alias = '')
    {
        return $this->CreateOperation(MathOperator::Divide(), $operand1, $operand2, $alias);
    }
    
    
    /**
     * Creates a sql SIN function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionConcat(array $params, $alias = '')
    {
        return FunctionCall::Concat($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql arcus cosinus function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionACos(array $params, $alias = '')
    {
        return FunctionCall::ACos($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql arcus sinus function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionASin(array $params, $alias = '')
    {
        return FunctionCall::ASin($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql MAX function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionMax(array $params, $alias = '')
    {
        return FunctionCall::Max($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql MIN function* 
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionMin(array $params, $alias = '')
    {
        return FunctionCall::Min($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql MIN function
     * @param array $params The params of the abs function
     * @param alias The alias of the function
     * @distinctParams If true, prepends DISTINCT to the params
     * @return FunctionCall
     */
    protected function CreateFunctionCount(array $params, $alias = '', $distinctParams = false)
    {
        return FunctionCall::Count($this->connection, $params, $alias, $distinctParams);
    }
    
    /**
     * Creates a sql ABS function
     * @param array $params The params of the abs function
     * @alias The alias of the function
     * @return FunctionCall
     */
    protected function CreateFunctionAbs(array $params, $alias = '')
    {
        return FunctionCall::Abs($this->connection, $params, $alias);
    }
    
    
    protected function CreateFunctionSum(array $params, $alias = '')
    {
        return FunctionCall::Sum($this->connection, $params, $alias);
    }
    
    /**
     * Creates a sql group list
     * @param Selectable $selectable The first grouping selectable
     * @return GroupList 
     */
    protected function CreateGroupList(Selectable $selectable)
    {
        return new GroupList($this->connection, $selectable);
    }
    
    /**
     * Creates a sql IN list
     * @param Selectable $selectable The first selectable for IN right hand side
     * @return InList 
     */
    protected function CreateInList(Selectable $selectable)
    {
        return new InList($this->connection, $selectable);
    }
    
    /**
     * Creates a new INSERT statement
     * @param Table $table
     * @param SetList $setList
     * @return Insert 
     */
    protected function CreateInsert(Table $table, SetList $setList)
    {
        return new Insert($this->connection, $table, $setList);
    }
    
    /**
     * Creates a sql JOIN initially from a table
     * @param Table $table
     * @return Join 
     */
    protected function CreateJoin(Table $table)
    {
        return new Join($this->connection, $table);
    }
    
    
    /**
     * Creates a sql order object for sorting ascendingly
     * @param Selectable $selectable
     * @return Order
     */
    protected function CreateOrderAsc(Selectable $selectable)
    {
        return Order::Asc($this->connection, $selectable);
    }
    
    /**
     * Creates a sql order object for sorting descendingly
     * @param Selectable $selectable
     * @return Order
     */
    protected function CreateOrderDesc(Selectable $selectable)
    {
        return Order::Desc($this->connection, $selectable);
    }
    
    /**
     * Creates a sql order list object for sorting
     * @param Order $order
     * @return OrderList
     */
    protected function CreateOrderList(Order $order)
    {
        return new OrderList($this->connection, $order);
    }
    /**
     * Creates a new placeholder object
     * @return Placeholder 
     */
    protected function CreatePlaceholder()
    {
        return new Placeholder($this->connection);
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
    protected function CreateSelect($distinct, SelectList $selectList, Source $source, Condition $condition = null, OrderList $orderList = null, GroupList $groupList = null, $offset = 0, $count = 0)
    {
        return new Select($this->connection, $distinct, $selectList, $source, $condition, $orderList, $groupList, $offset, $count); 
    }
    
    /**
     * Creates a sql select list
     * @param Selectable $selectable
     * @return SelectList 
     */
    protected function CreateSelectList(Selectable $selectable)
    {
        return new SelectList($this->connection, $selectable);
    }
    
    /**
     * Creates a new set list for update or insert
     * @param string $fieldName
     * @param Value $value
     * @return SetList 
     */
    protected function CreateSetList($fieldName, Value $value)
    {
        return new SetList($this->connection, $fieldName, $value);
    }
    /**
     * Creates a sql table object
     * @param string $name
     * @param array[string] $fieldNames
     * @param string $alias
     * @return Table 
     */
    protected function CreateTable($name, array $fieldNames, $alias = '')
    {
        return new Table($this->connection, $name, $fieldNames, $alias);
    }
    
    /**
     * Creates a sql update object
     * @param Table $table
     * @param SetList $setList
     * @param Condition $condition
     * @param int $offset Limits the update to datasets beginning on offset
     * @param int $count Limits the update to this amount of datasets
     * @return \Phine\Framework\Database\Sql\Update 
     */
    protected function CreateUpdate(Table $table, SetList $setList, Condition $condition = null, OrderList $orderBy = null, $offset = 0, $count = 0)
    {
        return new Update($this->connection, $table, $setList, $condition, $orderBy, $offset, $count);
    }
    
    /**
     *
     * @param mixed $value A primitive type or a type implementing __toString or null
     */
    protected function CreateValue($value)
    {
        return new Value($this->connection, $value);
    }
}