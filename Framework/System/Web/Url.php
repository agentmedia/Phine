<?php

namespace Phine\Framework\System\Web;

/**
 * Represents an url
 *
 * @author klaus
 * 
 */
class Url
{
    /**
     * The scheme (e.g. http) 
     * @var string
     */
    private $scheme;
    
    /**
     * The host (e.g. www.mydomain.de) 
     * @var string
     */
    private $host;
    
    /**
     * The port (e.g. 80) 
     * @var string
     */
    private $port;
    
    /**
     * An optional user
     * @var string
     */
    private $user;
    /**
     * An optional password
     * @var string
     */
    private $password;
    /**
     * The host side requested path
     * @var string
     */
    private $path;
    
    /**
     * The query parameter (all after ?)
     * @var string
     */
    private $query;
    
    /**
     * The query parameter (after #)
     * @var string
     */
    private $fragment;
    
    /**
     * Creates a new url by its components
     * @param string $scheme
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     * @param string $path
     * @param string $query
     * @param string $fragment 
     */
    function __construct($scheme, $host, $port = 0, $user='', $password='', $path='', $query='', $fragment='')
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->path = $path;
        $this->query = $query;
        $this->fragment= $fragment;
    }
    
    /**
     * Creates an url from string
     * @param string $url
     * @return Url 
     */
    public static function FromString($url)
    {
        $parts = \parse_url($url);
        $scheme = isset($parts['scheme']) ? $parts['scheme'] : '';
        $host = isset($parts['host']) ? $parts['host'] : '';
        $port = isset($parts['port']) ? $parts['port'] : '';
        $user = isset($parts['user']) ? $parts['user'] : '';
        $password = isset($parts['pass']) ? $parts['pass'] : '';
        $path = isset($parts['path']) ? $parts['path'] : '';
        $query = isset($parts['query']) ? $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? $parts['fragment'] : '';
        return new self($scheme, $host, $port, $user, $password, $path, $query, $fragment);
    }
    
    /**
     * The url as string
     * @return string 
     */
    function __toString()
    {
        //http://benutzername:passwort@hostname:81/pfad?argument=wert#textanker'
        $result = $this->scheme . '://';
        if ($this->user)
        {
            $result .= $this->user;
            if ($this->password)
                $result .= ':' . $this->password;
            
            $result .= '@';
        }
        $result .= $this->host;
        if ($this->port)
            $result .= ':' . $this->port;
        
        if ($this->path)
            $result .= $this->path;
        
        if ($this->query)
            $result .= '?' . $this->query;
        
        if ($this->fragment)
            $result .= '#' . $this->fragment;
        
        return $result;
    }
    
    /**
     * Gets the scheme (http for example)
     * @return string
     */
    public function GetScheme()
    {
        return $this->scheme;
    }
    
    /**
     * Sets the scheme (http for example)
     * @var string $scheme
     */
    public function SetScheme($scheme)
    {
        $this->scheme = $scheme;
    }
    
    /**
     * Gets the host (www.domain.com for example)
     * @return string
     */
    public function GetHost()
    {
        return $this->host;
    }

    /**
     * Sets the host (www.domain.com for example)
     * @var string $host
     */
    public function SetHost($host)
    {
        $this->host = $host;
    }
    /**
     * Gets the port (appended after host separated by ':')
     * @return int
     */
    public function GetPort()
    {
        return $this->port;
    }

    /**
     * Sets the port (appended after host separated by ':')
     * @var int $port
     */
    public function SetPort($port)
    {
        $this->port = $port;
    }
    /**
     * Gets an optional user name
     * @return string
     */
    public function GetUser()
    {
        return $this->user;
    }

    /**
     * Sets an optional user
     * @var string $user
     */
    public function SetUser($user)
    {
        $this->user = $user;
    }
    
    /**
     * Gets an optional password
     * @return string
     */
    public function GetPassword()
    {
        return $this->password;
    }

    /**
     * Sets an optional password
     * @var string $password
     */
    public function SetPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * Gets the path
     * @return string
     */
    public function GetPath()
    {
        return $this->path;
    }

    /**
     * Sets the path; It should either be empty or begin with '/'
     * @var string $path
     */
    public function SetPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * Gets the query (after '?')
     * @return string
     */
    public function GetQuery()
    {
        return $this->query;
    }

    /**
     * Sets the query (after '?')
     * @var string $query
     */
    public function SetQuery($query)
    {
        $this->query = $query;
    }
    
    /**
     * Gets the fragment (after '#')
     * @return string
     */
    public function GetFragment()
    {
        return $this->fragment;
    }

    /**
     * Sets the fragment (after '#')
     * @var string $fragment
     */
    public function SetFragment($fragment)
    {
        $this->fragment = $fragment;
    }
    /**
     * More visible alternative to the also implemented __toString() magic function
     * @return string
     */
    public function ToString()
    {
        return (string)$this;
    }
}

