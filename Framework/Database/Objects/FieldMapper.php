<?php
namespace Phine\Framework\Database\Objects;
use Phine\Framework\Database\Interfaces as DBInterfaces;

class FieldMapper
{
    /**
     * 
     * @var DBInterfaces\IDatabaseFieldInfo
     */
    private $fieldInfo;
    
    /**
     * 
     * @var DBInterfaces\IDatabaseType
     */
    private $type;

    /**
     * 
     * @var TableSchema
     */
    private $relatedSchema;
    
    function __construct(DBInterfaces\IDatabaseFieldInfo $fieldInfo, TableSchema $relatedSchema = null)
    {
        $this->fieldInfo = $fieldInfo;
        $this->typeDef = $fieldInfo->GetTypeDef();
        $this->type = $this->typeDef->GetType();
        $this->relatedSchema = $relatedSchema;
    }
    
    /**
     * 
     * @return TableSchema
     */
    function RelatedSchema()
    {
        return $this->relatedSchema;
    }
    /**
     * 
     * @return DBInterfaces\IDatabaseFieldInfo
     */
    public function FieldInfo()
    {
        return $this->fieldInfo;
    }
        
    /**
     * Returns a fitting object (e.g. date or database object) if available.
     * @param $value
     * @return mixed The resulting object.
     */
    function MapValue($value)
    {
        if ($value === null)
            return $value;
        
        if ($this->IsRelatedObject($value) || $this->IsDatabaseObject($value))
            return $value;
        
        else if (!is_object($value) && !is_array($value))
        {
            if ($this->relatedSchema)
                return $this->relatedSchema->CreateInstance($value);
            else
                return $this->type->FromDBString((string)$value);
        }

        //no more object types mappable.
        throw new \InvalidArgumentException('Invalid argument for fieldmapper. Object could not be mapped');
    }
    
    /**
     * Returns sql usable string or null for database NULL.
     * @param $value
     * @return string
     */
    function ToDBString($value)
    {
        if ($value === null)
            return $value;
        
        if ($this->IsRelatedObject($value))
            return $value->KeyValue();
        
        else if ($this->IsDatabaseObject($value))
            return $this->type->ToDBString($value);
        
        else if (!is_object($value) && !is_array($value))
            return (string)$value;
        
        throw new \InvalidArgumentException('Invalid argument for fieldmapper. Object could not be converted to database string.');
    }
    
    
    private function IsDatabaseObject($value)
    {
        $defaultInst = $this->type->DefaultInstance();
        return is_object($defaultInst) && is_object($value) &&
                get_class($value) == get_class($defaultInst);
    }
    
    private function IsRelatedObject($value)
    {
        $schema = $this->relatedSchema;
        
        return $schema && is_object($value) &&
                get_class($value) == get_class($schema->CreateInstance());
    }
    
    
    
}