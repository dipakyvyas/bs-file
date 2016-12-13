<?php
namespace BsFile\Model\Mapper\ODM\Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
/**
 * Class made for CSV
 *
 * @ODM\Document
 */
class CSV extends AbstractFile
{

    /**
     */
    function __construct()
    {
        $this->setMime('text/csv');
    }


}