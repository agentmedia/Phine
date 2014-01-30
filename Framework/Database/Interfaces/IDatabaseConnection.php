<?php

namespace Phine\Framework\Database\Interfaces;
use Phine\Framework\Database\Sql;

require_once __DIR__ . '/IDatabaseReader.php';
require_once __DIR__ . '/IDatabaseEscaper.php';
require_once __DIR__ . '/IDatabaseSqlLimiter.php';


interface IDatabaseConnection
{

  /**
   *
   * Close current connection
   */
  function Close();
  
  /**
   * Is the database connected?
   * @return bool
   */
  function IsConnected();

  /**
   * Executes the query.
   * @param string $query
   * @return IDatabaseReader result
   */
  public function ExecuteQuery($query);

  /**
   * Start transaction on database.
   */
  function StartTransaction();

  
  /**
   * Commit transaction On database.
   */
  function Commit();

  
  /**
   * Commit transaction On database.
   */
  function RollBack();

  /**
   * 
   * @param string $table
   * @returns int
   */
  function LastInsertId($table);
  
  /**
   * 
   * @return IDatabaseEscaper
   */
  function GetEscaper();
  
  /**
   * 
   * @return IDatabaseSqlLimiter
   */
  function GetSqlLimiter();
  
  /**
   * All table names
   * @return array string
   */
  function GetTables();
  
  /**
   * All field names of the table
   * @param string $table
   * @return array string
   */
  function GetFields($table);
  
  /**
   * Returns info providing basic information about the field.
   * @sparam string $table Table name
   * @param string $field Field name
   * @return IDatabaseFieldInfo
   */
  function GetFieldInfo($table, $field);
  
  /**
   * Executes a prepared statement (with placeholders).
   * @param string $prepared Sql statement.
   * @param array[Sql\Value] $values
   * @return IDatabaseReader
   */
  function ExecutePrepared($prepared, array $values);
  
  /**
   * Returns the parameters that this instance was constructed with
   */
  function ConstructorParameters();
  
}