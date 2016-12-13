<?php
namespace BsFile\Model\Mapper\ODM\Documents;

use BsFile\Model\Mapper\FileInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Implements methods in MultiFileInterface for ODM
 * You must declare your $files property with Annotation etc
 *
 * @author mat_wright
 *
 */
trait MultiFileInterfaceTrait
{

    /**
     * @param FileInterface $file
     */
    public function addFile(FileInterface $file)
    {
        $this->files->add($file);
    }

    /**
     * @param FileInterface $file
     */
    public function removeFile(FileInterface $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }
}