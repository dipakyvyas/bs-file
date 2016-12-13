<?php
namespace BsFile\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class FileUrl extends AbstractHelper
{

    /**
     * return HTML code of an file 
     *
     * @param string|FileInterface $file            
     * @param string $disposition            
     * @param string $width            
     */
    public function __invoke($file, $disposition = 'attachment', $width = null)
    {
        
       return  $this->getView()->url('bs-file-manager/output', array(
            'id' => $file->getId()
        ), array(
            'query' => array(
                'disposition' => $disposition,
                'width' => $width
            )
        ));
    }
}