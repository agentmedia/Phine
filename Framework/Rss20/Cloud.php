<?php
namespace Phine\Framework\Rss20;

/**
 * 
 * Represents an rss 2.0 cloud element
 */
class Cloud
{
    /**
     *
     * @var string The cloud's domain
     */
    public $Domain;
    /**
     *
     * @var string The cloud's path
     */
    public $Path;
    /**
     *
     * @var string The cloud's port
     */
    public $Port;
    /**
     *
     * @var string The cloud's register procedure
     */
    public $RegisterProcedure;
    /**
     *
     * @var string The cloud's protocol
     */
    public $Protocol;

    /**
     * 
     * @param string $domain The cloud's domain
     * @param string $path The cloud's path
     * @param string $port The cloud's port
     * @param string $registerProcedure The cloud's register procedure
     * @param string $protocol The cloud's protocol
     */
    function __construct($domain, $path, $port, $registerProcedure, $protocol)
    {
        $this->Domain = $domain;
 	$this->Path = $path;
 	$this->Port = $port;
 	$this->RegisterProcedure = $registerProcedure;
        $this->Protocol = $protocol;
    }   
}