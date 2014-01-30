<?php
namespace Phine\Framework\Database\Mysqli;

use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\Database\Exceptions as DBExceptions;
use Phine\Framework\Database\Mysql;
use Phine\Framework\Database\Sql;
use Phine\Framework\System\Php;


class Connection extends Php\WritableClass implements DBInterfaces\IDatabaseConnection 
{
  /**
   * 
   * @var \mysqli
   */
  private $db;
  
  /**
   * 
   * @var string
   */
  private $dbName;
  /**
   * 
   * @var Escaper;
   */
  private $escaper;
  /**
   * 
   * @var bool
   */
  private $connected = false;
  
  /**
   * 
   * @var string
   */
  private $lastQuery = '';
  
  /**
   * 
   * @var bool
   */
  private $inTransaction = false;
  
  /**
   * 
   * @var array string
   */
  private $tables;
  
  
  /**
   *
   * @var The constructor params
   */
  private $constructorParams = array();
  
  /**
   * Throws an exception on db error or db connect error
   */
    private function AssertSuccess()
    {
        if ($this->db)
        {
            if ($this->IsConnected())
            {
                $errorCode = $this->db->errno;
                if ($errorCode != 0)
                {
                    if ($this->inTransaction)
                        $this->RollBack();
              
                    throw new DBExceptions\DatabaseException($this->db->error, $errorCode, $this->lastQuery);
                }
            }
            else
            {
                $errorCode = $this->db->connect_errno;
                if ($errorCode != 0)
                {
                    throw new DBExceptions\DatabaseException($this->db->connect_error, $errorCode);
                }
            }
        }      
    }

