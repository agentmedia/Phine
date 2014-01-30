<?php
namespace Phine\Framework\Progress\Interfaces;

interface IReporter
{
    function Report($progress, $progressCount);
}
