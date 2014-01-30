<?php
namespace Phine\Framework\Localization;
use Phine\Framework\System\IO;
use Phine\Framework\Localization\Interfaces as LocInterfaces;
/** 
 * 
 * Provides a translator based on csv files
 * 
 */
class CsvTranslator extends Base\FormatTranslator
{
    /**
     * 
     * The base file path of the csv files
     * @var array
     */
    private $basePaths = array();
    
    /**
     * 
     * The current language
     * @var string
     */
    private $language = '';
    
    /**
     * The csv delimiter
     * @var string
     */
    private $delimiter;
    
    /**
     * The csv enclosure
     * @var string
     */
    private $enclosure;
    
    /**
     *
     * @var array
     */
    private $translations = array();
    
    /**
     * The default language from constructor
     * @var string
     */
    private $defaultLanguage;
    /**
     * Creates a new csv translator
     * @param $basePath The base path for the filenames; 
     * @param string $lang A language code
     * @example If you name have csv files /root/Translation.en.csv, /root/Translation.de.csv and so on, set basePath=/root/Translation.csv
     */
    function __construct($basePath, $defaultLanguage, $delimiter = ";", $enclosure='"')
    {
        $this->basePaths[] = $basePath;
        $this->defaultLanguage = $defaultLanguage;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->translations = array();
    }
    /**
     * Adds another csv files base path to the translator; Must be called before method SetLanguage
     * @param string $basePath 
     */
    function AddBasePath($basePath)
    {
        $this->basePaths[] = $basePath;
    }
    
    /**
     * Gets the csv file path for the given language
     * @param string $language
     * @return string
     */
    private function LanguageCsvFile($basePath)
    {
        $ext = IO\Path::Extension($basePath);
        $file = IO\Path::RemoveExtension($basePath);
        $file = IO\Path::AddExtension($file, $this->language);
        return  IO\Path::AddExtension($file, $ext);
    }
    
    /**
     * Reads translations from language file if not already done
     * @param type $language
     * @return type 
     */
    private function ReadTranslations()
    {
        if (isset($this->translations[$this->language]))
                return;
        
        $this->translations[$this->language] = array();
        foreach ($this->basePaths as $basePath)
        {
            $handle = @fopen($this->LanguageCsvFile($basePath), 'r');
            if (!$handle)
                return;
       
            while ($result = fgetcsv($handle, 0, $this->delimiter, $this->enclosure))
            {
                if (count($result) != 2)
                    throw new \Exception('Translation CSV needs exacty two columns');
                
                $key = $result[0];
                if (array_key_exists($key, $this->translations[$this->language]))
                        throw new \Exception("Translation key '$key' is defined more then once");
                
                $this->translations[$this->language][$key] = $result[1]; 
            }
        }
    }
    
    public function GetLanguage()
    {
        return $this->language;
    }
    
    public function SetLanguage($language = null)
    {
        if (!$language)
            $language = $this->defaultLanguage;
        
        if ($language != $this->language)
        {
            $this->language = $language;
            $this->ReadTranslations();
        }
    }

    protected function GetReplacement($placeholder)
    {
        $transLang = $this->translations[$this->language];
        if ($transLang !== null)
        {
            if (isset($transLang[$placeholder]))
                return $transLang[$placeholder];
        }
        return $placeholder;
    }
}