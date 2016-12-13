<?php
namespace BsFile\Model\Mapper;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
interface MapperInterface
{
    /**
     * @param string $entityName
     * @return FileInterface
     */
    public function getEntity($entityName);
    /**
     * @return ObjectManager
     */
    public function getInstance();
    
    /**
     * @return ObjectRepository
     */
    public function getRepository($entity);
}