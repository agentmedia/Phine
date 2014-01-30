<?php

namespace Phine\Framework\Mailing;

use Phine\Framework\System\String;
/**
 * Provides methods for sending e-mails with text, html and attachments
 *
 * @author klaus
 */

class Mailer
{
    
    /**
     * The array of headers
     * @var array 
     */
    private $headersArray = array();
    
    /**
     *
     * @var string
     */
    private $subject;
    
    /**
     * Creates a header
     * @param string $from The from email adress
     */
    function __construct()
    {
    }
    
    /**
     * Sets the email subject
     * @param string $subject 
     */
    function SetSubject($subject)
    {
        $this->subject = $subject;
    }
    
    /**
     * Gets the subject
     * @return string
     */
    function GetSubject()
    {
        return $this->subject;
    }
    
    /**
     * Gets a string to use as subject with correct utf8 encoding
     * @return string
     */
    public function GetUtf8Subject()
    {
        return '=?UTF-8?B?' . base64_encode($this->GetSubject()) . '?=';
    }
    
    /**
     * Sets the obligatory "From" header
     * @param string $from The from e-mail
     */
    public function SetFrom($from)
    {
        $this->SetHeader('From', $from);
    }
    
    /**
     * Gets the from header
     * @return string
     */
    public function GetFrom()
    {
        return $this->GetHeader('From');
    }
    
    /**
     * Sets the "Reply-To" header
     * @param string $replyTo 
     */
    public function SetReplyTo($replyTo)
    {
        $this->SetHeader('Reply-To', $replyTo);
    }
    /**
     * Sets a header 
     * @param string $name The name of the header
     * @param string $value The value; can be null to clear header
     */
    public function SetHeader($name, $value)
    {
        if ($value !== null)
            $this->headersArray[$name] = $value;
        
        else if (isset($this->headersArray[$name]))
            unset($this->headersArray[$name]);
    }
    /**
     * Gets a header by its name
     * @param string $name
     * @return string The header; will return null if header is not set
     */
    public function GetHeader($name)
    {
        if (isset($this->headersArray[$name]))
            return $this->headersArray[$name];
        
        return null;
    }
    
    /**
     * Returns all mail heafers as an array
     * @return array
     */
    public function GetHeaders()
    {
        return $this->headersArray;
    }
    /**
     * Sets mail headers
     * @param array $headers 
     */
    public function SetHeaders(array $headers)
    {
        $this->headersArray = $headers;
    }
    
    /**
     * Gets the "Reply-To" header
     * @return string 
     */
    public function GetReplyTo()
    {
        return $this->GetHeader('Reply-To');
    }
    
    /**
     * The message as plain text
     * @var string
     */
    private $plainText = null;
    
    
    /**
     * The message as html
     * @var string
     */
    private $html = null;
    
    /**
     * An array of attachment contents
     * @var array
     */
    private $attachments = array();

    /**
     * Adds attachment with given name and content of given file
     * @param string $displayName
     * @param string $filename 
     */
    public function AddAttachmentFile($displayName, $filename)
    {
        $this->AddAttachmentContent($displayName, file_get_contents($filename));
    }
    /**
     * Adds attachment with the given name and content
     * @param string $displayName The name of the file as displayed for recipient
     * @param string $content The attachment content
     * @throws InvalidArgumentException Raises error if the display is already in use
     */
    public function AddAttachmentContent($displayName, $content)
    {
        if (array_key_exists($displayName, $this->attachments))
            throw new \InvalidArgumentException('attachment with given name already exists');
        
        $this->attachments[$displayName] = chunk_split(base64_encode($content));
    }
    /**
     *
     * @param string $displayName The name of the file as displayed for recipient
     * @return boolean Returns true if the attachment was present and could be removed
     */
    public function RemoveAttachment($displayName)
    {
        if (array_key_exists($displayName, $this->attachments))
        {
            unset($this->attachments[$displayName]);
            return true;
        }
        return false;
    }
    /**
     *
     * Removes all attachments
     */
    public function ClearAttachments()
    {
        $this->attachments = array();
    }
    
    
   
    
    /**
     * Sends the mail via php built-in mail function
     * @param string $to
     * @param string $additionalParameters
     * @return bool 
     */
    public function Mail($to, $additionalParameters = '')
    {
        if ($additionalParameters)
            return (bool)@\mail($to, $this->GetUtf8Subject(), '', $this->GetFullHeaders(), $additionalParameters);
        else 
            return (bool)@\mail($to, $this->GetUtf8Subject(), '', $this->GetFullHeaders());
    }
    
