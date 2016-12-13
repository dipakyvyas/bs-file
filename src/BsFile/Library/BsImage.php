<?php
namespace BsFile\Library;

use BsFile\Model\Mapper\ImageInterface;

/**
 *
 * @author Mat Wright <mat.wright@broadshout.com>
 *        
 */
class BsImage
{

    /**
     *
     * @param string|ImageInterface $source            
     * @param integer $x            
     * @param integer $y            
     * @param bool $proportions            
     * @return resource Reduire la taille d'une image
     */
    public static function resize($image, $x = null, $y = null, $proportions = true, $white = false, $transparent = false)
    {
        if ($image instanceof ImageInterface) {
            $tmpfname = tempnam('./tmp', 'IMAGE_REDUCE');
            file_put_contents($tmpfname, $image->getFile()->getBytes());
            $filename = $tmpfname;
        } elseif (is_resource($image)) {
            $tmpfname = tempnam('./tmp', 'IMAGE_REDUCE');
            file_put_contents($tmpfname, $image);
            $filename = $tmpfname;
        } elseif (is_string($image)) {
            $filename = $image;
        } else {
            throw new \Exception('Invalid argument $image provided');
        }
        
        $size = getimagesize($filename);
        $mime = $size['mime'];
        $width = $size[0];
        $height = $size[1];
        // if both x and y are given
        if (! is_null($x) && ! is_null($y)) {
            $new_x = $x;
            $new_y = $y;
            // if at least x is given and image should be proportioned
        } elseif (! is_null($x) && true === $proportions) {
            $q = $width / $x;
            $new_x = $x;
            $new_y = $height / $q;
            // if $y is given and proportional
        } elseif (! is_null($y) && true === $proportions) {
            $q = $height / $y;
            $new_x = $width / $q;
            $new_y = $y;
        } else {
            $new_x = $x;
            $new_y = $y;
        }
        $imageType = null;
        switch ($mime) {
            case ('image/png'):
                $imagecreate = 'imagecreatefrompng';
                $imageprint = 'imagepng';
                break;
            case ('image/gif'):
                $imagecreate = 'imagecreatefromgif';
                $imageprint = 'imagegif';
                break;
            default:
                $imagecreate = 'imagecreatefromjpeg';
                $imageprint = 'imagejpeg';
                break;
        }
        $newImage = imagecreatetruecolor($new_x, $new_y);
        
        
        
        
        
        $source = $imagecreate($filename);
        
        
        if ('imagecreatefrompng' === $imagecreate && $white === true) {
    
            $source = self::addPNGBackground($source, 255, 255, 255);
        } elseif ('imagecreatefrompng' === $imagecreate && $transparent === true) {

            // Transparent Background
            imagealphablending($newImage, false);
            $transparency = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transparency);
            imagesavealpha($newImage, true);
            
            $transparentSource = self::removePNGBackground($source);
            if(is_resource($transparentSource)){
                $source=$transparentSource;
            }
        }
        
        // Copy and resize image
        if (! imagecopyresampled($newImage, $source, 0, 0, 0, 0, $new_x, $new_y, $width, $height)) {
            throw new \Exception('Could not copy/resize image width:' . $width . ' height' . $height . ' to ' . $new_x . ' height' . $new_y);
        }
        
        /*
         * TODO http://www.php.net/manual/en/function.imagescale.php implementer version PHP 5.5
         */
        
        // capture image from output
        ob_start();
        $imageprint($newImage);
        $image = ob_get_contents();
        ob_end_clean();
        return $image;
    }

    /**
     * Removes a PNG background and replaces with a transparant background.
     *
     * @param resource $source            
     * @return resource
     * @throws \Exception
     */
    public static function removePNGBackground($source)
    {
        if (! is_resource($source)) {
            throw new \Exception('$source must of of type resource, ' . gettype($source) . ' given.');
        }
        $image = imagecolortransparent($source);
        return $image;
    }

    /**
     * Add a solid background using RGB colour
     *
     * @param resource $source            
     * @param int $red            
     * @param int $green            
     * @param int $blue            
     * @throws \Exception
     * @return resource
     */
    public static function addPNGBackground($source, $red, $green, $blue)
    {
        if (! is_resource($source)) {
            throw new \Exception('$source must of of type resource, ' . gettype($source) . ' given.');
        }
        
        // Create a new true color image with the same size
        $w = imagesx($source);
        $h = imagesy($source);
        $newImage = imagecreatetruecolor($w, $h);
        // Fill the new image with white background
        $bg = imagecolorallocate($source, $red, $green, $blue);
        imagefill($newImage, 0, 0, $bg);
        
        // Copy original transparent image onto the new image
        imagecopy($newImage, $source, 0, 0, 0, 0, $w, $h);
        return $newImage;
    }
}