<?php
namespace Phine\Framework\Rss20;

/**
 * 
 * Implements methods to write rss 2.0 documents
 */
class Writer
{
    /**
     *
     * @var \DOMDocument
     */
    private $domDoc;
    
    /**
     *
     * @var the rss xml dom element
     */
    private $rssElement;
    /**
     *
     * @var \DOMElement The channel xml dom element
     */
    private $channel;
    
    /**
     *
     * @var Channel the current channel element class 
     */
    private $rssChannel;
    
    /**
     *
     * @var Feed
     */
    private $feed;
    
    /**
     * Createas a new rss writer
     */
    function __construct(Feed $feed)
    {
        $this->domDoc = new \DOMDocument("1.0", "utf-8");
 	$this->domDoc->formatOutput = true;
 	$this->CreateRssElement();
        $this->feed = $feed;
        $this->domDoc->appendChild($this->rssElement);
        $channels = $this->feed->GetChannels();
        foreach($channels as $rssChannel)
        {
            $this->AddChannel($rssChannel);
        }
    }
    
    /**
     * Adds an item
     * @param Item $rssItem The rss item
     * @param bool $useCDATA Wrap description with CDATA
     */
    private function AddItem(Item $rssItem, $useCDATA = true)
    {
            $itemEl = $this->domDoc->createElement("item");

            if($rssItem->Title != null)
              $this->AppendTextChild($itemEl, "title", $rssItem->Title);

            if ($rssItem->Link != null)
              $this->AppendTextChild($itemEl, "link", $rssItem->Link);

            if ($rssItem->Description != null)
            {
                    if ($useCDATA)
                      $this->AppendCDATAChild($itemEl, "description", $rssItem->Description);
                    else
                      $this->AppendTextChild($itemEl, "description", $rssItem->Description);
            }

            if ($rssItem->GetPubDate() != null)
              $this->AppendTextChild($itemEl, "pubDate", $rssItem->GetPubDate()->ToString(\DATE_RSS));

            if ($rssItem->Author != null)
              $this->AppendTextChild($itemEl, "author", $rssItem->Author);

            if ($rssItem->Comments != null)
              $this->AppendTextChild($itemEl, "comments", $rssItem->Comments);

            if ($rssItem->Guid != null)
              $this->AppendTextChild($itemEl, "guid", $rssItem->Guid);
            
            //objects
            $this->AddCategory($itemEl, $rssItem->GetCategory());
            $this->AddSource($itemEl, $rssItem->GetSource());
            $this->AddEnclosure($itemEl, $rssItem->GetEnclosure());
            $this->channel->appendChild($itemEl);
    }
    
    
    private function AddChannel(Channel $rssChannel)
    {
        $this->rssChannel = $rssChannel;
        $this->channel = $this->domDoc->createElement("channel");
        $this->AppendTextChild($this->channel, "title", $rssChannel->Title);
        $this->AppendTextChild($this->channel, "link", $rssChannel->Link);
        $this->AppendTextChild($this->channel, "description", $rssChannel->Description);

        // Adding optional elements
        if ($rssChannel->GetPubDate() != null)
            $this->AppendTextChild($this->channel, "pubDate", $rssChannel->GetPubDate()->ToString(\DATE_RSS));
        
        if ($rssChannel->GetLastBuildDate())
            $this->AppendTextChild($this->channel, "pubDate", $rssChannel->GetLastBuildDate()->ToString(\DATE_RSS));
            
        if ($rssChannel->Copyright != null)
            $this->AppendTextChild($this->channel, "copyright", $rssChannel->Copyright);

        if ($rssChannel->Language != null)
            $this->AppendTextChild($this->channel, "language", $rssChannel->Language);

        if ($rssChannel->Generator != null)
          $this->AppendTextChild($this->channel, "generator", $rssChannel->Generator);

        if ($rssChannel->WebMaster != null)
          $this->AppendTextChild($this->channel, "webMaster", $rssChannel->WebMaster);

        if ($rssChannel->ManagingEditor != null)
          $this->AppendTextChild($this->channel, "managingEditor", $rssChannel->ManagingEditor);

        if ($rssChannel->Ttl != null)
          $this->AppendTextChild($this->channel, "ttl", $rssChannel->Ttl);

        if ($rssChannel->Docs != null)
          $this->AppendTextChild($this->channel, "docs", $rssChannel->Docs);

            
        
        // adding objects
        $this->AddSkipDays($this->channel, $rssChannel->GetSkipDays());
        $this->AddSkipHours($this->channel, $rssChannel->GetSkipHours());
        $this->AddCategory($this->channel, $rssChannel->GetCategory());
        $this->AddImage($this->channel, $rssChannel->GetImage());
        $this->AddCloud($this->channel, $rssChannel->GetCloud());
        $this->AddTextInput($this->channel, $rssChannel->GetTextInput());

        $this->rssElement->appendChild($this->channel);
        $items = $rssChannel->GetItems();
        foreach ($items as $rssItem)
        {
            $this->AddItem($rssItem);
        }
    }
    
    private function AddSkipDays(\DOMElement $docParent, SkipDays $skipDays = null)
    {
        if ($skipDays)
        {
            $skipDaysElement = $this->domDoc->createElement('skipDays');
            foreach ($skipDays->GetDays() as $day)
            {
                $this->AppendTextChild($skipDaysElement, 'day', (string)$day->DayOfWeek());
            }
            $docParent->appendChild($skipDaysElement);
        }
    }
    
    
    private function AddSkipHours(\DOMElement $docParent, SkipHours $skipHours = null)
    {
        if ($skipHours)
        {
            $skipHoursElement = $this->domDoc->createElement('skipHours');
            foreach ($skipHours->GetHours() as $hour)
            {
                $this->AppendTextChild($skipHoursElement, 'hour', (string)$hour->Hour);
            }
            $docParent->appendChild($skipHoursElement);
        }
    }
    

