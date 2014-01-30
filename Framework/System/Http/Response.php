<?php
namespace Phine\Framework\System\Http;

class Response
{
    const REDIRECT_HEADER_301 = "HTTP/1.1 301 Moved Permanently";
    
    /**
     * Redirects to a target whose path is relatively to the currently running php script
     * @param type $target
     * 
     */
    static function RedirectRelative($target)
    {
        session_write_close();
        $dirName =  dirname($_SERVER["PHP_SELF"]);
        if ($dirName == "/")
            $dirName = "";
        header("Location: " .  "http://" . $_SERVER["HTTP_HOST"]
            . $dirName
            . "/" .  $target);
    }

    /**
     * Sends the header string if given and redirects to the target
     * @param string $target
     * @param type $redirectHeader 
     */
    static function Redirect($target, $redirectHeader = "")
    {
        session_write_close();

        if ($redirectHeader)
            header($redirectHeader);

        header("Location: ". $target);
    }
    
    /**
     * Sends a 301 (Moved permanently) header and redirects to the target
     * @param string $target The valid url of the target
     */
    static function Redirect301($target)
    {
        self::Redirect($target, self::REDIRECT_HEADER_301);
    }
}