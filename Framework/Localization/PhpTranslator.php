<?php

namespace Phine\Framework\Localization;
use Phine\Framework\Localization\Base;

/**
 * Provides methods to store translations within php files
 */
class PhpTranslator extends Base\FormatTranslator
{
    /**
     *
     * @var PhpTranslator
     */
    private static $singleton;
    
    /**
     * The translations
     * @var array 
     */
    private $translations = array();
    
    /**
     * The currently used language
     * @var string 
     */
    private $language;
    
    /**
     * The default language
     * @var string
     */
    private $defaultLanguage;
     /**
     * Creates a new instanceof the php translator
     * @return PhpTranslator
     */
    private function __construct($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }
    /**
     * Returns a singleton instance
     * @return PhpTranslator
     */
    public static function Singleton()
    {
        if (!self::$singleton)
            self::$singleton = new self('en');
        
        return self::$singleton;
    }
    
    /**
     * Gets the replacement text of the placeholder
     * @param string $placeholder
     * @return string
     */
    protected function GetReplacement($placeholder)
    {
        if(isset($this->translations[$this->language]))
        {
            $langTranslations = $this->translations[$this->language];
            if (isset($langTranslations[$placeholder]))
                return $langTranslations[$placeholder];
        }
        return $placeholder;
    }
    
    /**
     * Gets the current language
     * @return string
     */
    public function GetLanguage()
    {
        return $this->language;
    }
    
    /**
     * Sets the current language
     */
    public function SetLanguage($language = null)
    {
        if (!$language)
            $this->language = $this->defaultLanguage;
        else
            $this->language = $language;
    }
    
    /**
     * Adds a translation to the translator
     * @param string $language
     * @param string $placeholder
     * @param string $text 
     */
    public function AddTranslation($language, $placeholder, $text)
    {
        $this->translations[$language][$placeholder] = $text;
    }
}