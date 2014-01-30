<?php

namespace Phine\Framework\System\IO;
use Phine\Framework\System\Date;

/**
 * 
 * Provides methods for file operations
 */

class File
{
    
    /**
     * Checks if the path exists and is a regular file
     * @param string $path The file path
     * @return bool True if path exists and is a file
     */
    static function Exists($path)
    {
        if (file_exists($path))
            return is_file($path);
        
        return false;
    }
    
    static function IsReadable($path)
    {
        return is_readable($path);
    }
    
    /**
     * 
     * Deletes the file with the given path
     * @param $path The file path
     */
    static function Delete($path)
    {
        if (!self::Exists($path))
            throw new Exceptions\PathNotFoundException("File $path does not exist. Invalid path or not a regular file.");
        
        if (!@unlink($path))
            throw new Exceptions\PathNoAccessException("File $path cannot be deleted");   
    }
    
    /**
     * Creates a file and puts the text in it; if the file exists, the content is overwritten.
     * @param string $path
     * @param string $text 
     */
    static function CreateWithText($path, $text)
    {
        $dir = Path::Directory($path);
        if (!Folder::Exists($dir))
            Folder::Create($dir);
        
        file_put_contents($path, $text);
    }
    
    /**
     * Appends text to a specified file; if the file doesn't exist, it is created with the desired text.
     * @param string $path
     * @param string $text 
     */
    static function AppendText($path, $text)
    {
        file_put_contents($path, $text, FILE_APPEND);
    }
    
    /**
     * Reads all contents from a file into a string
     * @param string $path 
     * @return string
     */
    static function GetContents($path)
    {
        if (!self::Exists($path))
            throw new Exceptions\PathNotFoundException("File $path doesn't exist");
        
        if (!self::IsReadable($path))
            throw new Exceptions\PathNoAccessException("File $path cannot be read");
        
        return file_get_contents($path);
    }
    
    /**
     * Checks if the file exists and is writable
     * @param string $path
     * @return bool
     */
    static function IsWritable($path)
    {
        return self::Exists($path) && is_writable($path);
    }
    
    static function IsExecutable($path)
    {
         return self::Exists($path) && is_writable($path);
    }
    
    /**
     * 
     * @copyright Thanks to "Unsigned", posted on stackoverflow, 
     * from BSD-licensed SoloAdmin
     * @param string $path
     * @return int|string Gets the file size in bytes as string or integer
     */
    static function GetSize($path)
    {
        // If the platform is Windows...
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
        {
            // Try using the NT substition modifier %~z
            $size = trim(exec("for %F in (\"".$path."\") do @echo %~zF"));
            // If the return is blank, zero, or not a number
            if (!$size || !ctype_digit($size))
            {
                // Use the Windows COM interface
                $fsobj = new COM('Scripting.FileSystemObject');
                if (dirname($path) == '.')
                    $path = Path::Combine(getcwd(), Path::Filename($path));
                
                $f = $fsobj->GetFile($path);
                return $f->Size;
            }
             // Otherwise, return the result of the 'for' command
             return $size;
         }
         // If the platform is not Windows, use the stat command (should work for *nix and MacOS)
         return trim(exec("stat -c%s $path"));
    }
    
    /**
     * Returns the mime type as string
     * @param string $filename
     * @return string the mimetype
     */
    static function GetMimeType($path)
    {
        if (!self::IsReadable($path))
            throw new Exceptions\PathNoAccessException("File $path cannot be read");
        
        $finfo = \finfo_open(FILEINFO_MIME_TYPE);
        $mime = @\finfo_file($finfo, $path);
        if (!$mime)
            throw new \Exception("File info of $path not readable");
        
        return $mime;
    }
    
    
    /**
     * Gets the last modified date of the file or folder
     * @param string $path
     * @return Date Returns null if file doesn't exit or info is not accessible
     */
    static function GetLastModified($path)
    {
        $ts = @\filemtime($path);
        if ($ts !== false)
            return Date::FromTimeStamp($ts);
        
        return null;
    }
    /**
     * Moves a file to another location
     * @param string $source
     * @param string $destination
     * @return bool Returns true on success and false on error
     */
    static function Move($source, $destination)
    {
        if (!@\rename($source, $destination))
            throw new Exceptions\PathNoAccessException("Could not move file $source to destination $destination");
    }
}
