<?php
namespace BsFile\Controller\Plugin;

use BsFile\Library\File;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ReadableBytes extends AbstractPlugin
{

    /**
     * @param number $bytes
     * @param number $precision
     * @return string
     */
    public function __invoke($bytes, $precision = 2)
    {
        return File::humanReadable($bytes,$precision);
    }
}

?>