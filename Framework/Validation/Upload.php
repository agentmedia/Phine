<?php
namespace Phine\Framework\Validation;
use Phine\Framework\System\IO;
use Phine\Framework\System\IO\Enums\MimeType;

require_once  __DIR__ . '/Validator.php';
class Upload extends Validator
{
    /**
     *
     * @var IO\Upload
     */
    private $upload;
    
    /**
     * True if upload errors shall be checked
     * @var bool
     */
    private $checkUploadError = true;
    /**
     * True if the upload field must not be empty
     * @var bool
     */
    private $required = true;
    /**
     * 
     * @var int|string 
     */
    private $maxSize = 0;
    
    /**
     *
     * @var array[string]
     */
    private $mimeTypes;
    
    const TooBig = 'Validation.Upload.TooBig_{0}';
    const InvalidMimeType = 'Validation.Upload.InvalidMimeType';
    const NoFile = 'Validation.Upload.NoFile';
    const UploadError = 'Validation.Upload.UploadError';
    /**
     * 
     * @param type $fieldName
     * @param type $checkUploadError
     * @param type $required
     * @param int $maxSize max size in bytes
     * @param array $mimeTypes
     * @param string $errorLabelPrefix
     */
    function __construct($fieldName, $checkUploadError = true, $required = true, $maxSize = 0, array $mimeTypes = array(), $errorLabelPrefix = '')
    {
        $this->upload = new IO\Upload($fieldName);
        $this->maxSize = $maxSize;
        $this->required = $required;
        $this->checkUploadError = $checkUploadError;
        $this->mimeTypes = $mimeTypes;
        parent::__construct($errorLabelPrefix);
    }
    /**
     * Adds a valid mime type
     * @param MimeType $mimeType
     */
    public function AddValidMimeType(MimeType $mimeType)
    {
        $this->mimeTypes[] = (string)$mimeType;
    }
    
    /**
     * Remove a valid mime type
     * @param string $mimeType
     */
    public function RemoveValidMimeType(MimeType $mimeType)
    {
        $result = array();
        for($idx = 0; $idx < count($this->mimeTypes); ++$idx)
        {
            if ($this->mimeTypes[$idx] != (string)$mimeType)
                $result[] = $this->mimeTypes[$idx];
        }
        $this->mimeTypes = $result;
    }
    /**
     * Sets maximum upload size, in bytes
     * @param int $size
     */
    public function SetMaxBytes($size)
    {
        $this->maxSize = (int)$size;
    }
    /**
     * Sets maximum upload size, in kilobytes
     * @param int $size
     */
    public function SetMaxKB($size)
    {
        $this->SetMaxBytes(1024 * $size);
    }
    
    /**
     * Sets maximum upload size, in megabytes
     * @param int $size
     */
    public function SetMaxMB($size)
    {
        $this->SetMaxKB(1024 * $size);
    }
    
    /**
     * Sets maximum upload size, in gigabytes
     * @param int $size
     */
    public function SetMaxGB($size)
    {
        $this->SetMaxMB(1024 * $size);
    }
    
    public function Check($value)
    {
        //use value to avoid ide warning...
        $value = null;
        $this->error = '';
        
        if (!$this->required && !$this->upload->NotEmpty())
            return true;
        
        if ($this->required && $this->HasNoFile())
            $this->error = self::NoFile;
        
        else if ($this->upload->HasFile() && $this->checkUploadError && ($error = $this->GetUploadError()))
            $this->error = self::UploadError . $error;
        
        else if ($this->upload->HasFile() && $this->maxSize > 0 && $this->IsTooBig())
        {
            $this->error = self::TooBig;
        }
        else if ($this->upload->HasFile() && $this->InvalidMimeType())
        {
            $this->error = self::InvalidMimeType;
        }
        return $this->error == '';
    }
    
    protected function ErrorParams()
    {
        $params = array();
        if ($this->maxSize > 0)
            $params[] = $this->maxSize;
        
        return $params;
    }
    private function GetUploadError()
    {
        $notEmpty = $this->upload->NotEmpty();
        if ($notEmpty)
            return $this->upload->Error();
    }
    private function HasNoFile()
    {
        return !$this->upload->HasFile();
    }
    
    private function IsTooBig()
    {   
        return $this->upload->NotEmpty() &&
                IO\File::GetSize($this->upload->TempPath()) > $this->maxSize; 
    }
    
    
    private function InvalidMimeType()
    {
        if (count($this->mimeTypes) == 0)
            return true;
        
        $mimeType = IO\File::GetMimeType($this->upload->TempPath());
        return !in_array($mimeType, $this->mimeTypes);
    }
    
}