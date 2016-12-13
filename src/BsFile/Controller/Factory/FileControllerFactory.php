<?php
namespace BsFile\Controller\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use BsFile\Controller\FileController;
use BsFile\Service\BsFile;

class FileControllerFactory  implements FactoryInterface{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        
        return new FileController($serviceLocator->get('BsFile\Service\File'));
    }
}

?>