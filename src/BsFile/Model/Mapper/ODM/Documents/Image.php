<?php
namespace BsFile\Model\Mapper\ODM\Documents;

use BsFile\Model\Mapper\ImageInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class made for Image Files like .
 * jpeg, .gif, .png, etc
 * 
 * @ODM\Document
 */
class Image extends AbstractFile implements ImageInterface
{

    /**
     *
     * @var @ODM\Field(type="string")
     */
    private $caption;

    /**
     * (non-PHPdoc)
     * 
     * @see \BsFile\Model\Mapper\ImageInterface::getCaption()
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \BsFile\Model\Mapper\ImageInterface::setCaption()
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }
}