    private function AddEnclosure(\DOMElement $docParent, Enclosure $rssEnclosure = null)
    {
 	if ($rssEnclosure != null)
        {
            $encEl = $this->domDoc->createElement();
            $this->SetAttribute($encEl, "url", $rssEnclosure->Url);
            $this->SetAttribute($encEl, "length", $rssEnclosure->Length);
            $this->SetAttribute($encEl, "type", $rssEnclosure->Type);
            $docParent->appendChild($encEl);
 	}
    }
        
        
    private function AddTextInput(\DOMElement $docParent, TextInput $rssTextInput = null)
    {
        if ($rssTextInput != null)
        {
            $txtInputEl = $this->domDoc->createElement("textInput");
            $this->AppendTextChild($txtInputEl, "title", $rssTextInput->Title);
            $this->AppendTextChild($txtInputEl, "name", $rssTextInput->Name);
            $this->AppendTextChild($txtInputEl, "description", $rssTextInput->Description);
            $this->AppendTextChild($txtInputEl, "link", $rssTextInput->Link);
            $docParent->appendChild($txtInputEl);
        }
    }
    /**
     * Saves the rss feed to the given filename
     * @param string $filename The filename for saving
     * @return int The number of bytes written
     * @throws \Exception Raises exception on failure
     */
    function Save($filename)
    {
        $result = @$this->domDoc->save($filename);
        
        if ($result === false)
            throw new \Exception("RSS Feed saviing to '$filename' failed");
        
        return $result;
    }
    
    /**
     * Gets the rss feed xml
     * @return string Returns the RSS feed url
     * @throws \Exception Raises exception on failure
     */
    function GetXml()
    {
        $result = @$this->domDoc->saveXML();
        
        if ($result === false)
              throw new \Exception("Rss xml generation failed");
        
        return $result;
    }
    
    private function AddImage(\DOMElement $docParent, Image $rssImage = null)
    {
        if ($rssImage != null)
        {
            $imgEl = $this->domDoc->createElement("image");
            $this->AppendTextChild($imgEl, "url", $rssImage->Url);
            $title = $this->DefaultValue($rssImage, $this->rssChannel, "Title");
            $this->AppendTextChild($imgEl, "title", $title);
            $link = $this->DefaultValue($rssImage, $this->rssChannel, "Link");
            $this->AppendTextChild($imgEl, "link", $link);
            
            if ($rssImage->Description != null)
                $this->AppendTextChild($imgEl, "description", $rssImage->Description);
            
            if ($rssImage->Width != null)
                $this->AppendTextChild($imgEl, "width", $rssImage->Width);

            if ($rssImage->Height != null)
                $this->AppendTextChild($imgEl, "height", $rssImage->Height);

            $docParent->appendChild($imgEl);
        }
    }
    
    
    private function AddCloud(\DOMElement $docParent, Cloud $rssCloud = null)
    {
 	if ($rssCloud != null)
        {
            $cloudEl = $this->domDoc->createElement("cloud");
            $this->SetAttribute($cloudEl, "domain", $rssCloud->Domain);
            $this->SetAttribute($cloudEl, "path", $rssCloud->Path);
            $this->SetAttribute($cloudEl, "port", $rssCloud->Port);
            $this->SetAttribute($cloudEl, "registerProcedure", $rssCloud->RegisterProcedure);
            $this->SetAttribute($cloudEl, "protocol", $rssCloud->Protocol);
            $docParent->appendChild($cloudEl);
        }
    }
    
    private function AddSource(\DOMElement $docParent, Source $rssSource = null)
    {
        if ($rssSource != null)
        {
            $contents = $rssSource->Contents;
            if (!$contents)
                $contents = $this->rssChannel->Title;
            
            $srcEl = $this->AppendTextChild($docParent, "source", $contents);
            $this->SetAttribute($srcEl, "url", $rssSource->Url);
        }
    }
    
    private function AddCategory(\DOMElement $docParent, Category $rssCategory = null)
    {
 	if ($rssCategory != null)
 	{
            $catEl = $this->AppendTextChild($docParent, "category", $rssCategory->Contents);
            if ($rssCategory->Domain != null)
                $this->SetAttribute($catEl, "domain", $rssCategory->Domain);
        }
    }
    
    private function CreateRssElement()
    {
        $rssEl = $this->domDoc->createElement("rss");
        $this->SetAttribute($rssEl, "version", "2.0");
 	$this->rssElement =  $rssEl;
    }

    private function SetAttribute(\DOMElement $element, $name, $value)
    {
        $attr = $this->domDoc->createAttribute($name);
        $attr->value = $value;
 	$element->appendChild($attr);
    }


    private function AppendCDATAChild($parent, $elementName, $contents)
    {
        $element = $this->domDoc->createElement($elementName);
        $cdata = $this->domDoc->createCDATASection ($contents);
        $element->appendChild($cdata);
        $parent->appendChild($element);
        return $element;
    }

    private function AppendTextChild($parent, $elementName, $contents)
    {
        $element = $this->domDoc->createElement($elementName);
        $tn = $this->domDoc->createTextNode ($contents);
        $element->appendChild($tn);
        $parent->appendChild($element);
        return $element;
    }
    
    private function DefaultValue($element, $parent, $fieldName)
    {
        return $element->$fieldName != null ? $element->$fieldName : $parent->$fieldName;
    }
 }