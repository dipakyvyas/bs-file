<?php
namespace BsFile\Model\Mapper\ODM\Documents;

use BsFile\Model\Mapper\FileInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * File with unknow extension
 *
 *  @ODM\Document(collection="File",repositoryClass="\BsFile\Model\Mapper\ODM\Repository\FileRepository")
 */
class File extends AbstractFile implements FileInterface
{
}