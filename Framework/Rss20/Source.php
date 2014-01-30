<?php
namespace Phine\Framework\Rss20;
/**
 * Represents an rss 2.0 source
 */
class Source
{
    /**
     *
     * @var string the source's url
     */
    public $Url;
    
    /**
     *
     * @var string the source's contents
     */
    public $Contents;
    
    /**
    * Constructs a new rss source. if contents is omitted,
    * this value is filled with the channel's title.
    */
    function __construct($url, $contents = null)
    {
        $this->Url = $url;
 	$this->Contents = $contents;  
    }
 }