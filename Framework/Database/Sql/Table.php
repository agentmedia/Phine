<?php
namespace Phine\Framework\Database\Sql;
use Phine\Framework\Database\Interfaces as DBInterfaces;

require_once  __DIR__ . '/Source.php';
class Table extends Source
{
    private $name;
    private $alias;
    private $fields = array();
    
    protected function __construct(DBInterfaces\IDatabaseConnection $connection, $name, array $fieldNames, $alias = '')
    {
        parent::__construct($connection);
        $this->name = $name;
        $this->alias = $alias;
        
        foreach ($fieldNames as $key=>$fieldName)
        {
            $alias = '';
            if (is_string($key))
                $alias = $key;
        
            $this->fields[$fieldName] = $this->CreateField($fieldName, $this->RawSelectableName(), $alias);
        }
    }
    
    private function Name()
    {
        return $this->escaper->EscapeIdentifier($this->name);
    }

    private function NameAsAlias()
    {
        $result = $this->Name();
        if ($this->alias) 
            $result = $result . ' AS ' .  $this->Alias();
        
        return $result;
    }
    
    
    /**
     * Returns alias if given, name otherwise. (NOT sql escaped)
     * @return string
     */
    private function RawSelectableName()
    {
        return $this->alias ? $this->alias : $this->name;
    }
    
    /**
     * Returns the alias (sql escaped).
     * @return String
     */
    private function Alias()
    {
        return $this->alias ? $this->escaper->EscapeIdentifier($this->alias) : '';
    }

    /**
     * Gets the Field with given name. Alias is NOT allowed.
     * @param string $name The name of the desired field
     * @return Field
     * @throws \InvalidArgumentException if field doesn't belong to the table.
     */
    function Field($name)
    {
        if (!array_key_exists($name, $this->fields))
            throw new \InvalidArgumentException($name . ' is not a valid field for '. $this->name);
        
        return $this->fields[$name];
    }
    
    /**
     * Returns all fields as array.
     * @return array[Field]
     */
    function AllFields()
    {
        return $this->fields;
    }
    
    /**
     * String representation as needed in FROM statement
     * @return string
     */
    function ToString()
    {
        return $this->NameAsAlias();
    }
    
    /**
     * 
     * Pushes all fields to a select list 
     * @return SelectList
     */
    function ToSelectList()
    {
        $first = true;
        $list = null;
        foreach ($this->fields as $field)
        {
            if ($first)
            {
                $list = $this->CreateSelectList($field);
                $first = false;
            }
            else
            {
                $list->Add($field);
            }
        }
        return $list;
        
    }
}