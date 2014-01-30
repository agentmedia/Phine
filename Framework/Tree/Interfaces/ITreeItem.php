<?php
namespace Phine\Framework\Tree\Interfaces;

interface ITreeItem
{
    /**
     * Gets parent item.
     * @return ITreeItem
     */
    function GetParent();
    /**
     * Sets parent item.
     * @param ITreeItem $item
     */
    function SetParent(ITreeItem $item = null);
        
    /**
     * Gets next sibling item.
     * @return ITreeItem
     */
    function GetNext();
    
    /**
     * Sets next sibling item.
     * @param ITreeItem $item
     */
    function SetNext(ITreeItem $item = null);
    
    /**
     * Gets first child of item.
     * @return ITreeItem
     */
    function GetFirstChild();
    
    /**
     * Sets first child of item
     * @param ITreeItem $item
     * @return unknown_type
     */
    function SetFirstChild(ITreeItem $item = null);
    
    
    /**
     * Returns True if item equals this.
     * @param ITreeItem $item
     * @return bool
     */
    function Equals(ITreeItem $item = null);
    
    /**
     * Save this item.
     */
    function Save();
    
}