<?php
namespace Phine\Framework\Database\Interfaces;

interface IDatabaseSqlLimiter
{
    /**
     * Apply a limit to an sql select statement. Simple for mysql only.
     * @param string $sql
     * @param int $offset The row offset
     * @param int $count The row count
     * @return string
     */
    function ApplySqlLimit($sql, $offset, $count);
}