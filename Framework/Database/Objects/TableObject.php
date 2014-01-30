<?php

namespace Phine\Framework\Database\Objects;
use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\Database\Sql;

abstract class TableObject
{
    private $fieldValues = array();
    private $initialized = false;
    private $noDBLoad;
    /**
     * 
     * Creates a new Database object.
     * @param $keyValue the id for which to create this instance
     */
    function __construct($keyValue = null, $noDBLoad = false)
    {
            $this->fieldValues[$this->GetSchema()->KeyField()] = $keyValue;    
            $this->initialized = $noDBLoad;
            $this->noDBLoad = $noDBLoad;
    }
    
    /**
     * Get associatad table schema
     * @return TableSchema
     */
    abstract function GetSchema();
    
    /**
     * Returns the value of the key field.
     * @return mixed
     */
    final function KeyValue()
    {
        return $this->fieldValues[$this->GetSchema()->KeyField()];
    }
    
    /**
     * Fetches data to fill fields.
     */
    private function Initialize()
    {
        if (!$this->initialized)
        {
            $schema = $this->GetSchema();
            $t = $schema->Table();
            $sql = $schema->SqlBuilder();
           
            $what = $t->ToSelectList();
            $where = $sql->Equals($t->Field($schema->KeyField()), $sql->Value($this->KeyValue()));
            $select = $sql->Select(false, $what, $t, $where);
            $reader = $schema->Connection()->ExecuteQuery((string)$select);
            if ($reader->Read())
                $this->InitFromReader($reader);

            $reader->Close();
        }
    }

    /**
     * Initializes the object by filling all field values from reader. 
     * @param DBInterfaces\IDatabaseReader $reader
     */
    final function InitFromReader(DBInterfaces\IDatabaseReader $reader)
    {
        $names = $reader->Names();
        foreach ($names as $name)
        {    
            $this->SetInternally($name, $reader->ByName($name));
        }
        $this->initialized = true;
    }
    /**
     * Sets data internally, without initializing at first
     * @param type $name
     * @param type $value
     */
    private function SetInternally($name, $value)
    {
        $schema = $this->GetSchema();
        $mapper = $schema->FieldMapper($name);
        $this->fieldValues[$name] = $mapper->MapValue($value);
    }
    /**
     * Sets the field value. If there is a related table schema, a database object can be given.
     * @param string $name
     * @param mixed $value
     */
    final function __set($name, $value)
    {
        //we need to initialize it, because data would get lost on later initializing
        $this->Initialize();
        $this->SetInternally($name, $value);
    }
    
    /**
     * Returns the field value. If there is a related table schema, an object is returned.
     * @param $name
     * @return mixed
     */
    final function __get($name)
    {
        $schema = $this->GetSchema();
        $names = $schema->FieldNames();
        if (!in_array($name, $names))
            throw new \InvalidArgumentException($name . ' is not a field of ' . $schema->TableName());
        
        if ($this->KeyValue() && !$this->initialized 
            && $name != $schema->KeyField())
        {
            $this->Initialize();
        }
        //Avoid php notice in regular cases:
        if (($this->noDBLoad || !$this->Exists()) && !isset($this->fieldValues[$name]))
            return null;
                
        return $this->fieldValues[$name];    
    }
    
    /**
     * 
     * Saves this instance by updating on current key value or inserting if none is given.
     */
    function Save()
    {
        $schema = $this->GetSchema();
        $sql = $schema->SqlBuilder();
        $table = $schema->Table();
        $setList = null; //new Sql\SetList();
        $con = $schema->Connection();
        
        $first = true;
        foreach ($this->fieldValues as $field=>$value)
        {
                
            $mapper = $schema->FieldMapper($field);
            $sqlValue = $sql->Value($mapper->ToDBString($value));
            if ($first)
            {
                $setList = $sql->SetList($field, $sqlValue);
                $first = false;
            }
            else
                $setList->Add($field, $sqlValue);
        }
        if ($this->KeyValue() && $this->Exists())
        {
            $update = $sql->Update($table, $setList, $this->KeyCondition());
            $con->ExecuteQuery((string)$update);
        }
        else
        {
            if ($this->KeyValue())
                $setList->Add($schema->KeyField(),$sql->Value($this->KeyValue()));
            
            $insert = $sql->Insert($table, $setList);
            $con->ExecuteQuery((string)$insert);
            if (!$this->KeyValue())
                $this->fieldValues[$schema->KeyField()] = $con->LastInsertId($schema->TableName());
        }
    }
    
    /**
     * Returns the sql condition key field = key value
     * @return Sql\Condition
     */
    private function KeyCondition()
    {
        $schema = $this->GetSchema();
        $table = $schema->Table();
        $sql = $schema->SqlBuilder();
        $keyField = $table->Field($schema->KeyField());
        return $sql->Equals($keyField, $sql->Value($this->KeyValue()));
    }

    /**
     * Deletes the current instance from the database.
     */
    function Delete()
    {
        if ($this->KeyValue())
        {
            $schema = $this->GetSchema();
            $schema->Delete($this->KeyCondition());
        }
    }
    /**
     * True if this record has a key value that exists in the database.
     * @return bool
     */    
    function Exists()
    {
        if ($this->KeyValue())
        {
            return $this->GetSchema()->Count(false, $this->KeyCondition()) > 0;
        }
        return false;
    }
    /**
     * True if this === object, or both this and object exist, are of same class and key values are equal.
     * @param $object
     * @return bool
     */
    function Equals(TableObject $object = null)
    {
        if (!$object)
            return false;

        if (get_class($this) == get_class($object))
            return $this->KeyValue() == $object->KeyValue();

        return $this === $object;
    }
}