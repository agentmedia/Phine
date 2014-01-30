<?php
namespace Phine\Framework\Database\ObjectGeneration\Interfaces;

interface ITableNameMapper
{
    function ClassName($tableName);
    function FullNamespace($tableName);
    function RelativeNamespace($tableName);
    function RootNamespace();
}