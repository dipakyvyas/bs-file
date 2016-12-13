<?php
namespace BsFile\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

class FileService implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    /**
     * Recherche les fichiers d'un compagnie ou d'un projet dans la colonne params
     * 
     * @param array $params
     * @param string $paginate
     * @param string $limit
     * @return \Zend\Paginator\Paginator|Cursor
     */
    public function getFiles(array $params, $paginate = false)
    {
        $mapper = $this->getServiceLocator()->get('bsfile_mapper');
        $repo = $mapper->getRepository('File');
        
        $files = $repo->findBy($params);
        if ($paginate) {
            $adapter = new ArrayAdapter($files);
            $paginator = new Paginator($adapter);
            return $paginator;
        }
        return $files;
    }
}
