<?php
namespace Phine\Framework\Database\Objects;
use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\Database\Sql;

abstract class TableSchema
{
    /**
     * @return DBInterfaces\IDatabaseConnection 
     */
    abstract function Connection();
    /**
     *
     * @var Sql\Builder
     */
    private $builder;
    
    /**
     * Gets a sql builder for handling the database
     * @return Sql\Builder
     */
    public function SqlBuilder()
    {
        if (!$this->builder)
            $this->builder = new Sql\Builder($this->Connection());
        
        return $this->builder;
    }
    
    private $keyField;
    
    /**
     * Gets the key field name.
     * @return string
     * @throws \Exception if no key field exists
     */
    final function KeyField()
    {
        if (!$this->keyField)
        {
            $fieldMappers = $this->FieldMappers();
            foreach ($fieldMappers as $name=>$mapper)
            {
                if ($mapper->FieldInfo()->IsPrimary())
                {
                    $this->keyField = $name;
                    break;
                }
            }
            if (!$this->keyField)
                throw new \Exception('No (primary) key field found in ' . $this->TableName());
        }
        return $this->keyField;
    }

    /**
     * Create an Sql\Table for constructing queries
     * @param string $alias
     * @param array $fieldAliases
     * @return Sql\Table
     */
    final function Table($alias = '', array $fieldAliases = array())
    {
        $sql = $this->SqlBuilder();
        return $sql->Table($this->TableName(), $this->FieldNames($fieldAliases), $alias);
    }
    
    /**
     * Return field names of related table as needed for Sql\Table construction
     * @param array $aliases An array with field names as keys and aliases as values
     * @return array
     */
    final function FieldNames(array $aliases = array())
    {
        $fieldNames = array_keys($this->FieldMappers());
        $result = array();
        foreach ($fieldNames as $fieldName)
        {
            if (array_key_exists($fieldName, $aliases))
                $result[$aliases[$fieldName]] = $fieldName;
            else
                $result[] = $fieldName;
        }
        return $result;
    }
    
    /**
     * Returns field mapper object for the given field name.
     * @param string $name
     * @return FieldMapper
     * @throws \Exception if field does not exist in this schema.
     */
    final function FieldMapper($name)
    {
        $fieldMappers = $this->FieldMappers();
        if (!array_key_exists($name, $fieldMappers))
            throw new \InvalidArgumentException($name . ' is not a field of ' . $this->TableName());
        
        return $fieldMappers[$name];
    }
    
    /**
     * Returns all object by reading the reader to its end.
     * @param DBInterfaces\IDatabaseReader $reader
     * @return array DatabaseObject
     */
    final function FetchFromReader(DBInterfaces\IDatabaseReader $reader)
    {
        $objects = array();
        while($reader->Read())
        {
            $object = $this->CreateInstance($reader->ByName($this->KeyField()));
            $object->InitFromReader($reader);
            $objects[] = $object;
        }
        $reader->Close();
        return $objects;
    }
    
    /**
     * Returns one object by reading the reader.
     * @param $reader
     * @return DBInterfaces\DatabaseObject
     */
    public final function OneFromReader(DBInterfaces\IDatabaseReader $reader)
    {
        $object = null;
        if($reader->Read())
        {
            $object = $this->CreateInstance($reader[$this->KeyField()]);
            $object->InitFromReader($reader);
        }
        $reader->Close();
        return $object;
    }
    /**
     * Fetches table objects.
     * @param $distinct
     * @param Sql\Condition $where
     * @param Sql\OrderList $orderBy
     * @param Sql\GroupList $groupBy
     * @param int|null $offset
     * @param int|null $count
     * @param Sql\Join $join
     * @param Sql\JoinType $joinType
     * @param $joinCondition
     * @return array[TableObject]
     */
    public final function Fetch($distinct = false, Sql\Condition $where = null, Sql\OrderList $orderBy = null, Sql\GroupList $groupBy = null, $offset = 0, $count = null, Sql\Join $join = null, Sql\JoinType $joinType = null, Sql\Condition $joinCondition = null)
    {
        $t = $this->Table();
        $src = $t;
        if ($join)
        {
            if (!$joinType || !$joinCondition)
                throw new \InvalidArgumentException("If join is given, a join type and join condition are also required.");
            
            $src = $join->Join($t, $joinType, $joinCondition, true);
        }
        $sql = $this->SqlBuilder();
        $select = $sql->Select($distinct, $t->ToSelectList(), $src, $where, $orderBy, $groupBy, $offset, $count);
        
        $reader = $this->Connection()->ExecuteQuery((string)$select);
        return $this->FetchFromReader($reader);
    }
    
    /**
     * 
     * @param Sql\Condition $where
     * @param Sql\OrderList $orderBy
     * @param Sql\GroupList $groupBy
     * @param Sql\Join $join
     * @param Sql\JoinType $joinType
     * @param Sql\Condition $joinCondition
     * @return TableObject
     */
    public final function First(Sql\Condition $where = null, Sql\OrderList $orderBy = null, Sql\GroupList $groupBy = null, Sql\Join $join = null, Sql\JoinType $joinType = null, Sql\Condition $joinCondition = null)
    {
        $objects = $this->Fetch(false, $where, $orderBy, $groupBy, 0, 1, $join, $joinType, $joinCondition);
        if (count($objects))
            return $objects[0];

        return null;
    }
    
    
    /**
     * Returns first result by field value
     * @param string $field Field Name
     * @param mixed $value A value type, TableObject or mappable object (e.g. Date)
     * @param Sql\OrderList $orderBy
     * @param Sql\GroupList $groupBy
     * @return TableObject
     */
    public final function FirstByField($field, $value, Sql\OrderList $orderBy = null, Sql\GroupList $groupBy = null)
    {
        $result = $this->FetchByField(false, $field, $value, $orderBy, $groupBy, 0, 1);
        if (count($result))
            return $result[0];
    }
    
