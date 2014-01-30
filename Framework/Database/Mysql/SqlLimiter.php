<?php
namespace Phine\Framework\Database\Mysql;

use Phine\Framework\Database\Interfaces as DBInterfaces;

class SqlLimiter implements DBInterfaces\IDatabaseSqlLimiter
{
    function ApplySqlLimit($sql, $offset, $count)
    {
        return $sql . ' LIMIT ' . $offset . ', ' . $count;
    }
}