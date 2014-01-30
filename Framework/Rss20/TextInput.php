<?php

namespace Phine\Framework\Rss20;

/**
 * Represents an rss 2.0 text input
 */
class TextInput
{
    /**
     *
     * @var string the text input's title
     */
    public $Title;
    
    /**
     *
     * @var string the text input's description
     */
    public $Description;
    
    /**
     *
     * @var string the text input's name
     */
    public $Name;
    
    /**
     *
     * @var string the text input's link
     */
    public $Link;
    
    /**
     * 
     * @param string $title The text input's title
     * @param string $description The text input's description
     * @param string $name The text input's name
     * @param string $link  The text input's link
     */
    function __construct($title, $description, $name, $link)
    {
        $this->Title = $title;
        $this->Description = $description;
        $this->Name = $name;
        $this->Link = $link;
    }
}
