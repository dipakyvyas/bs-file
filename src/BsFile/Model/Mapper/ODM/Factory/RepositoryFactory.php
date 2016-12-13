<?php
namespace BsFile\Model\Mapper\ODM\Factory;

use Zend\ServiceManager\FactoryInterface;
use BsFile\Model\Mapper\FileRepositoryInterface;

/**
 * Repository Factory using 'BsFile\Model\Mapper\ODM\Repository\FileRepository' repository by default
 */
class RepositoryFactory implements FactoryInterface
{
    
    /*
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $dm = $serviceLocator->get('doctrine.documentmanager.odm_default');
        $repository = $dm->getRepository('BsFile\Model\Mapper\ODM\Documents\File');
        if (! $repository instanceof FileRepositoryInterface) {
            throw new \Exception('Configured repository must be instance of FileRepositoryInterface');
        }
        return $repository;
    }
}