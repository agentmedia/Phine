<?php

namespace Phine\Framework\Wording\Interfaces;

/**
 * Provides an interface for wording realizers
 *
 * @author klaus
 */
interface IRealizer 
{
    /**
     * Realizes the placeholder with the given args 
     */
    function RealizeArgs($placeholder, array $args = array());
}

?>