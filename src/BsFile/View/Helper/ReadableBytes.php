<?php
namespace BsFile\View\Helper;

use Zend\View\Helper\AbstractHelper;
use BsFile\Library\File;

class ReadableBytes extends AbstractHelper
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