<?php
namespace Phine\Framework\Rss20;

/**
 * 
 * Represents an rss 2.0 category
 */
class Category
{
    /**
     *
     * @var string The contents of the Rss 2.0 category
     */
    public $Contents;
    
    /**
     *
     * @var string The domain of the Rss 2.0 category
     */
    public $Domain;
    
    /**
     * Creates a new rss 2.0 category
     * @param string $contents
     * @param string $domain
     */
    function __construct($contents, $domain = null)
    {
        $this->Contents = $contents;
  	$this->Domain = $domain;   
    }
}
