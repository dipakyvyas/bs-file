<?php
namespace BsFile\Model\Mapper;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface corresponding to all repositories
 *
 */
interface FileRepositoryInterface extends ObjectRepository
{
    /**
     * 
     * @param FileInterface $file
     */
    public function save(FileInterface $file,  $flush = true);
    
    public function remove(FileInterface $file);
}

?>