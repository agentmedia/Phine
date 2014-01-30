<?php
namespace Phine\Framework\Progress;
use Phine\Framework\Progress\Interfaces\IReporter;
abstract class Progressor
{
    
    private $reporters = array();
    function AddReporter(IReporter $reporter)
    {
        $this->reporters[] = $reporter;
    }
    function NotifyReporters($progress, $progressCount)
    {
        foreach ($this->reporters as $reporter)
        {
            $reporter->Report($progress, $progressCount);
        }
    }
}
