<?php

namespace Phine\Framework\System;

require_once __DIR__ . '/Enum.php';

class HtmlQuoteMode extends Enum
{
    
    /**
     * Returns a flag as needed by php function "htmlspecialchars".
     * @return ENT_COMPAT|ENT_QUOTES|ENT_NOQUOTES
     */
    function Flag()
    {
        return $this->Value();
    }
    
    /**
     * Html Encode double Quotes only.
     * @return HtmlQuoteMode
     */
    static function DoubleQuotes()
    {
        return new self(ENT_COMPAT);    
    }

    /**
     * Html Encode double and single quotes.
     * @return HtmlQuoteMode
     */
    static function AllQuotes()
    {
        return new self(ENT_QUOTES);    
    }
    
    
    /**
     * Html Encode neither double nor single quotes.
     * @return HtmlQuoteMode
     */
    static function NoQuotes()
    {
        return new self(ENT_NOQUOTES);    
    }
}