<?php
namespace Phine\Framework\Progress\Json;
use Phine\Framework\Progress\Interfaces\IReporter;
use Phine\Framework\System\IO;
use Phine\Framework\Localization\Translation;

class Reporter implements IReporter
{
    private $targetFile;
    private $descriptionLabel;
    private $data;
    
    function __construct($targetFile, array $otherData = array(), $descriptionlabel = '')
    {
        $this->targetFile = $targetFile;
        $this->descriptionLabel = $descriptionlabel;
        $this->data = $otherData;
    }
    function Report($progress, $progressCount)
    {
        $this->data['progress'] = $progress;
        $this->data['progressCount'] = $progressCount;
        if ($this->descriptionLabel)
            $this->data['progressDescription'] = Translation::Of($this->descriptionLabel, array($progress, $progressCount));
        
        IO\File::CreateWithText($this->targetFile, json_encode($this->data));
    }
}


