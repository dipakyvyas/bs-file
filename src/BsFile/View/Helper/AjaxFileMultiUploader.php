<?php
namespace BsFile\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AjaxFileMultiUploader extends AbstractHelper
{

    public function __invoke($fileToken)
    {
        return $this->getView()->render('bsfile/add-file', array(
            'fileToken' => $fileToken
        ));
    }
}