<?php
namespace Phine\Framework\Sitemap;

use Phine\Framework\System\Date;
use Phine\Framework\Sitemap\Enums\ChangeFrequency;
/**
 * Sitemap generator in xml format
 */
class XmlGenerator
{
    /**
     *
     * @var \DOMDocument
     */
    private $domDoc;
    
    /**
     *
     * @var \DOMElement
     */
    private $urlset;

    /**
     * Creates a new xml sitemag generator
     */
    function __construct()
    {
        $this->domDoc = new \DOMDocument("1.0", "utf-8");
        $this->domDoc->formatOutput = true;
        $this->CreateUrlset();
        $this->domDoc->appendChild($this->urlset);
    }

    /**
     * Creates the urlset element
     */
    private function CreateUrlset()
    {
        $this->urlset = $this->domDoc->createElement("urlset");
        $this->SetAttribute($this->urlset, "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
    }
    
    /**
     * 
     * @param \DOMElement $element
     * @param string $name
     * @param string $value
     */
    private function SetAttribute($element, $name, $value)
    {
        $attr = $this->domDoc->createAttribute($name);
        $attr->value = $value;
        $element->appendChild($attr);
    }
    
    /**
     * Creates a text element with the given contents and returns it
     * @param \DOMElement $parent
     * @param string $elementName
     * @param string $contents
     * @return \DOMElement
     */
    private function AppendTextChild($parent, $elementName, $contents)
    {
        $element = $this->domDoc->createElement($elementName);
        $tn = $this->domDoc->createTextNode ($contents);
        $element->appendChild($tn);
        $parent->appendChild($element);
        return $element;
    }

    /**
     * Adds an url to the sitemap
     * @param string $url
     * @param ChangeFrequency $changeFreq
     * @param float $priority
     * @param Date $lastMod
     */
    function AddUrl($url, ChangeFrequency $changeFreq = null, $priority= null, Date $lastMod = null)
    {
        $elm = $this->domDoc->createElement("url");
        $this->AppendTextChild($elm, "loc", $url);
        
        if ($changeFreq)
            $this->AppendTextChild($elm, "changefreq", (string)$changeFreq);
        
        if ($priority)
            $this->AppendTextChild($elm, "priority", $priority);
        
        if ($lastMod)
            $this->AppendTextChild($elm, "lastmod", $lastMod);
        
        $this->urlset->appendChild($elm);
    }
    
    /**
     * Gets the xml sitemap as string
     * @return string
     */
    function SaveXml()
    {
        return $this->domDoc->saveXML();
    }
    
    /**
     * Saves the sitemap as xml file
     * @return int The number of written bytes
     */
    function Save($filename)
    {
        return $this->domDoc->save($filename);
    }
}