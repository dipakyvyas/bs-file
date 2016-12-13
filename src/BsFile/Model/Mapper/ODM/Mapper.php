<?php
namespace BsFile\Model\Mapper\ODM;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use BsFile\Model\Mapper\MapperInterface;
use BsFile\Model\Mapper\FileInterface;

class Mapper implements ServiceLocatorAwareInterface, MapperInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    /*
     * (non-PHPdoc)
     * @see \BsFile\Model\Mapper\MapperInterface::getInstance()
     */
    public function getInstance()
    {
        return $this->getServiceLocator()->get('odm');
    }
    
    /*
     * (non-PHPdoc)
     * @see \BsFile\Model\Mapper\MapperInterface::getRepository()
     */
    public function getRepository($entity)
    {
        if (is_object($entity)) {
            $entity = get_class($entity);
        }
        
        return $this->getInstance()->getRepository(__NAMESPACE__ . '\\Documents\\' . ucfirst($entity));
    }

    public function getEntity($entityName)
    {
        $entityName = (string) $entityName;
        $class = __NAMESPACE__ . '\\Documents\\' . ucfirst($entityName);
        
        if (class_exists($class)) {
            $entity = new $class();
        } else {
            throw new \Exception('class ' . $class . ' does not exist');
        }
        
        if ($entity instanceof FileInterface) {
            return $entity;
        }
        
        throw new \Exception('class ' . $entityName . ' must implement FileInterface object of type : ' . get_class($entity) . ' given.');
    }
    
    public function __invoke()
    {
        return $this->getInstance();
    }
}