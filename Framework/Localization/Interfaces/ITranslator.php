<?php
namespace Phine\Framework\Localization\Interfaces;
use Phine\Framework\Wording;

interface ITranslator
{
    /**
     * Returns the languange
     * @return mixed An object with language information.
     */
    function GetLanguage();
    
    /**
     * Sets the language. If null, a default language should be set.
     * 
     */
    function SetLanguage($language = null);
}