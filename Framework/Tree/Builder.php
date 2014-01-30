<?php

namespace Phine\Framework\Tree;
use Phine\Framework\Tree\Interfaces as TreeInterfaces;

/**
 * Contains methods for building tree objecs.
 * @author Klaus Potzesny
 *
 */
class Builder
{
    static function GetLastChild(TreeInterfaces\ITreeItem $parent)
    {
        $lastChild = null;
        $child = $parent->GetFirstChild();
        while ($child)
        {
            $lastChild = $child;
            $child = $child->GetNext();
        }
        return $lastChild;
    }
    static function Insert(TreeInterfaces\ITreeItem $item, TreeInterfaces\ITreeItem $parent = null, TreeInterfaces\ITreeItem $previous = null)
    {
        //Assure child gets correct parent
        if ($previous && !$parent)
            $parent = $item->GetParent();

        if ($parent)
        {
            //Assure correct parent
            if ($previous && !self::Equal($parent, $previous->GetParent()))
            {
                throw new \LogicException('Error in tree insertion. Previous sibling has different parent.');
            }
            $item->SetParent($parent);
        }
        $item->Save();
                $next = null;
        if ($previous)
        {
            $next = $previous->GetNext();
            $previous->SetNext($item);
            $previous->Save();    
        }
        else
        {
            if ($parent)
            {
                $next = $parent->GetFirstChild();
                $parent->SetFirstChild($item);
                $parent->Save();
            }    
        }
            
        if ($next)
        {
            $item->SetNext($next);
            $item->Save();
        }
        return $item;
    }
    
    private static function Equal(TreeInterfaces\ITreeItem $item1 = null, TreeInterfaces\ITreeItem $item2 = null)
    {
        if ($item1 !== null)
            return $item1->Equals($item2);

        if ($item2 !== null)
            return $item2->Equals($item1);
        
        return $item1 === $item2;
    }
}