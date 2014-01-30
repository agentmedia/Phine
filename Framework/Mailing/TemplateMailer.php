<?php
namespace Phine\Framework\Mailing;
use Phine\Framework\System\IO;
use Phine\Framework\System\String;

/**
 * Provides methods to send html mails using a template with placeholders
 */
class TemplateMailer
{
    
    /**
     * The template's original contents
     * @var string 
     */
    private $templateContents;
    
    /**
     * The start marker for template placeholders
     * @var string 
     */
    private $placeholderStart;
    
    /**
     * The end marker for template placeholders
     * @var string
     */
    private $placeholderEnd;
    
    /**
     * Creates a new template mailer
     * @param string $templateContents The contents of the template
     * @param string $placeholderStart The start marker of template placeholder 
     * @param string $placeholderEnd The end marker of template placeholder
     * @see See also static constructor FromFile() to create instance from template file
     */
    function __construct($templateContents, $placeholderStart = '{{', $placeholderEnd = '}}')
    {
        $this->templateContents = $templateContents;//IO\File::GetContents($templateFile);
        $this->placeholderStart = $placeholderStart;
        $this->placeholderEnd = $placeholderEnd;
    }
    
    /**
     * Creates a new template mailer from a template file
     * @param string $templateFile The path to the template file
     * @param string $placeholderStart The start marker of template placeholder 
     * @param string $placeholderEnd The end marker of template placeholder
     * @return TemplateMailer
     */
    static function FromFile($templateFile, $placeholderStart = '{{', $placeholderEnd = '}}')
    {
        return new self(IO\File::GetContents($templateFile), $placeholderStart, $placeholderEnd);
    }
    
    /**
     * Sends the mail
     * @param string $from The "From" header
     * @param string $to The recipient e-mail
     * @param string $subject The mail subject
     * @param array $placeholders The placeholder replacements
     * @param string $replyTo The "Reply-To" header
     * @param array $headers More mail headers
     * @param string $additionalParameters Additional parameters for mail program
     * @return bool
     */
    function Mail($from, $to, $subject, array $placeholders, $replyTo = '', array $headers = array(), $additionalParameters = '')
    {
        $mailer = new Mailer();
        
        $mailer->SetFrom($from);
        if ($replyTo)
            $mailer->SetReplyTo ($replyTo);
        
        if (count($headers))
            $mailer->SetHeaders($headers);
        
        $mailer->SetSubject($subject);
        $mailer->SetHtml($this->ParseTemplate($placeholders));
        return $mailer->Mail($to, $additionalParameters);
    }
    
    /**
     * Realizes the placeholders
     * @param array $placeholders
     * @return strung
     */
    function ParseTemplate(array $placeholders)
    {
        $contents = $this->templateContents;
        foreach ($placeholders as $label=>$replacement)
        {
            $search = $this->placeholderStart . $label . $this->placeholderEnd;
            $contents = String::Replace($search, $replacement, $contents); 
        }
        return $contents;
    }
}