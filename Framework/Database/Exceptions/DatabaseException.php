<?php

namespace Phine\Framework\Database\Exceptions;

class DatabaseException extends \Exception
{
  private $query;
  function __construct($message, $errorCode, $query = '')
  {
    parent::__construct($message, $errorCode);
    $this->query = $query;
  }
  function getQuery()
  {
    return $this->query;
  }
}