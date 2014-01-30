<?php
namespace Phine\Framework\Database\ObjectGeneration;

use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\System;
use Phine\Framework\System\Php;
use Phine\Framework\Progress\Progressor;
use Phine\Framework\Database\Sql;
use Phine\Framework\System\IO\Folder;


class TableObjectGenerator extends Progressor
{
    /**
     * 
     * @var DBInterfaces\IDatabaseConnection
     */
    private $connection;
    
    /**
     * 
     * @var string
     */
    private $targetFolder;
    
    /**
     * 
     * @var Interfaces\ITableNameMapper
     */
    private $tableNameMapper;
    
    /**
     * Creates a new table object generator
     * @param DBInterfaces\IDatabaseConnection $connection The database connection with related tables.
     * @param $targetFolder The folder to produce to
     * @param Interfaces\ITableNameMapper $tableMapper If null, the CamelCaseTableNameMapper ist used.
     * @return unknown_type
     */
    function __construct(DBInterfaces\IDatabaseConnection $connection, $targetFolder, Interfaces\ITableNameMapper $tableNameMapper = null)
    {
        $this->connection = $connection;
        $this->targetFolder = $targetFolder;
        if ($tableNameMapper)
            $this->tableNameMapper = $tableNameMapper;
        else
            $this->tableNameMapper = new CamelCaseTableNameMapper();
    }
    
    function Generate()
    {
        $this->PrepareTargetFolder();
        $this->CreateFiles();
    }
    
    private function PrepareTargetFolder()
    {
        if (Folder::Exists($this->targetFolder))
            Folder::Delete($this->targetFolder);
        
        Folder::Create($this->targetFolder, 0777);
            
        if (!Folder::Exists($this->targetFolder))
            throw new \Exception ('TargetFolder not found and could not be created');
        
         //TODO: !!Clear Folder!!
    }
    
    private function CreateFiles()
    {
        $tables = $this->connection->GetTables();
        $idx = 1;
        
        foreach ($tables as $table)
        {
            $this->NotifyReporters($idx, count($tables));
            $this->PrepareNamespaceFolder($table);
            $this->CreateObjectFile($table);
            $this->CreateSchemaFile($table);
            ++$idx;
        }
        $this->CreateAccessFile();
        $this->CreatePackageFile();
        $this->CreateDependenciesFile();
    }
    
    private function CreatePackageFile($subFolder = '')
    {
        $writer = new Php\Writer();
        $writer->StartPhp();
        $writer->AddCommand("require_once PHINE_PATH . 'Framework/System/PackageBuilder.php'");
        $writer->AddCommand("Phine\Framework\System\PackageBuilder::RequireFiles(__FILE__)");
        
        $this->CreateFile(System\PackageBuilder::PACKAGE_FILE, $writer->Text(), $subFolder);
    }
    
    private function CreateDependenciesFile($subFolder = '')
    {
        $writer = new Php\Writer();
        $writer->StartPhp();
        $writer->AddCommand("require_once PHINE_PATH . 'Framework/Database/Objects/_Package.php'");
        $this->CreateFile(System\PackageBuilder::DEPENDENCIES_FILE, $writer->Text(), $subFolder);
    }
    
