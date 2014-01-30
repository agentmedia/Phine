<?php
namespace Phine\Framework\Database\Mysqli;
use Phine\Framework\Database\Interfaces as DBInterfaces;
use Phine\Framework\System\Date;

class Escaper implements DBInterfaces\IDatabaseEscaper
{
    /**
     * 
     * @var mysqli 
     */
    private $db;
    function __construct(\mysqli $db)
    {
        $this->db = $db;
    }
    
    function EscapeIdentifier($identifier)
    {
        $splitted = explode('.', $identifier);
        for($idx = 0; $idx < count($splitted); ++$idx)
        {
            $splitted[$idx] = $this->QuoteSingleIdentifier($splitted[$idx]);
        }
        $identifier = join('.', $splitted);
        return $identifier;
        
    }

    private function QuoteSingleIdentifier($identifier)
    {
        $identifier = trim($identifier);
        if ($identifier != '*')
            $identifier = '`' .  $identifier . '`';
        
        return $identifier;
    }
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseEscaper#EscapeValue($identifier)
     */
    function EscapeValue($value)
    {
        if ($value === null)
            return 'NULL';
        
        if ($value instanceof Date)
            $value = $value->ToString( 'Y-m-d H:i:s');

        return "'" . $this->db->real_escape_string($value) . "'";
    }

}