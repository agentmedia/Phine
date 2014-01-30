<?php
namespace Phine\Framework\System;

class PackageBuilder
{
    const DEPENDENCIES_FILE = '_Dependencies.php';
    const PACKAGE_FILE = '_Package.php';
    static function RequireFiles($packageFile)
    {
        
        $dir = dirname($packageFile);
        $handle = opendir($dir);
        self::RequireDependencies($dir);
        $subPackageFolders = array();
        
        while (false !== ($file = readdir($handle)))
        {
            
            $fullPath = $dir . '/' . $file;
            if (is_file($fullPath) && 
                $file != basename($packageFile) &&
                $file != self::DEPENDENCIES_FILE)
            {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == 'php')
                    require_once($fullPath);
            }
            else if (is_dir($fullPath) && $file != '.' && $file != '..')
            {
                //always require files, first so sub packages can use the folder contents. 
                $subPackageFolders[] = $fullPath;
                //self::RequireSubPackage($fullPath);
            }
        }
        foreach ($subPackageFolders as $fullPath)
        {
            self::RequireSubPackage($fullPath);
        }
        closedir($handle);
    }
    
    private static function RequireDependencies($dir)
    {
        $depFile = self::DEPENDENCIES_FILE;
        $depPath = $dir . '/' . $depFile;
        if (is_file($depPath))
            require_once($depPath);
    }
    
    private static function RequireSubPackage($dir)
    {
        $subPackageFile = $dir . '/' . self::PACKAGE_FILE;
        if (is_file($subPackageFile))
        {
            require_once($subPackageFile);    
        }
    }
}