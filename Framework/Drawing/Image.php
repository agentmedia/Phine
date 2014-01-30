<?php
namespace Phine\Framework\System\Drawing;

use Phine\Framework\System\IO\File;
use Phine\Framework\System\IO\Enums\MimeType;


class Image
{
    /**
     * GD image resource identifier
     * @var mixed 
     */
    private $resource = false;
    
    /**
     * GD color resource identifier
     * @var mixed
     */
    private $colorResource = false;
    
    /**
     *
     * @var ArgbColor
     */
    private $color = null;
    
    /**
     * Width in pixel
     * @var int
     */
    private $width = 0;
    
    /**
     * Height in pixel
     * @var int
     */
    private $height = 0;
    
    /**
     * Creates an instance by image resource
     * @param mixed $imageResource
     */
    private function __construct($imageResource)
    {
        $this->resource = $imageResource;
    }
    
    /**
     * 
     * @param mixed $imageResource
     */
    private function AssignResource($imageResource)
    {
        $color = null;
        if ($this->resource !== false)
        {   
            $color = $this->color;
            $this->ClearColor();
            \imagedestroy ($this->resource);
        }
        $this->resource = $imageResource;
        $this->width = 0;
        $this->height = 0;
        
        $this->AssignColor($color);
    }
    private function ClearColor()
    {
        if ($this->colorResource !== false)
        {
             \imagecolordeallocate ($this->resource, $this->colorResource);
             $this->colorResource = false;
        }
        $this->color = null;
    }
    
    private function AssignColor(ArgbColor $color = null)
    {
        $this->ClearColor();
        $this->color = $color;
        if ($color)
        {
            $this->colorResource = \imagecolorallocatealpha($this->resource, $color->GetRed(), 
                    $color->GetGreen(), $color->GetBlue(), $color->GetAlpha());
        }
    }
    /**
     * Destroys internal image resource
     */
    function __destruct()
    {
        if ($this->resource !== false);
        {
            $this->ClearColor();   
            \imagedestroy($this->resource);
            
            $this->resource = false;
        }
    }
    
    static function FromString($data)
    {
        return new self(\imagecreatefromstring($data));
    }

    /**
     * 
     * @param string $filename
     * @return Image Returns null on failure (file no image)
     */
    static function FromFile($filename)
    {
        $result = null;
        switch(File::GetMimeType($filename))
        {
            case MimeType::Jpeg():
                $result = new self(@\imagecreatefromjpeg($filename));
                break;
            
            case MimeType::Png():
                $result = new self(@\imagecreatefrompng($filename));
                break;
            
            case MimeType::Gif():
                $result = new self($this->resource = @\imagecreatefromgif($filename));
                break;
        }
        return $result;
    }
    
    /**
     * Image width in pixels
     * @return int
     */
    public function Width()
    {
        if (!($this->width > 0))
            $this->width = \imagesx($this->resource);
        
        return $this->width;
    }
    
    /**
     * Image height in pixels
     * @return int
     */
    public function Height()
    {
        if (!($this->height > 0))
            $this->height = \imagesy($this->resource);
        
        return $this->height;
    }
    /*
    function CenterBlowUp($newWidth, $newHeight, ArgbColor $backgroundColor)
    {
        
    }
     */
    /**
     * Crops the image to the biggest fitting, centered square 
     * 
     */
    function CenterSquarify(ArgbColor $backgroundColor = null)
    {
        $width = $this->Width();
        $height = $this->Height();
        $squareSize = min($width, $height);
        $xOrigin = 0;
        $yOrigin = 0;
        if ($width > $squareSize)
            $xOrigin = round(($width - $squareSize) / 2) ;
        
        else if ($height > $squareSize)
            $yOrigin = round(($height - $squareSize) / 2);
        
        $destRes = \imagecreatetruecolor($squareSize, $squareSize);
        
        if ($backgroundColor)
        {
            $colRes = \imagecolorallocate($destRes, $backgroundColor->GetRed(), 
                    $backgroundColor->GetGreen(), $backgroundColor->GetBlue());    
            
            \imagefill($destRes, 0, 0, $colRes);
            \imagecolordeallocate($destRes, $colRes);
        }
        \imagecopy($destRes, $this->resource, 0, 0, $xOrigin, $yOrigin, $squareSize, $squareSize);
            
        $this->AssignResource($destRes);
    }
    
    function ToPng($filename = null, $quality = null, $filters = null)
    {
        if ($filters !== null)
            return \imagepng($this->resource, $filename, $quality, $filters);
        else if ($quality !== null)
            return \imagepng($this->resource, $filename, $quality);
        else
            return \imagepng($this->resource, $filename);
    }
    
    function ToJpeg($filename = null, $quality = null)
    {
        if ($quality !== null)
            return \imagejpeg($this->resource, $filename, $quality);
        else
            return \imagejpeg($this->resource, $filename);
    }
    
    
    function ToGif($filename = null)
    {
        return \imagegif($this->resource, $filename);
    }
    /**
     * Scales down with fixed relation to the max size in height and width; If image is smaller, nothing is done. 
     * @param int $maxSize The max width and height the image shall be given 
     */
    function ScaleDown($maxSize)
    {
        
        $height = $this->Height();
        $width = $this->Width();
        
        if ($width < $maxSize && $height < $maxSize)
            return;
        
        $xRatio = $maxSize / $height ;
        $yRatio = $maxSize / $width;
        
        $this->ScaleProportional(min($xRatio, $yRatio));
    }
    
    /**
     * Scales to new size
     * @param int $newWidth
     * @param int $newHeight
     */
    function Scale($newWidth, $newHeight)
    {
        
        $dstImg = \imagecreatetruecolor($newWidth, $newHeight);
        
        \imagecopyresampled($dstImg, $this->resource, 0, 0, 
                0, 0, $newWidth, $newHeight, $this->Width(), $this->Height());
        
        $this->AssignResource($dstImg);
    }
    /**
     * Scales proportionally (fixed width/height ratio)
     * @param double $ratio
     */
    function ScaleProportional($ratio)
    {
        $newWidth = round($this->Width() * $ratio);
        $newHeight = round($this->Height() * $ratio);
       
        $this->Scale($newWidth, $newHeight);   
    }
    
}