    /**
     * Returns results by field value.
     * @param string $field Field name
     * @param mixed $value A fitting value type, TableObject or mappable object (e.g. Date)
     * @param Sql\OrderList $orderBy
     * @param Sql\GroupList $orderBy
     * @return array[TableObject]
     */
    public final function FetchByField($distinct, $field, $value, Sql\OrderList $orderBy = null, Sql\GroupList $groupBy = null, $offset = 0, $count = null)
    {
        $where = $this->FieldCondition($field, $value);        
        return $this->Fetch($distinct, $where, $orderBy, $groupBy, $offset, $count);
    }
        
        /**
         * Sql condition for matching a field value
         * @param string $field
         * @param mixed $value A fitting value type, TableObject or mappable object (e.g. Date)
         * @return Sql\Condition 
         */
        private function FieldCondition($field, $value)
        {
            $tbl = $this->Table();
            $mapper = $this->FieldMapper($field);
            $dbVal = $mapper->ToDBString($value);
            
            $sql = $this->SqlBuilder();
            
            if ($dbVal === null)
                return $sql->IsNull($tbl->Field($field));
            else
                return $sql->Equals($tbl->Field($field), $sql->Value($dbVal));
        }
        
      /**
     * Returns count by field value.
     * @param string $field Field name
     * @param mixed $value A fitting value type, TableObject or mappable object (e.g. Date)
     * @param Sql\GroupList $groupBy
     * @return int
     */    
        public final function CountByField($distinct, $field, $value,  Sql\GroupList $groupBy = null)
        {
            $where = $this->FieldCondition($field, $value);        
            return $this->Count($distinct, $where, $groupBy);
        }
    
    /**
     * Builds a sql source by prepending the table to the join if join is given.
     * @param Sql\Table $table
     * @param Sql\Join $join
     * @param Sql\JoinType $joinType
     * @param Sql\Condition $joinCondition
     * @return Sql\Source
     */
    private function BuildSource(Sql\Table $table, Sql\Join $join = null, Sql\JoinType $joinType = null, Sql\Condition $joinCondition = null)
    {
        $src = $table;
        if ($join)
        {
            if (!$joinType || !$joinCondition)
                throw new \InvalidArgumentException("If join is given, a join type and join condition are also required.");
            
            $src = $join->Join($table, $joinType, $joinCondition, true);
        }
        return $src;
    }
    
    public final function Count($distinct = false, Sql\Condition $where = null, Sql\GroupList $groupBy = null, Sql\Join $join = null, Sql\JoinType $joinType = null, Sql\Condition $joinCondition = null)
    {
        $t = $this->Table();
        $src = $this->BuildSource($t, $join, $joinType, $joinCondition);
        $sql = $this->SqlBuilder();
        $cnt = $sql->FunctionCount(array($t->Field($this->KeyField())), '', $distinct);
        $select = $sql->Select(false, $sql->SelectList($cnt), $src, $where, null, $groupBy);
        
        $reader = $this->Connection()->ExecuteQuery((string)$select);
        $result = 0;
    
        if ($reader->Read())
            $result = $reader->ByIndex(0);

        $reader->Close();
        return $result;
    }
   
    /**
     * Deletes data recors from the underlying tables
     * @param Sql\Condition $where Condition which records to delete
     * @param Sql\OrderList $orderBy Optional order of deletion
     * @param int $offset Optional offset for record deletion
     * @param int $count Optional count 
     */
    public final function Delete(Sql\Condition $where, Sql\OrderList $orderBy = null, $offset = 0, $count = 0)
    {
        $sql = $this->SqlBuilder();
        $delete = $sql->Delete($this->Table(), $where, $orderBy, $offset, $count);
        $this->Connection()->ExecuteQuery((string)$delete);
    }
    
    /**
     * Performs an update on the underlying table
     * @param Sql\SetList $setList The set list
     * @param Sql\Condition $where The condition on the records to update
     * @param Sql\OrderList $orderBy Optional order for data records
     * @param int $offset Optional offset for the data records
     * @param int $count Optional count for the data records
     */
    public final function Update(Sql\SetList $setList, Sql\Condition $where = null, Sql\OrderList $orderBy = null, $offset = 0, $count = 0)
    {
        $sql = $this->SqlBuilder();
        $update = $sql->Update($this->Table(), $setList, $where, $orderBy ,$offset, $count);
        $this->Connection()->ExecuteQuery((string)$update);
    }
    /**
     * Then name of the associated table
     * @return string
     */
    abstract public function TableName();

    /**
     * Return field mappers as array. Field name is key.
     * @return array string=>FieldMapper
     */
    abstract public function FieldMappers();
    
    /**
     * Creates a related database object.
     * @param mixed $keyValue
     * @return TableObject
     */
    abstract function CreateInstance($keyValue = null);
}