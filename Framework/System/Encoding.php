<?php
namespace Phine\Framework\System;

require_once __DIR__ . '/Enum.php';

class Encoding extends Enum
{   
    /**
     * A string representation as needed by php functions.
     * @return string
     */
    function Code()
    {
        return $this->Value();
    }
    
    /**
     * True if encoding is utf-8
     * @return bool
     */
    function IsMultiByte()
    {
        return $this->Equals(Encoding::UTF_8());
    }
    
    static function ISO_8859_1()
    {
        return new self('ISO-8859-1');
    }
    
    static function ISO_8859_15()
    {
        return new self('ISO-8859-15');
    }
    
    static function UTF_8()
    {
        return new self('UTF-8');
    }
    
    static function cp866()
    {
        return new self('cp866');
    }
    
    static function cp1251()
    {
        return new self('cp1251');
    }
    
    static function cp1252()
    {
        return new self('cp1252');
    }
    
    static function KOI8_R()
    {
        return new self('KOI8-R');
    }
    
    static function BIG5()
    {
        return new self('BIG5');
    }
    
    static function GB2312()
    {
        return new self('GB2312');
    }
    
    static function BIG5_HKSCS()
    {
        return new self('BIG5-HKSCS');
    }
    
    static function Shift_JIS()
    {
        return new self('Shift_JIS');
    }

    static function EUC_JP()
    {
        return new self('EUC-JP');
    }
}