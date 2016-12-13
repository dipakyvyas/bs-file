<?php
namespace BsFile\Model\Mapper;

/**
 * Interface used for every File entity whatever Storage is used
 */
interface FileInterface
{

    /**
     *
     * @return string
     */
    public function getId();

    /**
     *
     * @return string
     */
    public function getName();

    public function getFile();

    /**
     *
     * @return string
     */
    public function getMime();

    /**
     * Determines if the file can be show/download
     *
     * @return boolean
     */
    public function getActive();

    /**
     * Return the session token to link the entity with the file
     * 
     * @return string
     */
    public function getFileToken();
    
    /**
     * Set the field to which the file should be saved in the owning object
     * 
     * @return field
     */
    public function getField();
}
