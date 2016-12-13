<?php
namespace BsFile\Model\Mapper;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
/**
 *
 * @author mat_wright
 * @deprecated use  BsFile\Model\Mapper\ODM\Documents\FileTokenAwareTrait
 */
trait FileTokenAwareTrait
{

    /**
     *
     * @var String @ODM\Field(type="string")
     */
    private $fileToken;

    /**
     *
     * @return the $fileToken
     */
    public function getFileToken()
    {
        return $this->fileToken;
    }

    /**
     *
     * @param field_type $fileToken
     */
    public function setFileToken($fileToken)
    {
        $this->fileToken = $fileToken;
    }

    /*
     * (non-PHPdoc)
     * @see \BsFile\Model\Mapper\FileTokenAwareInterface::addFile()
     */
    public function addFile(FileInterface $file)
    {
        $this->files->add($file);
        return $this;
    }
}