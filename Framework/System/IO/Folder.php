<?php
namespace Phine\Framework\System\IO;

/** 
 * Provides methods like create, remove or copy a folder
 */
require_once __DIR__ . '/Path.php';
require_once __DIR__ . '/File.php';
require_once __DIR__ . '/FileSystemEntryType.php';

class Folder
{
    
    static function Exists($dir)
    {
        return file_exists($dir) && 
            self::GetFileSystemEntryType($dir) == FileSystemEntryType::Folder();
    }
    /**
     * Gets all files, directories and symbolic links (except current and parent folder) in a folder 
     * @param string $dir The folder path
     * @return string[] The filenames
     */
    static function GetFileSystemEntries($dir)
    {
        return self::GetSubEntriesOfType($dir, null);
    }
    
    /**
     * Gets all files from the folder 
     * @param string $dir The folder path
     * @return string[] The filenames
     */
    static function GetFiles($dir)
    {
        return self::GetSubEntriesOfType($dir, FileSystemEntryType::File());
    }
    
    /**
     * Gets all symbolic links in the folder 
     * @param string $dir The folder path
     * @return string[] The link names
     */
    static function GetLinks($dir)
    {
        return self::GetSubEntriesOfType($dir, FileSystemEntryType::Link);
    }
    
    /**
     * Gets all sub directories of the folder
     * @param string $dir The folder path
     * @return string[] The sub directories
     */
    static function GetSubFolders($dir)
    {
        return self::GetSubEntriesOfType($dir, FileSystemEntryType::Folder());
    }
    
    /**
     * The 
     * @param string $path
     * @return FileSystemEntryType
     */
    static function GetFileSystemEntryType($path)
    {
        if (is_file($path))
            return FileSystemEntryType::File();
        else if (is_link($path))
            return FileSystemEntryType::Link();
        else if (is_dir($path))
            return FileSystemEntryType::Folder();
        else
            throw new Exceptions\PathNotFoundException("Path $path not found");
    }
    /**
     * Returns true if the file sytem entry matches the given type
     * @param string $dir
     * @param string $file
     * @param FileSystemEntryType $type
     * @return bool
     */
    private static function FilterByType($dir, $file, FileSystemEntryType $type = null)
    {
        if ($file == '.' || $file == '..')
            return false;
        
        $path = Path::Combine($dir, $file);
        if (!$type)
            return true;
        
        switch ($type)
        {
            case FileSystemEntryType::File():
                return is_file($path);
                
            case FileSystemEntryType::Folder():
                return is_dir($path);
                
            case FileSystemEntryType::Link():
                return is_link($path);
        }
        return false;
    }
    /**
     * 
     * Gets all file system sub entries of folder of a specific type
     * @param string $dir
     * @param FileSystemEntryType $type
     * @return string[]
     */
    private static function GetSubEntriesOfType($dir, FileSystemEntryType $type = null)
    {
        if (!file_exists($dir))
            throw new Exceptions\PathNotFoundException("Path $dir not found");
        
        if (!is_dir($dir))
            throw new \InvalidArgumentException ("Path $dir is no (readable) folder");
        
        $handle = opendir($dir);
        $result = array();
        
        $file = readdir($handle);
        while ($file !== false)
        {   
            if (self::FilterByType($dir, $file, $type))
                $result[] = $file;
            
            $file = readdir($handle);
        }
        closedir($handle);
        return $result;
    }
    
    /**
     * Creates a folder; working recursively
     * @param string $dir The folder path
     * @param int $mode The access mode
     */
    static function Create($dir, $mode = 0777)
    {
        if (!mkdir($dir, $mode, true))
            throw new Exceptions\PathNoAccessException("Could not create folder $dir");
    }
    
    static function IsEmpty($dir)
    {
        return count(self::GetFileSystemEntries($dir));
    }
    
    /**
     * Deletes a folder and all of its contents
     * @param string $dir 
     */
    static function Delete($dir)
    {
        $files = self::GetFileSystemEntries($dir);
        
        foreach($files as $file)
        {
            $path = Path::Combine($dir, $file);
            switch(self::GetFileSystemEntryType($path))
            {
                case FileSystemEntryType::File():
                case FileSystemEntryType::Link():
                    unlink($path);
                    break;
                
                case FileSystemEntryType::Folder():
                    self::Delete($path);
                    break;
            }
        }
        rmdir($dir);
    }
    
    static function IsWritable($dir)
    {
        return self::Exists($dir) && is_writable($dir);
    }
}
