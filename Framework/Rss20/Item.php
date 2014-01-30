<?php
namespace Phine\Framework\Rss20;
use Phine\Framework\System\Date;
/**
 * 
 * Represents an rss 2.0 item
 */
class Item
{
    /**
     *
     * @var string The rss item's title
     */
    public $Title;
    
    /**
     *
     * @var string The rss item's comments
     */
    public $Link;
    
    /**
     *
     * @var string The rss item's description
     */
    public $Description;
    
    /**
     *
     * @var string The rss item's author
     */
    public $Author;
    
    /**
     *
     * @var Category The rss item's category
     */
    private $category;
    
    /**
     *
     * @var string The rss item's comments
     */
    public $Comments;
    
    
    /**
     *
     * @var string The rss item's guid
     */
    public $Guid;
    
    /**
     *
     * @var Date The rss item's pub date
     */
    private $pubDate;
    
    /**
     *
     * @var Source The rss item's source
     */
    private $source;
    
    /**
     *
     * @var Enclosure
     */
    private $enclosure;
    
    /**
     * Creates a new rss item
     * @param string $title The rss item's title
     * @param string $link The rss item's link
     * @param string $description The rss item's description
     */
    function __construct($title, $link, $description)
    {
        $this->Title = $title;
 	$this->Description = $description;
 	$this->Link = $link;
    }
    
    /**
     * Sets (or unsets) the pub date
     * @param Date $date
     */
    public function SetPubDate(Date $date = null)
    {
        $this->pubDate = $date; //->ToString('D, d M o G:i:s T');
    }
    
    /**
     * Gets the pub date, if set
     * @return Date
     */
    public function GetPubDate()
    {
        return $this->pubDate; //->ToString('D, d M o G:i:s T');
    }
    
    /**
     * Gets the item category
     * @return Category
     */
    public function GetCategory()
    {
        return $this->category;
    }
    
    /**
     * Sets the item category
     * @param Category $category
     */
    function SetCategory(Category $category = null)
    {
        $this->category = $category;
    }
    
    /**
     * Gets the item source
     * @return Source
     */
    function GetSource()
    {
        return $this->source;
    }
    
    /**
     * Sets (or unsets) the item source
     * @param Source $source
     */
    function SetSource(Source $source = null)
    {
        $this->source = $source;
    }
    
    /**
     * Gets the item enclosure
     * @return Enclosure
     */
    function GetEnclosure()
    {
        return $this->enclosure;
    }
    
    /**
     * Sets (or unsets) the item enclosure
     * @param Enclosure $enclosure
     */
    function SetEnclosure(Enclosure $enclosure = null)
    {
        $this->enclosure = $enclosure;
    }
 }
