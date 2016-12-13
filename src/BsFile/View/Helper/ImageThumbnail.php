<?php
namespace BsFile\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Cache\StorageFactory;
use BsFile\Library\BsImage;
use BsFile\Model\Mapper\ImageInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class ImageThumbnail extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     *
     * @param ImageInterface $image            
     * @param number $width            
     * @return Ambigous <string, \Zend\Cache\Storage\mixed>
     */
    public function __invoke(ImageInterface $image, $width = 150, $isHeight = false)
    {
        $cacheDir = './data/cache';
        
        $cache = StorageFactory::adapterFactory('filesystem', array(
            'ttl' => (3600 * 24 * 365),
            'cache_dir' => $cacheDir
        ));
        
        $plugin = StorageFactory::pluginFactory('exception_handler', array(
            'throw_exceptions' => true
        ));
        
        $cache->addPlugin($plugin);
        $plugin = StorageFactory::pluginFactory('serializer', array());
        
        $cache->addPlugin($plugin);
        $this->cache = $cache;
        $success = false;
        $string = $this->cache->getItem('thumbs_' . $image->getMd5() . '_size_' . $width . '_' . ($isHeight ? 'isheight' : 'noisheight'), $success);
        
        if (! $success) {
            $bsimage = BsImage::resize($image, ($isHeight ? null : $width), ($isHeight ? $width : null), true, false, true);
            $base64 = base64_encode($bsimage);
            $string = 'data:' . $image->getMime() . ';base64,' . $base64;
            $this->cache->addItem('thumbs_' . $image->getMd5() . '_size_' . $width . '_' . ($isHeight ? 'isheight' : 'noisheight'), $string);
        }
        
        return $string;
    }
}