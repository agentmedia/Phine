<?php
namespace Phine\Framework\System;

/**
 * Static class optimized for multibyte strings
 */
class String
{
    /**
     * 
     * @var Encoding
     */
    private static $encoding = null;

    /**
     * 
     * @param Encoding $encoding
     * @return void
     */
    public static function SetEncoding(Encoding $encoding)
    {
        self::$encoding = $encoding;
        mb_internal_encoding($encoding->Code());
    }
    /**
     * Returns current encoding. It is utf-8 by default.
     * @param $encoding
     * @return Encoding
     */
    public static function GetEncoding()
    {
        self::InitEncoding();
        return self::$encoding;
    }
    
    private static function InitEncoding()
    {
        if (self::$encoding == null)
        {
            self::SetEncoding(Encoding::UTF_8());
        }    
    }
    
    /**
     * Escapes special chars.
     * @param string $string
     * @param HtmlQuoteMode $mode If omitted, only double quotes are encoded.
     * @return string
     */
    static function ToHtml($string, HtmlQuoteMode $mode = null)
    {
        self::InitEncoding();
        if (!$mode)
            $mode = HtmlQuoteMode::DoubleQuotes();
                
        return htmlspecialchars($string, $mode->Flag(), self::GetEncoding()->Code());
    }
        
        /**
         * Creates a single quoted string that can be put into javascript variables.
         * @param string $string The input string
         */
        static function ToJavascript($string)
        {    
            $string = self::ToHtml($string);
            
            return '\'' . addcslashes($string, "'\r\n\\") . '\'';
        }
    
    /**
     * Returns length of string.
     * @param $string
     * @return int
     */
    static function Length($string)
    {
        self::InitEncoding();
        return mb_strlen($string);
    }
    
    /**
     * Returns length of string.
     * @param $string
     * @return int
     */
    static function Part($string, $start, $length = null)
    {
        self::InitEncoding();
        if ($length !== null)
            return mb_substr($string, $start, $length);

        return mb_substr($string, $start);
    }
    
    static function Start($string, $length = null)
    {
        self::InitEncoding();
        return self::Part($string, 0, $length);
    }
    
    /**
     * Gets the end part of the string with the given length
     * @param \string $string
     * @param int $length
     * @return \string
     */
    static function End($string, $length = 0)
    {    
        self::InitEncoding();
        $pos = max(0, self::Length($string) - $length);
        return self::Part($string, $pos);
    }

    static function Compare($string1, $string2, $ignoreCase = false)
    {
        self::InitEncoding();
        if ($string1 == $string2)
            return true;
        
        if ($ignoreCase)
            return mb_strtolower($string1) == mb_strtolower($string2);
        
        return false;        
    }
    static function StartsWith($needle, $haystack, $ignoreCase = false)
    {
        self::InitEncoding();
        $start = self::Start($haystack, self::Length($needle));
        return self::Compare($start, $needle, $ignoreCase);
    }
    
    static function EndsWith($needle, $haystack, $ignoreCase = false)
    {
        self::InitEncoding();
        $end = self::End($haystack, self::Length($needle));
        return self::Compare($end, $needle, $ignoreCase);
    }
    
    
    static function Contains($needle, $haystack, $ignoreCase = false)
    {
        self::InitEncoding();
        if (!$ignoreCase)
            return mb_strpos($haystack, $needle) !== false;
        else
            return mb_stripos ($haystack, $needle) !== false;
    }
    static function ToUpper($string)
    {
        self::InitEncoding();
        return mb_strtoupper($string);
    }
    
    static function ToLower($string)
    {
        self::InitEncoding();
        return mb_strtolower($string);
    }
    /**
     * Returns true if the string consists of letters from a-z only; case insensitive
     * @param $string
     * @return bool
     */
    static function IsLetter($string)
    {
        return preg_match("/[A-Z]/i", $string) > 0 ? true : false;

    }
    
    /**
     * Returns true if the string consists of numbers 0-9 only
     * @param $string
     * @return bool
     */
    static function IsDigit($string)
    {
        return ctype_digit($string);

    }
    
    /**
     * Trims leading and trailing chars.
     * @param string $string
     * @param string $chars
     * @return string The trimmed string
     */
    static function Trim($string, $chars = null)
    {
        self::InitEncoding();
        if (!$chars)
            return trim($string);

        return trim($string, $chars);
    }
    /**
     * Trims trailing chars.
     * @param string $string
     * @param string $chars
     * @return string The trimmed string
     */
    static function TrimRight($string, $chars = null)
    {
        self::InitEncoding();
        if (!$chars)
            return rtrim($chars);

        return rtrim($string, $chars);
    }
    
    /**
     * Trims leading chars.
     * @param string $string
     * @param string $chars
     * @return string The trimmed string
     */
    static function TrimLeft($string, $chars = null)
    {
        self::InitEncoding();
        if (!$chars)
            return ltrim($chars);

        return ltrim($string, $chars);
    }
        
    /**
    * Splits the string into its lines
    * @param string $string The input string
    * @return string[] All lines of the string
    */
    static function SplitLines($string)
    {
            return preg_split( '/\r\n|\r|\n/', $string);
    }
    
    static function Replace($search, $replace, $subject)
    {
        return str_replace($search, $replace, $subject);
    }
    