    /**
     * Returns all mail headers as a string
     * @return string
     */
    function GetHeadersString()
    {
        $headerString = '';
        foreach ($this->headersArray as $name=>$value)
        {
            $headerString .= "$name: " . $value. "\r\n";
        }
        return $headerString;
    }
    
    /**
     * Gets the full message including format alternatives and attachments
     * @return string 
     */
    function GetFullHeaders()
    {
        $message = 'MIME-Version: 1.0' . "\r\n";
        $message .= $this->GetHeadersString();
        $uid = md5(uniqid(microtime()));
        $uidAlt = $uid . 'alt';
        //$message .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        
        $message .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $message .= "This is a multi-part message in MIME format.\r\n";
        $message .= "--" . $uid . "\r\n";
        $message .= "Content-Type: multipart/alternative; boundary=\"".$uidAlt."\"\r\n";
        
          $message .= "\r\n\r\n";
        $message .= $this->GetPlainTextPart($uidAlt);
        $message .= $this->GetHtmlPart($uidAlt);
        $message .= "--" .$uidAlt. "--\r\n\r\n";
        
        foreach ($this->attachments as $displayName=>$content)
        {
           
            $message .= "--" . $uid . "\r\n";
            $message .= "Content-Type: application/octet-stream; name=\"".$displayName."\"\r\n"; // use diff. tyoes here
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"".$displayName."\"\r\n\r\n";
            $message .= $content."\r\n\r\n";
        }
        $message .= "--".$uid."--\r\n";
        return $message; 
    }
    
    private function GetPlainTextPart($boundary)
    {
        $plainText = $this->CalcPlainText();
        if ($plainText)
        {
            $message = "--" . $boundary . "\r\n";
            $message .= "Content-type:text/plain; charset=utf-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $message .= $plainText . "\r\n\r\n";
            return $message;
        }
        return '';
    }
    
    /**
     *
     * @param string $html 
     */
    function SetHtml($html)
    {
        $this->html = $html;
    }
    /**
     * Gets the html portion of the message
     * @param string $boundary The mail boundary
     * @return string 
     */
    private function GetHtmlPart($boundary)
    {
        if ($this->html)
        {
            
            $message = "--" . $boundary . "\r\n";
            $message .= "Content-type:text/html; charset=utf-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $message .= $this->html . "\r\n\r\n";
            return $message;
        }
        return '';
    }
    
    /**
     * Allows you to set a plain text; if null, html will be stripped.
     * @param string $text 
     */
    public function SetPlainText($text = null)
    {
        $this->plainText = $text;
    }
    
    /**
     * Gets the plain text as set for this mailer
     * @return string
     * @see See also CalcPlainText to retrieve plain text that is used when mailing
     */
    public function GetPlainText()
    {
        return $this->plainText;
    }
    
    /**
     * Gets (or calculates by given html) the plain text for usage in message
     * @return string
     */
    private function CalcPlainText()
    {
        if ($this->plainText !== null)
            return $this->plainText;
        
        else if ($this->html)
        {
            $plainText = $this->html;
            $tagbreaks = array('h1'=>'5', 'h2'=>'4', 'h3'=>'3', 'h4'=>'3', 'h5'=>'3', 'h6'=>'3', 'p'=>'2');
            
            foreach ($tagbreaks as $tag=>$breakCount)
            {
                $breaks = join("", array_fill(0, $breakCount - 1, "\r\n"));
                $plainText = String::Replace("</$tag>", $breaks . "</$tag>", $plainText);
            }
            $bodyStart = mb_stripos($plainText, '<body');
            $bodyEnd = mb_stripos($plainText, '</body>');
            
            $plainText = mb_substr($plainText, $bodyStart, $bodyEnd + mb_strlen('</body>') - $bodyStart);
            $this->plainText = strip_tags($plainText);
            $this->plainText = htmlspecialchars_decode($this->plainText);
        }
        return $this->plainText;
    }
    
    
}

