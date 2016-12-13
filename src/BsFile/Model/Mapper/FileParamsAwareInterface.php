<?php
namespace BsFile\Model\Mapper;

interface FileParamsAwareInterface
{
    /**
     * @return array
     */
    public function fileParams();
    
    public function getFiles();
}