  /**
   * 
   * @param $server
   * @param $username
   * @param $password
   * @param $dbName
   * @param $port
   * @param $socket
   */
  function __construct($server, $username, $password, $dbName, $port = 3306, $socket = '')
  {
    $this->constructorParams = array($server, $username, $password, $dbName, $port, $socket);
    $this->dbName = $dbName;
    $this->db = @new \mysqli($server, $username, $password, $dbName, $port, $socket);
    $this->AssertSuccess();
    $this->connected = true;
    $this->db->select_db($dbName);
    $this->AssertSuccess();
    $this->escaper = new Escaper($this->db);
  }
  /**
   * 
   */
  function __destruct()
  {
    if ($this->connected)
    {
      $this->Close();
    }
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#Close()
   */
  function Close()
  {
    if ($this->connected)
    {
      if ($this->inTransaction)
            $this->Commit();
        
      $this->db->close();
      /*$this->AssertSuccess();*/
      $this->connected = false;
    }
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#IsConnected()
   */
  function IsConnected()
  {
    return $this->connected;
  }

  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#ExecuteQuery($query)
   */
  function ExecuteQuery($query)
  {
    $this->lastQuery = $query;
    $result = mysqli_query($this->db, $query);
    $this->AssertSuccess();
   
    if ($result instanceof \mysqli_result)
        return new Reader($result);
    
    return null;
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#StartTransaction()
   */
  function StartTransaction()
  {
      $this->ExecuteQuery("set autocommit = 0");
    $this->ExecuteQuery("start transaction");
    $this->inTransaction = true;
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#Commit()
   */
  function Commit()
  {
    $this->ExecuteQuery("commit");
    $this->ExecuteQuery("set autocommit = 1");
    $this->inTransaction = false;
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#RollBack()
   */
  function RollBack()
  {
    $this->ExecuteQuery("rollback");
      $this->ExecuteQuery("set autocommit = 1");
    $this->inTransaction = false;
  }
  
  /*
  private function GetColumnSetString(array $columnValues = array())
  {
      $resultArr = array();
      foreach ($columnValues As $key=>$val)
      {
          if ($val === null)
              $resultArr[] = $key . " = NULL";
          else
              $resultArr[] = $key . " = " . $this->escaper->EscapeValue($val) ;
      }
      return join(', ', $resultArr);
  }
   * 
   */
  

  function LastInsertId($table)
  {
      $reader = $this->ExecuteQuery("SELECT LAST_INSERT_ID() FROM " . $this->escaper->EscapeIdentifier($table));
      if ($reader->Read())
        return $reader->ByIndex(0);
    
      return 0;
  }
  
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#GetEscaper()
   */
  function GetEscaper()
  {
      return $this->escaper;
  }
  
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#GetSqlLimiter()
   */
  function GetSqlLimiter()
  {
      return new Mysql\SqlLimiter();      
  }
  
  /**
   * 
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#GetTables()
   */
  function GetTables()
  {
      if ($this->tables === null)
      {
          $this->tables = array();
          $sql = new Sql\Builder($this);
          $tbl = $sql->Table('information_schema.tables', array('TABLE_SCHEMA', 'TABLE_NAME'));
          $cond = $sql->Equals($tbl->Field('TABLE_SCHEMA'), $sql->Value($this->dbName));
          $what = $sql->SelectList($tbl->Field('TABLE_NAME'));
      
          $select = $sql->Select(true, $what, $tbl, $cond);
          $reader = $this->ExecuteQuery((string)$select);
          while ($reader->Read())
          {
              $this->tables[] = $reader->ByIndex(0);
          }
      }
      return $this->tables;
  }
  /**
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#GetFields($table)
   */
  function GetFields($table)
  {
      $fields = array();
      $sql = new Sql\Builder($this);
      $tbl = $sql->Table('information_schema.columns', array('TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME'));
      $cond = $sql->Equals($tbl->Field('TABLE_SCHEMA'), $sql->Value($this->dbName))
              ->And_($sql->Equals($tbl->Field('TABLE_NAME'), $sql->Value($table)));
      $what = $sql->SelectList($tbl->Field('COLUMN_NAME'));
      $select = $sql->Select(true, $what, $tbl, $cond);
      $reader = $this->ExecuteQuery((string)$select);
      while ($reader->Read())
      {
          $fields[] = $reader->ByIndex(0);
      }
      return $fields;
  }
  /**
   *
   * (non-PHPdoc)
   * @see Phine/Framework/Database/Interfaces/IDatabaseConnection#GetFieldInfo($table, $field)
   * @return Mysql\FieldInfo
   */
  function GetFieldInfo($table, $field)
  {
      $sql = new Sql\Builder($this);
      $tbl = $sql->Table('information_schema.columns', array('TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 'IS_NULLABLE', 'COLUMN_TYPE', 'COLUMN_KEY'));
      
      $cond = $sql->Equals($tbl->Field('TABLE_SCHEMA'), $sql->Value($this->dbName))
              ->And_($sql->Equals($tbl->Field('TABLE_NAME'), $sql->Value($table)))
              ->And_($sql->Equals($tbl->Field('COLUMN_NAME'), $sql->Value($field)));
      
      $what = $sql->SelectList($tbl->Field('IS_NULLABLE'));
      $what->Add($tbl->Field('COLUMN_TYPE'));
      $what->Add($tbl->Field('COLUMN_KEY'));
      
      $sel = $sql->Select(true, $what, $tbl, $cond);
      $reader = $this->ExecuteQuery((string)$sel);
      if ($reader->Read())
      {
          $fullName = $reader->ByName('COLUMN_TYPE');
          $key = $reader->ByName('COLUMN_KEY');
          $isUnique = ($key == 'UNI');
          $isPrimary = ($key == 'PRI');
          $isNullable = $reader->ByName('IS_NULLABLE') == 'YES';
          $reader->Close();
           
          $tbl = $sql->Table('information_schema.key_column_usage', array('TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 
                  'REFERENCED_TABLE_NAME', 'REFERENCED_COLUMN_NAME'));
      
          $cond = $sql->Equals($tbl->Field('TABLE_SCHEMA'), $sql->Value($this->dbName))
              ->And_($sql->Equals($tbl->Field('TABLE_NAME'), $sql->Value($table)))
              ->And_($sql->Equals($tbl->Field('COLUMN_NAME'), $sql->Value($field)))
              ->And_($sql->IsNotNull($tbl->Field('REFERENCED_TABLE_NAME')));
              
          $what = $sql->SelectList($tbl->Field('REFERENCED_TABLE_NAME'));
          $what->Add($tbl->Field('REFERENCED_COLUMN_NAME'));
          $select = $sql->Select(true, $what, $tbl, $cond);
          $reader = $this->ExecuteQuery((string)$select);
          $refTable = '';
          $refField = '';
          if ($reader->Read())
          {
              $refTable = (string)$reader->ByName('REFERENCED_TABLE_NAME');
              $refField = (string)$reader->ByName('REFERENCED_COLUMN_NAME');
          }
          return new Mysql\FieldInfo($fullName, $refTable, $refField, $isUnique, $isPrimary, $isNullable);
      }
      return null;      
  }
  
  /**
   * 
   * Executes a prepared Statement.
   * @param string $prepared A prepared statement with ?-placeholders.
   * @param array[Sql\Value] $values Values as Sql\Value
   * @return Reader
   * 
  */
  function ExecutePrepared($prepared, array $values)
  {
      $prepared = addcslashes($prepared, "'");
      $prepStmt = 'PREPARE stmt FROM \'' . $prepared . '\'';
      $this->ExecuteQuery($prepStmt);
      
      $cnt = count($values);
      $using = $cnt ? ' USING ' : '';
      for ($idx = 0; $idx < $cnt; ++$idx)
      {
          $var = '@v' . $idx;
          $this->ExecuteQuery('SET ' . $var .' = ' . $values[$idx]);
          $using .= $var;
          if ($idx < $cnt - 1)
                 $using .= ', ';
    }
    $result = $this->ExecuteQuery('EXECUTE stmt' . $using);
    $this->ExecuteQuery('DEALLOCATE PREPARE stmt');
    return $result;
  }
  
  public function ConstructorParameters()
  {
      return $this->constructorParams;
  }
  
  protected function GetConstructParams()
  {
      return $this->ConstructorParameters();
  }
}
?>