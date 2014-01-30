<?php

namespace Phine\Framework\System\IO\Enums;

use Phine\Framework\System\Enum;

/**
 * Mime types (not complete)
 */
class MimeType extends Enum
{
    /**
     * The mime type 'image/gif'
     * @return MimeType
     */
    static function Gif()
    {
        return new self('image/gif');
    }
    
    /**
     * The mime type 'image/jpeg'
     * @return MimeType
     */
    static function Jpeg()
    {
        return new self('image/jpeg');
    }

    /**
     * The mime type 'image/png'
     * @return MimeType
     */
    static function Png()
    {
        return new self('image/png');
    }
}