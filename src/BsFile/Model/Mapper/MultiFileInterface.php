<?php
namespace BsFile\Model\Mapper;

/**
 *
 * @author mat_wright
 *
 */
interface MultiFileInterface
{
    public function addFile(FileInterface $file);
    public function removeFile(FileInterface $file);
    public function getFiles();
}