    /**
     * 
     * Replaces characters that are potentially dangerous when used as part of a path.
     * @param string $name
     * @param array $charmap A user defined character map, e.g. array('ö'=>'oe', ...)
     * @return string A string that consists of letters, digits, minus characters and the provided charmap values only. If null, the SpecialWesternCharMap is used
     */
    static function DefuseName($name, array $charmap = null)
    {
        if ($charmap === null)
            $charmap = self::SpecialWesternLetterMap();
        
        $reader = new StringReader($name);
        $prevChar = '';
        $result = '';
        while (false !== ($ch = $reader->ReadChar()))
        {
            $newChar = '';
            if (self::IsLetter($ch) || self::IsDigit($ch))
                $newChar = $ch;
            
            else if (array_key_exists($ch, $charmap))
                $newChar = $charmap[$ch];
               
            else if ($prevChar != '-')
                $newChar = '-';
           
            $result .= $newChar;
            
            if ($newChar)
                $prevChar = $newChar;
        }
        return self::Trim($result, '-');
    }
    /**
     * 
     * Provides a .Net style format routine with placeholders in the style {0}
     * @param \string $string 
     * @param \mixed $string,... optional placeholder replacements
     */
    static function Format($string)
    {
        $args = func_get_args();
        array_shift($args);
        return String::FormatArgs($string, $args);
    }
    
    /**
     * 
     * Provides a .Net style format routine with placeholders in the style {0}
     * @param \string $string 
     * @param array $args Optional placeholder replacements
     */
    static function FormatArgs($string, array $args = array())
    {
        $result = $string;
        for($index = 0; $index < count($args); ++$index)
        {
            $placeholder = '{' . $index  . '}';
            $result = String::Replace($placeholder, (string)$args[$index], $result);
        }
        return $result;
    }
    /**
     * Maps all special chars to the given string
     * @param string $specialChars
     * @param string $mapped 
     */
    private static function AddSpecialCharsToMap($specialChars, $mapped)
    {
        $reader = new StringReader($specialChars);
        
        while($ch = $reader->ReadChar())
        {
            self::$specialWesternCharMap[$ch] = $mapped;
        }
    }
    /**
     * @var array 
     */
    private static $specialWesternCharMap = null;
    static function SpecialWesternLetterMap()
    {
        if (!is_array(self::$specialWesternCharMap))
        {
            self::$specialWesternCharMap = array();
            self::AddSpecialCharsToMap('Ä', 'Ae');
            self::AddSpecialCharsToMap('ÀÁÂÃǍ', 'A');
            self::AddSpecialCharsToMap('ä', 'ae');
            self::AddSpecialCharsToMap('àáâãǎ', 'a');
            self::AddSpecialCharsToMap('ÇĆĈČ', 'c');
            self::AddSpecialCharsToMap('çćĉč', 'c');
            self::AddSpecialCharsToMap('ĎĐ', 'D');
            self::AddSpecialCharsToMap('đ', 'd');
            self::AddSpecialCharsToMap('ÈÉÊËĚ', 'E');
            self::AddSpecialCharsToMap('èéêëě', 'e');
            self::AddSpecialCharsToMap('ĜĢ', 'G');
            self::AddSpecialCharsToMap('ĝ', 'g');
            self::AddSpecialCharsToMap('Ĥ', 'H');
            self::AddSpecialCharsToMap('ĥ', 'h');
            self::AddSpecialCharsToMap('ÌÍÎ', 'I');
            self::AddSpecialCharsToMap('íîï', 'i');
            self::AddSpecialCharsToMap('Ĵ', 'J');
            self::AddSpecialCharsToMap('ĵ', 'j');
            self::AddSpecialCharsToMap('Ķ', 'K');
            self::AddSpecialCharsToMap('ķ', 'k');
            self::AddSpecialCharsToMap('ĹĻŁ', 'L');
            self::AddSpecialCharsToMap('ĺļł', 'l');
            self::AddSpecialCharsToMap('ÑŃŇ', 'N');
            self::AddSpecialCharsToMap('ñńň', 'n');
            self::AddSpecialCharsToMap('Ö', 'Oe');
            self::AddSpecialCharsToMap('ÒÓÔÕŐ', 'O');
            self::AddSpecialCharsToMap('ö', 'oe');
            self::AddSpecialCharsToMap('òóôõøő', 'o');
            self::AddSpecialCharsToMap('ŔŘ', 'R');
            self::AddSpecialCharsToMap('ŕř', 'r');
            self::AddSpecialCharsToMap('ß', 'ss');
            self::AddSpecialCharsToMap('ŚŜŞŠ', 'S');
            self::AddSpecialCharsToMap('śŝşš', 's');
            self::AddSpecialCharsToMap('Ť', 'T');
            self::AddSpecialCharsToMap('Ü', 'ue');
            self::AddSpecialCharsToMap('ÙÚÛŰŨŲ', 'U');
            self::AddSpecialCharsToMap('ü', 'ue');
            self::AddSpecialCharsToMap('ùúûũųű', 'u');
            self::AddSpecialCharsToMap('Ŵ', 'W');
            self::AddSpecialCharsToMap('ŵ', 'w');
            self::AddSpecialCharsToMap('ÝŶŸ', 'Y');
            self::AddSpecialCharsToMap('ýÿŷ', 'y');
            self::AddSpecialCharsToMap('ŹŽ', 'Z');
            self::AddSpecialCharsToMap('źž', 'z');
        }
        return self::$specialWesternCharMap;
    }
    /**
     * Tokenizes a string by special split chars
     * @param string $str
     * @param string $splitChars
     * @return array[string] Tokenized string
     */
    static function Tokenize($string, $splitChars = ' %$§&/()?!.:;\#\'"')
    {
       $tokens = array();

       $tok = strtok($string,  $splitChars);
       while ($tok !== false)
       {
            array_push($tokens, $tok);
            $tok = strtok($splitChars);
       }
       return $tokens;
    }
    /**
     * Non standard base64 encoding that results in url/filename compatible result
     * @param string $data The data to encode
     * @return string The encoded string
     */
    static function Base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    /**
     * Decodes a string encoded by Base64Encode
     * @param string $data The encode data
     * @return string The decoded data
     */
    static function Base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}