<?php
namespace BsFile\Model\Mapper;


/**
 * Interface used for every Image files entity whatever Storage is used
 */
interface ImageInterface
{

    /**
     * Text to display if the image does not load
     * @return string
     */
    public function getCaption();

    /**
     * Text to display if the image does not load
     * 
     * @param string $caption
     * @return ImageInterface
     */
    public function setCaption($caption);
}