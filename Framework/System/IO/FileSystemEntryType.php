<?php

namespace Phine\Framework\System\IO;
use Phine\Framework\System;

/** 
 * Provides an enum for file system entry types
 */

class FileSystemEntryType extends System\Enum
{
   static function File()
   {
       return new self('File');
   }
   
   
   static function Folder()
   {
       return new self('Folder');
   }
   
   static function Link()
   {
       return new self('Link');
   }
}
