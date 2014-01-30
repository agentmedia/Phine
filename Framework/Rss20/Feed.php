<?php

namespace Phine\Framework\Rss20;
/**
 * Represents an rss 2.0 feed
 */
class Feed
{
    /**
     *
     * @var array[$channel]
     */
    private $channels = array();
    
    /**
     * Adds a channel to the feed
     * @param Channel $channel
     */
    function AddChannel(Channel $channel)
    {
        $this->channels[] = $channel;
    }
    
    /**
     * Removes the channel at the specified index position
     * @param int $idx The channel position in the feed
     */
    function RemoveChannel($idx)
    {
        $result = array();
        foreach ($this->channels as $currentIdx => $channel)
        {
            if ($currentIdx != $idx)
                $result[] = $channel;
        }
        $this->channels = $result;
    }
    
    /**
     * 
     * Gets all channels of the feed
     * @return array[Channel]
     */
    function GetChannels()
    {
        return $this->channels;
    }
}
