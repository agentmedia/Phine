<?php

namespace Phine\Framework\System;

/**
 * Reads string characters. Multibyte (UTF-8) compatible with good performance.
 * @author Klaus Potzesny
 *
 */
class StringReader
{
    private $string = '';
    private $charCount = 0;
    
    private $idx = 0;
    
    private $utf8Start; 
    
    /**
     * Creates a new string reader object.
     * @param string $string
     */
    function __construct($string)
    {
        $this->string = $string;
        // Don't use String::Length here because we use indexing. 
        // but php string{idx} is not working with multibyte.

        $this->charCount = strlen($string);
        $this->utf8Start= chr(128);
    }
    
    /**
     * Rewinds the string reader so characters can be read again.
     */
    function Rewind()
    {
        $this->idx = 0;
    }
    
    /**
     * Returns next char (maybe multibyte) or false if end is reached,
     * @returns string|bool
     */
    function ReadChar()
    {
        if ($this->IsAtEnd())
            return false;
        
        $ch = $this->string{$this->idx};
        ++$this->idx;
        if ($ch >= $this->utf8Start && !$this->IsAtEnd())
        {
            $ch .= $this->string{$this->idx};
            ++$this->idx;
        }
        Return $ch;
    }
    
    /**
     * True if end is reached.
     * @return bool
     */
    private function IsAtEnd()
    {
        return $this->idx >= $this->charCount;
    }
}