    private function CreateAccessFile()
    {
        if (!$this->connection instanceof Php\WritableClass)
            throw new \Exception("The connection class should extend \Phine\System\Php\WritableClass");
        
        $writer = new Php\Writer();
        $writer->StartPhp();
        $className = 'Access';
        $writer->AddNamespace($this->RootNamespace(''));
        $writer->StartClass($className);
        $writer->StartDocComment();
        $writer->AddDocComment('Database connection for all table obects in this folder and subfolders');
        $writer->AddDocComment(get_class($this->connection), 'var');
        $writer->EndDocComment();
        $writer->AddCommand('private static $connection');

        $writer->StartDocComment();
        $writer->AddDocComment('Database connection for all table obects in this folder and subfolders');
        $writer->AddDocComment('\\' . get_class($this->connection), 'return');
        $writer->EndDocComment();

        $writer->StartFunction('final static function Connection');
        $writer->Start_If('self::$connection == null');
        $writer->AddCommand('self::$connection = ' . $this->connection->GetNewStatement());
        $writer->End_If();
        $writer->AddCommand('return self::$connection');
        $writer->EndFunction();
        
        $writer->StartDocComment();
        $writer->AddDocComment('Sql builder for all table obects in this folder and subfolders');
        $builder = new Sql\Builder($this->connection);
        $builderClass = '\\' . get_class($builder);
        $writer->AddDocComment($builderClass, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final static function SqlBuilder');
        $writer->AddCommand('return new ' . $builderClass . '(self::Connection())');
        $writer->EndFunction();
        $writer->EndClass();
        
        $this->CreateFile($className . '.php', $writer->Text());   
    }
    private function ClassName($table)
    {
        return $this->tableNameMapper->ClassName($table);
    }
    
    private function FullNamespace($table)
    {
        return $this->tableNameMapper->FullNamespace($table);
    }
    
    private function RootNamespace()
    {
        return $this->tableNameMapper->RootNamespace();
    }
    
    private function SchemaClassName($table)
    {
        return $this->ClassName($table) . 'Schema';
        
    }
    private function NamespaceSubFolder($table)
    {
        $relative = $this->tableNameMapper->RelativeNamespace($table);
        if ($relative)
            $relative = str_replace('\\', '/', $relative);
        return $relative;
    }
    private function PrepareNamespaceFolder($table)
    {
        $relative = $this->NamespaceSubFolder($table);
        if ($relative)
        {
            $fullPath = $this->targetFolder . '/' . $relative;
            if (!is_dir($fullPath))
                mkdir($fullPath, 0777, true);
            if (!is_dir($fullPath))
                throw new \Exception('namespace folder could not be created');
            
            $this->CreatePackageFile($relative);
        }
        
    }
    private function CreateObjectFile($table)
    {
        $class = $this->ClassName($table);
        $namespace = $this->FullNamespace($table);
        
        $schemaClass = $this->SchemaClassName($table);
        $file = $class . '.php';
        $writer = new Php\Writer();
        $writer->StartPhp();
        
        $writer->AddNamespace($namespace);
        $writer->AddNamespaceUse('Phine\Framework\Database\Objects', 'DBObjects');
        
        $writer->StartClass($class, 'DBObjects\TableObject');
        
        $writer->StartDocComment();
        $writer->AddDocComment('Table object for the ' . $this->GetNiceName($class) .'.');
        $writer->AddDocComment($schemaClass, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('static function Schema');
        $writer->AddCommand("return $schemaClass::Singleton()");
        $writer->EndFunction();
        
        
        $writer->StartDocComment();
        $writer->AddDocComment('Table schema for ' . $this->GetNiceName($class) .'.');
        $writer->AddDocComment($schemaClass, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function GetSchema');
        $writer->AddCommand("return self::Schema()");
        $writer->EndFunction();
        
        
        $fields = $this->connection->GetFields($table);
        foreach ($fields as $field)
        {
            $this->AddGetter($writer, $table, $field);
            $this->AddSetter($writer, $table, $field);
        }
        $writer->EndClass();
        $this->CreateFile($file, $writer->Text(), $this->NamespaceSubFolder($table));
    }
    
    private function CreateSchemaFile($table)
    {
        $writer = new Php\Writer();
        $namespace = $this->FullNamespace($table);
        $class = $this->ClassName($table);
        $schemaClass = $this->SchemaClassName($table);
        $file = $schemaClass . '.php';
        $writer = new Php\Writer();
        $writer->StartPhp();
        $writer->AddNamespace($namespace);
        
        $writer->AddNamespaceUse('Phine\Framework\Database\Objects', 'DBObjects');
        $writer->AddNamespaceUse('Phine\Framework\Database\Sql');
        
        $writer->StartClass($schemaClass, 'DBObjects\TableSchema');
        
        $this->AddSchemaConstructor($writer, $schemaClass);
        $this->AddSchemaSingleton($writer, $schemaClass);
        $this->AddSchemaTableName($writer, $table);
        $this->AddSchemaConnection($writer, $class);
        $this->AddSchemaCreateInstance($writer, $class);
        $this->AddSchemaFieldMappers($writer, $table);
        $this->AddSchemaBysAndFetches($writer, $table);
        
        $writer->EndClass();
        $this->CreateFile($file, $writer->Text(), $this->NamespaceSubFolder($table));
    }
    
    private function AddSchemaConstructor($writer, $schemaClass)
    {
        $writer->StartDocComment();
        $writer->AddDocComment('Creates a new ' . $schemaClass . ' object.');
        $writer->EndDocComment();
        $writer->StartFunction('private function __construct');
        $writer->EndFunction();
    }
    
    private function AddSchemaTableName(Php\Writer $writer, $table)
    {
        $writer->StartDocComment();
        $writer->AddDocComment('The Name of the mapped database table.');
        $writer->AddDocComment('string', 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function TableName');
        $writer->AddCommand("return '" . $table . "'");
        $writer->EndFunction();
    }
    
    private function AddSchemaCreateInstance(Php\Writer $writer, $class)
    {
        $writer->StartDocComment();
        $writer->AddDocComment('Creates a new ' . $class .'.');
        $writer->AddDocComment($class, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function CreateInstance', array('$keyVal = null'));
        $writer->AddCommand('return new ' . $class . '($keyVal)');
        $writer->EndFunction();
    }
    
    private function AddSchemaConnection(Php\Writer $writer, $class)
    {
        $writer->StartDocComment();
        $writer->AddDocComment('Gets the database connection for ' . $class .'.');
        $writer->AddDocComment('\\' . get_class($this->connection), 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function Connection');
        $writer->AddCommand('return \\' . $this->RootNamespace() .  '\\Access::Connection()');
        $writer->EndFunction();
    }
    private function AddSchemaFieldMappers(Php\Writer $writer, $table)
    {
        $schemaClass = $this->SchemaClassName($table);
        
        $writer->StartDocComment();
        $writer->AddDocComment('array string=>DBObjects\FieldMapper', 'var');
        $writer->EndDocComment();
        $writer->AddCommand('private $fieldMappers = null');
        
        $writer->StartDocComment();
        $writer->AddDocComment('Get field mappers for ' .  $schemaClass . '.');
        $writer->AddDocComment('array string=>DBObjects\FieldMapper', 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function FieldMappers');
        $writer->Start_If('$this->fieldMappers === null');
        $writer->AddArray('$this->fieldMappers');
        $fields = $this->connection->GetFields($table);
        foreach ($fields as $field)
        {
            $this->AddSchemaFieldMapper($writer, $table, $field);
        }
        
        $writer->End_If();
        
        $writer->AddCommand('return $this->fieldMappers');
        
        $writer->EndFunction();
        
    }
    
    private function AddSchemaFieldMapper(Php\Writer $writer, $table, $field)
    {
        $lhs = '$this->fieldMappers[\'' . $field .  '\']';
        $fieldInfo = $this->connection->GetFieldInfo($table, $field);
        if (!$fieldInfo instanceof Php\WritableClass)
            throw new \InvalidArgumentException('field info must extend Php\WritableClass to work with TableObjectGenerator');
        
        $relSchema = 'null';
        $relTable = $fieldInfo->RelatedTable();
        
        if ($relTable)
        {
            $relClass = $this->ClassName($relTable);
            $relNamespace = $this->FullNamespace($relTable);
            if ($relNamespace != $this->FullNamespace($table))
                $relClass = join('\\', array($relNamespace, $relClass));
            
            $relSchema =  $relClass . '::Schema()';
        }
        $rhs = 'new DBObjects\FieldMapper('. $fieldInfo->GetNewStatement() . ', ' . $relSchema . ')';
        $writer->AddCommand($lhs . ' = ' . $rhs);
    }
    
    private function AddSchemaBysAndFetches(Php\Writer $writer, $table)
    {
        $fields = $this->connection->GetFields($table);
        foreach ($fields as $field)
        {
            $this->AddSchemaByOrFetch($writer, $table, $field);
        }
    }
    private function AddSchemaByOrFetch(Php\Writer $writer, $table, $field)
    {
        $fieldInfo = $this->connection->GetFieldInfo($table, $field);
        if (!$fieldInfo->IsPrimary())
        {
            if ($fieldInfo->IsUnique())
                 $this->AddSchemaBy($writer, $table, $field, $fieldInfo->IsNullable());
            else
            {
                $this->AddSchemaFetch($writer, $table, $field, $fieldInfo->IsNullable());
                $this->AddSchemaCount($writer, $table, $field, $fieldInfo->IsNullable());
            }            
        }
    }
    private function AddSchemaBy(Php\Writer $writer, $table, $field, $isNullable)
    {
        $class = $this->ClassName($table);
        $type = $this->GetValueTypeName($table, $field);
        $writer->StartDocComment();
        $writer->AddDocComment('Get ' . $class . ' by unique field \'' . $field . '\'.');
        $writer->AddDocComment($type . ' $value', 'param');
        $writer->AddDocComment($class, 'return');
        $writer->EndDocComment();
        $valParam = $this->GetValueParam($type, $isNullable);
        
            
        $writer->StartFunction('final function By' . $field, array($valParam));
        $writer->AddCommand('return parent::FirstByField(\'' . $field . '\', $value)');
        $writer->EndFunction();
    }
    private function AddSchemaFetch(Php\Writer $writer, $table, $field, $isNullable)
    {
        $class = $this->ClassName($table);
        $type = $this->GetValueTypeName($table, $field);
        $writer->StartDocComment();
        $writer->AddDocComment('Fetch ' . $class . ' objects by field \'' . $field . '\'.');
        $writer->AddDocComment('bool $distinct', 'param');
        $writer->AddDocComment($type . ' $value', 'param');
        $writer->AddDocComment('Sql\OrderList $orderBy', 'param');
        $writer->AddDocComment('Sql\GroupList $groupBy', 'param');
        $writer->AddDocComment('int $offset', 'param');
        $writer->AddDocComment('int $count', 'param');
        $writer->AddDocComment($class  . '[]', 'return');
        $writer->EndDocComment();
        $valParam = $this->GetValueParam($type, $isNullable);

        $writer->StartFunction('final function FetchBy' . $field, array('$distinct', $valParam, 'Sql\OrderList $orderBy = null', 'Sql\GroupList $groupBy = null', '$offset = 0', '$count= null'));
        $writer->AddCommand('return parent::FetchByField($distinct, \'' . $field . '\', $value, $orderBy, $groupBy, $offset, $count)');
        $writer->EndFunction();
    }
        
    private function AddSchemaCount(Php\Writer $writer, $table, $field, $isNullable)
    {
        $class = $this->ClassName($table);
        $type = $this->GetValueTypeName($table, $field);
        $writer->StartDocComment();
        $writer->AddDocComment('count ' . $class . ' objects by field \'' . $field . '\'.');
        $writer->AddDocComment('bool $distinct', 'param');
        $writer->AddDocComment($type . ' $value', 'param');
        $writer->AddDocComment('Sql\GroupList $groupBy', 'param');
        $writer->AddDocComment('int', 'return');
        $writer->EndDocComment();
        $valParam = $this->GetValueParam($type, $isNullable);

        $writer->StartFunction('final function CountBy' . $field, array('$distinct', $valParam, 'Sql\GroupList $groupBy = null'));
        $writer->AddCommand('return parent::CountByField($distinct, \'' . $field . '\', $value, $groupBy)');
        $writer->EndFunction();
    }
    private function AddSchemaSingleton(Php\Writer $writer, $schemaClass)
    {
        $writer->StartDocComment();
        $writer->AddDocComment($schemaClass, 'var');
        $writer->EndDocComment();
        $writer->AddCommand('private static $singleton');
        
        
        $writer->StartDocComment();
        $writer->AddDocComment('Get unique ' . $schemaClass . '.');
        $writer->AddDocComment($schemaClass, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('static function Singleton');
        $writer->Start_If('self::$singleton === null');
        $writer->AddCommand('self::$singleton = new self()');
        $writer->End_If();
        $writer->AddCommand('return self::$singleton');
        $writer->EndFunction();
    }
    
    private function GetValueTypeName($table, $field)
    {
        $fieldInfo = $this->connection->GetFieldInfo($table, $field);
        
        $relatedTable = $fieldInfo->RelatedTable();
        if ($relatedTable)
        {
            $class = $this->ClassName($relatedTable);
            $namespace = $this->FullNamespace($relatedTable);
            if ($namespace != $this->FullNamespace($table))
                return join('\\', array($namespace, $class));
            else
                return $class;
        }
        $typeInstance = $fieldInfo->GetTypeDef()->GetType()->DefaultInstance();
    
        if (is_object($typeInstance))
            return '\\' . get_class($typeInstance);

        else if (is_bool($typeInstance))
            return  'bool';        
        
        else if (is_int($typeInstance))
            return  'int';

        else if (is_float($typeInstance))
            return  'float';

        return  'string';
    }
    private function GetNiceName($name)
    {
        $reader = new System\StringReader($name);
        $result = '';
        $prevIsLower = false;
        $idx = 0;
        while (false !== ($ch = $reader->ReadChar()))
        {
            
            if ($idx == 0)
                $result .= System\String::ToLower($ch);
            
            else if (ctype_upper($ch))
            {
                if ($prevIsLower)
                    $result .= ' ' . System\String::ToLower ($ch);
                else
                    $result .=  System\String::ToLower ($ch);
            }
            else
                $result .= $ch;
            
            $prevIsLower = ctype_lower($ch);
            ++$idx;
        }
        return $result;
    }
    private function AddGetter(Php\Writer $writer, $table, $field)
    {
        $class = $this->ClassName($table);
        $writer->StartDocComment();
        $writer->AddDocComment('Gets the ' . $this->GetNiceName($field) . ' of the ' . $this->GetNiceName($class) . '.');
        $typeName = $this->GetValueTypeName($table, $field);
        
        $writer->AddDocComment($typeName, 'return');
        $writer->EndDocComment();
        $writer->StartFunction('final function Get' . $field);
        $writer->AddCommand('return $this->'  . $field);
        $writer->EndFunction();
    }
    private function GetValueParam($type, $isNullable)
    {
        $param = '$value';
        if ($type != 'string' && $type != 'int'
            && $type != 'bool' && $type != 'float')
            $param = $type . ' ' . $param;
        
        if ($isNullable)
            $param .= ' =  null';

        return $param;
    }
    private function AddSetter(Php\Writer $writer, $table, $field)
    {
        $fieldInfo = $this->connection->GetFieldInfo($table, $field);
        //No setter for primary key!
        if ($fieldInfo->IsPrimary())
            return;

        $class = $this->ClassName($table);
        $type = $this->GetValueTypeName($table, $field);

        $writer->StartDocComment();
        $writer->AddDocComment('Sets the ' . $this->GetNiceName($field) . ' of the ' . $this->GetNiceName($class) . '.');
        $writer->AddDocComment($type . ' $value', 'param');
        $writer->EndDocComment();
        $param = $this->GetValueParam($type, $fieldInfo->IsNullable());
        

        $writer->StartFunction('final function Set' . $field, array($param));
        $writer->AddCommand('$this->'  . $field . ' = $value');
        
        $writer->EndFunction();
    }
    
    private function CreateFile($filename, $content, $subFolder = '')
    {
        
        $target = $this->targetFolder;
        if ($subFolder)
            $target .= '/' . $subFolder;
        
        $target = $target . '/' . $filename;
        file_put_contents($target, $content);
    }
}