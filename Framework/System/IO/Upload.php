<?php
namespace Phine\Framework\System\IO;
require_once __DIR__ . '/File.php';
require_once __DIR__ . '/Path.php';

/**
 * 
 * Provides methods to handle uploaded file data
 */
class Upload
{
    
    /**
     * The form field name;
     * @var string 
     */
    private $fieldName;
    /**
     * Constructs upload by the form field name
     * @param string $fieldName 
     */
    function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }
    /**
     * Gets the name of the form upload field
     * @return string 
     */
    function FieldName()
    {
        return $this->fieldName;
    }
    /**
     * True if anything was received, but possibly errorous
     * @return bool
     */
    function NotEmpty()
    {
        return is_array($_FILES) && isset($_FILES[$this->fieldName]);
    }
    /**
     * True if a file was uploaded successfully
     * @return bool
     */
    function HasFile()
    {
        return $this->NotEmpty() && !$this->Error();
    }
    /**
     * Returns one of the UPLOAD_ERR_ integers
     * @return int
     */
    function Error()
    {
        return $this->GetProperty('error');
    }
    /**
     * 
     * Checks the upload and throws a corresponding exception
     * @throws Exceptions\UploadException
     */
    function Check()
    {
        $error = $this->GetProperty('error');
        if ($error > 0)
            throw new Exceptions\UploadException($this);
    }
    
    /**
     *
     * @param string $name
     * @return string
     * @throws \Exception 
     */
    private function GetProperty($name)
    {
        if ($this->NotEmpty())
        {
            if (isset($_FILES[$this->fieldName][$name]))
                return $_FILES[$this->fieldName][$name];
            
            return '';
        }
        throw new \Exception('No file uploaded for ' . $this->fieldName);
    }
    
    /**
     * The temporary file path to the uploaded file
     * @return string
     */
    function TempPath()
    {
        return $this->GetProperty('tmp_name');
    }
    
    /**
     * The filename retrieved by the uploader's name input (without directory)
     * @return string 
     */
    function Filename()
    {
        return Path::Filename($this->Name());
    }
    /**
     * The name (with dir) as given by the uploader
     * @return string
     */
    function Name()
    {
        return $this->GetProperty('name');
    }
    
    /**
     * moves the temporary, uploaded file to another destination
     * @param string $targetFolder The target folder
     * @param string $targetFilename If omitted, the user given file name is taken
     */
    function MoveTo($targetFolder, $targetFilename = '')
    {
        if (!$targetFilename)
            $targetFilename = $this->Filename();
        
        $destination = Path::Combine($targetFolder, $targetFilename);
        $result = @move_uploaded_file($this->TempPath(), $destination);
        if (!$result)
            throw new \Exception('Uploaded file could not be moved to target');
    }
}
