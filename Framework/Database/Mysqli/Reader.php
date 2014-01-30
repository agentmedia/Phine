<?php
namespace Phine\Framework\Database\Mysqli;

use Phine\Framework\Database\Interfaces as DBInterfaces;

class Reader implements DBInterfaces\IDatabaseReader
{
    private $row;
    /**
     * 
     * @var mysqli_result
     */
    private $result;
    
    /**
     * 
     * @var bool
     */
    private $closed = false;
    
    function Names()
    {
        if (!is_array($this->row))
            throw new \BadMethodCallException('MysqlReader has no data. Call Read or check data.');
        
        $result = array();
        foreach ($this->row as $key=>$val)
        {
            if (is_string($key))
                $result[] = $key;
        }
        return $result;
    }
    function __construct(\mysqli_result $result)
    {
        $this->result = $result;
    }
    
    function __destruct()
    {
        $this->Close();
    }
    function Read()
    {
        $this->row = $this->result->fetch_array(MYSQLI_BOTH);
        return $this->row !== null;
    }
    
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseReader#ByName($name)
     */
    function ByName($name)
    {
        $this->CheckData($name);
        if ($this->row)
            return $this->row[(string)$name];
    }
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseReader#ByIndex($idx)
     */
    function ByIndex($idx)
    {
        $this->CheckData($idx);
        return $this->row[$idx];    
    }
    /**
     * (non-PHPdoc)
     * @see Phine/Framework/Database/Interfaces/IDatabaseReader#Close()
     */
    function Close()
    {
        if (!$this->IsClosed())
        {
            $this->result->close();
            $this->closed = true;
        }
    }
    /**
     * Checks if key exists and reader not at end or closed.
     * @param $key
     * @return unknown_type
     */
    private function CheckData($key)
    {
        if (!is_array($this->row))
            throw new \BadMethodCallException('MysqliReader has no data. Call Read method or check query.');
          
        if (!array_key_exists($key, $this->row))
            throw new \InvalidArgumentException('Mysqli Reader has no index ' . $key);
    
          if ($this->IsClosed())
            throw new \BadMethodCallException('MysqliReader is closed.');
          
    }
    /**
     * 
     * @return bool
     */
    function IsClosed()
    {
        return $this->closed;
    }
}