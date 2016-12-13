<?php
namespace BsFile\Model\Mapper;

interface FileTokenAwareInterface
{
    /**
     * @return string
     */
    public function getFileToken();
    /**
     * @param FileInterface $file
     */
    public function addFile(FileInterface $file);
}