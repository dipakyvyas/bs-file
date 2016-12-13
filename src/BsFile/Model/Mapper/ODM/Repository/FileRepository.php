<?php
namespace BsFile\Model\Mapper\ODM\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use BsFile\Model\Mapper\FileRepositoryInterface;
use BsFile\Model\Mapper\FileInterface;
use BsFile\Model\Mapper\ODM\Documents\AbstractFile;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

/**
 * Repository class for files
 */
class FileRepository extends DocumentRepository implements FileRepositoryInterface
{

    /**
     * Saves a File entity to the database
     *
     * @param FileInterface $file            
     * @return void
     */
    public function save(FileInterface $file, $flush = true)
    {
    
            // add File entity to document manager
            $this->getDocumentManager()->persist($file);
            // by default flush all changes to DB
            if ($flush) {
                $this->getDocumentManager()->flush($file);
            }
            
        
    }
    
    /*
     * (non-PHPdoc)
     * @see \BsFile\Model\Mapper\FileRepositoryInterface::remove()
     */
    public function remove(\BsFile\Model\Mapper\FileInterface $file)
    {
        // TODO Auto-generated method stub
    }

    /**
     * Finds files by keyword
     *
     * @param string $keyword
     * @param array $params
     * @return \Doctrine\MongoDB\Cursor
     */
    public function findByKeyword($keyword, array $params = array())
    {
        $regex = new \MongoRegex('/' . $keyword . '/i');
        $qb = $this->createQueryBuilder();
        foreach($params as $key => $value) {
            $qb->field('params.' . $key)
                ->equals($value);
        }
        $qb->field('name')
            ->equals($regex);
        return $qb->getQuery()->execute();
    }
}