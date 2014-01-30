<?php

namespace Phine\Framework\System\IO\Exceptions;
use Phine\Framework\System\IO\Upload;
/**
 * Excepion
 */
class UploadException extends \Exception
{
    /**
     *
     * @var Upload;
     */
    private $upload;
    function __construct(Upload $upload, $previous = null)
    {
        $this->upload = $upload;
        $code = $upload->Error();
        switch ($code)
        {
            case UPLOAD_ERR_INI_SIZE:
                $message = 'upload_max_filesize exceeded';
                break;
            
           case UPLOAD_ERR_FORM_SIZE:
                $message = 'The file exceeds the size allowed in the upload form';
                break;
            
            case UPLOAD_ERR_PARTIAL:
                $message = 'File uploaded only partially';
                break;
                
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'File could not be written to disk';
                break;
                
            case UPLOAD_ERR_NO_FILE:
                $message = 'No file was uploaded';
                break;
            
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = 'Temporary folder for uploads doesn\'t exist';
                break;
            
                
            case UPLOAD_ERR_EXTENSION:
                $message = 'File upload was stopped by an extension';
                break;
                
        }
        if ($previous)
            parent::__construct($message, $code, $previous);
        else
            parent::__construct($message, $code);
    }
    
    function GetUpload()
    {
        return $this->upload;
    } 
}
?>
