<?php
namespace Phine\Framework\Rss20;

/**
 * 
 * Represents an rss 2.0 enclosure element (a media resource)
 */
class Enclosure
{
    /**
     *
     * @var string The enclosure url
     */
    public $Url;
    /**
     *
     * @var int The enclosure length
     */
    public $Length;
    /**
     *
     * @var string The enclosure (mime) type
     */
    public $Type;
    /**
     * Creates an rss 2.0 enclosure element describing a media resource
     * @param string $url The url to the enclosure
     * @param int $length The enclosure length in bytes
     * @param string $type The type, typically a mime type
     */
    function __construct($url, $length, $type)
    {
        $this->Url = $url;
 	$this->Length = $length;
 	$this->Type = $type; 
    }
 }
