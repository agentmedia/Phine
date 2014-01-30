<?php
namespace Phine\Framework\Rss20;
use Phine\Framework\System\Date;

class Channel
{
    
    /**
     *
     * @var array[Item] The channel's items
     */
    private $items = array();
    /**
     *
     * @var string The channel's title
     */
    public $Title;
    
    /**
     *
     * @var string The channel's link
     */
    public $Link;
    
    /**
     *
     * @var string The channel's description
     */
    public $Description;

    /**
     *
     * @var string The channel's generator
     */
    public $Generator;
    
    /**
     *
     * @var string The channel's copyright credits
     */
    public $Copyright;
    
    /**
     *
     * @var string The channel's language
     */
    public $Language;
    
    /**
     *
     * @var string The channel's webmaster
     */
    public $WebMaster;
    
    /**
     *
     * @var string The channel's managing editor
     */
    public $ManagingEditor;
    
    /**
     *
     * @var Date The channel's publication date
     */
    private $pubDate;
    
    /**
     *
     * @var Date The channel's last build date
     */
    private $lastBuildDate;
    
    /**
     *
     * @var int The channel's time to live (optimal client's caching time) in minutes
     */
    public $Ttl;
    
    /**
     *
     * @var string A doc link for the rss specification
     */
    public $Docs;
    
    /**
     *
     * @var string The channel's rating explaining the feed contents according to http://www.w3.org/PICS
     */
    public $Rating;
    
    /**
     *
     * @var SkipHours The hours skipped from feed regeneration
     */
    private $skipHours;
    
    /**
     *
     * @var SkipDays The days skipped from feed regeneration
     */
    private $skipDays;
    
    /**
     *
     * @var Category The channel category
     */
    private $category;
    
    /**
     * @var Cloud The cloud of the rss 2.0 element
     */
    private $cloud;
    
    /**
     *
     * @var TextInput
     */
    private $textInput;
    
    /**
     *
     * @var Image
     */
    private $image;

    /**
     * 
     * Creates an rss channel element
     * @param string $title
     * @param string $link
     * @param string $description
     */
    function __construct($title, $link, $description)
    {
            $this->Title = $title;
            $this->Description = $description;
            $this->Link = $link;
    }
    
    /**
     * Sets the (or unsets) last build date
     * @param Date $lastBuildDate
     */
    function SetLastBuildDate(Date $lastBuildDate = null)
    {
        $this->lastBuildDate = $lastBuildDate;
    }
    
    /**
     * Gets the last build date
     * @return Date
     */
    function GetLastBuildDate()
    {
        return $this->lastBuildDate;
    }
    
    /**
     * 
     * Sets (or unsets) the publishing date
     * @param Date $pubDate The publishing date
     */
    function SetPubDate(Date $pubDate = null)
    {
        $this->pubDate = $pubDate;
    }
    
    /**
     * Gets the publishing date
     * @return Date
     */
    function GetPubDate()
    {
        return $this->pubDate;
    }
    
    /**
     * Gets the skip days
     * @return SkipDays
     */
    function GetSkipDays()
    {
        return $this->skipDays;
    }
    
    /**
     * Sets (or unsets) the skip days
     * @param SkipDays $skipDays
     */
    function SetSkipDays(SkipDays $skipDays = null)
    {
        $this->skipDays = $skipDays;
    }
    
    /**
     * Gets the skip hours
     * @return SkipHours
     */
    function GetSkipHours()
    {
        return $this->skipHours;
    }
    
    /**
     * Sets (or unsets) the skip hours
     * @param SkipHours $skipHours
     */
    function SetSkipHours(SkipHours $skipHours = null)
    {
        $this->skipHours = $skipHours;
    }
    
    /**
     *  Sets (or unsets) the channel image
     * @param Image $image
     */
    function SetImage(Image $image = null)
    {
        $this->image = $image;
    }
    
    /**
     * Gets the channel image
     * @return Image
     */
    function GetImage()
    {
        return $this->image;
    }
    
    /**
     * Sets (or unsets) the channel text input
     * @param TextInput $textInput
     */
    function SetTextInput(TextInput $textInput = null)
    {
        $this->textInput = $textInput;
    }
    
    /**
     * Gets the text input
     * @return TextInput
     */
    function GetTextInput()
    {
        return $this->textInput;
    }
    
    /**
     * Gets the channel cloud
     * @return Cloud
     */
    function GetCloud()
    {
        return $this->cloud;
    }
    
    /**
     * Sets (or unsets) The channel cloud
     * @param Cloud $cloud
     */
    function SetCloud(Cloud $cloud = null)
    {
        $this->cloud = $cloud;
    }
    
    /**
     * Gets the channel category
     * @return Category
     */
    function GetCategory()
    {
        return $this->category;
    }
    
    /**
     * Sets (or unsets) the channel category
     * @param Category $category
     */
    function SetCategory(Category $category = null)
    {
        $this->category = $category;
    }
    
    /**
     * Adds an item to the items list
     * @param Item $item
     */
    function AddItem(Item $item)
    {
        $this->items[] = $item;
    }
    
    
    /**
     * Removes an item from the items list
     * @param int $idx The item position
     */
    function RemoveItem($idx)
    {
        $result = array();
        foreach ($this->items as $currentIdx => $item)
        {
            if ($currentIdx != $idx)
                $result[] = $item;
        }
        $this->items = $result;
    }
    
    /**
     * Gets the channel items
     * @return array[Item]
     */
    function GetItems()
    {
        return $this->items;
    }
}
