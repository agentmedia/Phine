<?php
namespace Phine\Framework\Rss20;
/**
 * Represents an rss 2.0 image
 */
class Image
{
    /**
     *
     * @var string The image url
     */
    public $Url;
    
    /**
     *
     * @var string The image title
     */
    public $Title;
    /**
     *
     * @var string The image link
     */
    public $Link;

    /**
     *
     * @var string The image width
     */
    public $Width;
    
    /**
     *
     * @var string The image height
     */
    public $Height;
    
    /**
     *
     * @var string The image description
     */
    public $Description;
    /**
     * Constructs a channel image.
     * if $title or $link are omitted, missing values are
     * taken from the containing channel.
     * @param string $url The image url
     * @param string $title The image title
     * @param string $link The image link
     */
    function __construct($url, $title, $link)
    {
            $this->Url = $url;
            $this->Title = $title;
            $this->Link = $link;
    }
}
