<?php
namespace BsFile\Model\Mapper\ODM\Documents;

use BsBase\Model\Mapper\ODM\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Date;
use BsFile\Model\Mapper\FileInterface;

/**
 * @ODM\Document(collection="File",repositoryClass="\BsFile\Model\Mapper\ODM\Repository\FileRepository")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"image"="\BsFile\Model\Mapper\ODM\Documents\Image", "document"="\BsFile\Model\Mapper\ODM\Documents\Document", "video"="Video","file"="\BsFile\Model\Mapper\ODM\Documents\File", "csv"="\BsFile\Model\Mapper\ODM\Documents\CSV"})
 */
abstract class AbstractFile extends AbstractDocument implements FileInterface
{

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $name;

    /**
     * @ODM\File @ODM\Index
     */
    protected $file;

    /**
     * @ODM\Date @ODM\Index
     */
    protected $uploadDate;

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $length;

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $chunkSize;

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $md5;

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $mime;

    /**
     * @ODM\Hash
     */
    protected $meta;

    /**
     * @ODM\Field(type="string")
     */
    protected $field;

    /**
     * Determines if the file can be show/download
     *
     * @ODM\Boolean @ODM\Index
     */
    protected $active;

    /**
     * @ODM\Field(type="string") @ODM\Index
     */
    protected $fileToken;

    /**
     *
     * @var array @ODM\Hash @ODM\Index(sparse=true)
     */
    protected $params;

    /**
     *
     * @return string $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

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

    /**
     *
     * @param Date $uploadDate
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return \BsFile\Model\Mapper\ODM\Documents\File
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \BsFile\Model\Mapper\FileInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \BsFile\Model\Mapper\FileInterface::getFile()
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @param File $file
     * @return \BsFile\Model\Mapper\ODM\Documents\File
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     *
     * @return \Doctrine\ODM\MongoDB\Mapping\Annotations\Date
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     *
     * @return string
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \BsFile\Model\Mapper\FileInterface::getMime()
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     *
     * @param string $mime
     * @return \BsFile\Model\Mapper\ODM\Documents\File
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     *
     * @return boolean $active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     *
     * @param boolean $active
     * @return File $this;
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     *
     * @return the $meta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     *
     * @param field_type $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     *
     * @return the $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * @param multitype: $params
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}