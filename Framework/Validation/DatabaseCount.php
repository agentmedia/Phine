<?php

namespace Phine\Framework\Validation;

require_once __DIR__ . '/Validator.php';

use Phine\Framework\Database\Sql;

class DatabaseCount extends Validator
{
    
    const TooFew = 'Validation.DatabaseCount.TooFew';
    const TooMuch = 'Validation.DatabaseCount.TooMuch';
    /**
     * 
     * @var Sql\Select
     */
    private $prepared = null;
    /**
     * 
     * @var int
     */
    private $minCount = 1;
    
    /**
     * 
     * @var int
     */
    private $maxCount = 1;
    
    /**
     * Number of placeholder appearances
     * @var int
     */
    private $numPlaceholders = 1;
    
    /**
     * Validator for exactly one match.
     * @param Sql\Select $select
     * @return DatabaseCount
     */
    static function UniqueExists(Sql\Select $select, $errorLabelPrefix = '')
    {
        return new self($select, 1, 1, $errorLabelPrefix);
    }
    
    /**
     * Validator for at least one match.
     * @param Sql\Select $select
     * @param string $errorLabelPrefix
     * @return DatabaseCount
     */
    static function Exists(Sql\Select $select, $errorLabelPrefix = '')
    {
        return new self($select, 1, -1, $errorLabelPrefix);    
    }

    /**
     * Validator for no match.
     * @param Sql\Select $select
     * @param string $errorLabelPrefix
     * @return DatabaseCount
     */
    static function NoneExists(Sql\Select $select, $errorLabelPrefix = '')
    {
        return new self($select, 0, 0, $errorLabelPrefix);        
    }

    /**
     * 
     * @param Sql\Select $select
     * @param int $minCount
     * @param int $maxCount
     * @param string $errorLabelPrefix
     */
    function __construct(Sql\Select $prepared, $minCount = 1, $maxCount = 1, $errorLabelPrefix = '')
    {
        $this->prepared = $prepared;
        $this->minCount = (int)$minCount;
        $this->maxCount = (int)$maxCount;
        parent::__construct($errorLabelPrefix);
    }
    /**
     * True if the cnt
     * @param type $cnt
     * @return bool
     */
    private function IsTooFew($cnt)
    {
        return $this->minCount > -1 &&  $cnt < $this->minCount;
    }
    
    private function IsTooMuch($cnt)
    {
        return $this->maxCount > -1 &&  $cnt > $this->maxCount;
    }
    
    /**
     * Needs to be set if multiple placeholder are uses
     * @param int $num 
     */
    public function SetNumPlaceholders($num)
    {
        $this->numPlaceholders = $num;
    }
    
    private function GetCount($value)
    {
        $connection = $this->prepared->Connection();
        $sql = new Sql\Builder($connection);
        $params = array_fill(0, $this->numPlaceholders, $sql->Value($value));
        
        $reader = $connection->ExecutePrepared((string)$this->prepared, $params);
        $result = 0;
        if ($reader->Read())
            $result = (int)$reader->ByIndex(0);

        $reader->Close();
        return $result;
    }
    function Check($value)
    {
        $this->error = '';
        if ($value)
        {
            $cnt = $this->GetCount($value);
            
            if ($this->IsTooFew($cnt))
                $this->error = self::TooFew;

            else if ($this->IsTooMuch($cnt))
                $this->error = self::TooMuch;
        }
        return $this->error == '';
    }
}