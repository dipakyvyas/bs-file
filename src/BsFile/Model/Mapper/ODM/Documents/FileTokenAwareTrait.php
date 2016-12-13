<?php
namespace BsFile\Model\Mapper\ODM\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use BsFile\Model\Mapper\FileInterface;

trait FileTokenAwareTrait
{

    /**
     *
     * @var String @Field(type="string")
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