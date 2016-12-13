<?php
namespace BsFile\Controller;

use BsBase\Controller\BaseController;
use Zend\View\Model\JsonModel;
use BsFile\Model\Mapper\MapperInterface;
use BsFile\Model\Mapper\FileInterface;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Filter\Word\Zend\Filter\Word;

class FileController extends BaseController
{

    protected $mapper;

    public function getMapper()
    {
        if (! $this->mapper instanceof MapperInterface) {
            $this->mapper = $this->getServiceLocator()->get('bsfile_mapper');
        }
        return $this->mapper;
    }

    /**
     * Send file to the browser, inline or via attachment
     *
     * find the file with a parameter
     *
     * Call OutputFile plugin
     */
    public function outputAction()
    {
        return $this->bsfile_output($this->params()
            ->fromRoute('id'), $this->params()
            ->fromQuery('disposition', 'attachment'), $this->params()
            ->fromQuery('width'));
    }

    /**
     *
     * @param string $fileType            
     * @return string
     */
    private function detectFileType($fileType)
    {
        switch ($fileType) {
            case 'image/jpeg':
                $fileEntity = $this->getMapper()->getEntity('Image');
                break;
            /**
             * @TODO Ajouter different type de fichier
             */
            default:
                $fileEntity = $this->getMapper()->getEntity('File');
                break;
        }
        
        return $fileEntity;
    }

    /**
     * Upload a file to the database from a post request
     */
    public function uploadAction()
    {
        /*
         * Response type needed
         *
         * url: "http://url.to/file/or/page",
         * thumbnail_url: "http://url.to/thumnail.jpg ",
         * name: "thumb2.jpg",
         * type: "image/jpeg",
         * size: 46353,
         * delete_url: "http://url.to/delete /file/",
         * delete_type: "DELETE"
         *
         */
        $json = new JsonModel();
        
        $request = $this->getRequest();
        
        $fileToken = $this->params('fileToken');
        $fileField = $this->params('fileField');
        $fileType = $this->params('fileType');
        $response = array();
        
        if ($request->isPost()) {
            // $request->getFiles()->toArray(); // Uploaded file
            foreach ($request->getFiles() as $files) {
                if(array_key_exists('name', $files)){
                    $files = [
                        $files
                    ];
                }
                foreach ($files as $file) {
                    
                    if ($fileType) {
                        $fileType = (new UnderscoreToCamelCase())->filter($fileType);
                        $fileEntity = $this->getMapper()->getEntity($fileType);
                    } else {
                        $fileEntity = $this->detectFileType($file['type']);
                    }
                    
                    if (! $fileEntity instanceof FileInterface) {
                        $fileEntity = $this->getMapper()->getEntity('File');
                    }
                    
                    if ($fileField) {
                        $fileField = (new UnderscoreToCamelCase())->filter($fileField);
                        $fileEntity instanceof FileInterface;
                        $fileEntity->setField($fileField);
                    }
                    $fileEntity->setName($file['name']);
                    $fileEntity->setFile($file['tmp_name']);
                    $fileEntity->setMime($file['type']);
                    $fileEntity->setUploadDate(new \DateTime('NOW'));
                    $fileEntity->setMeta($file);
                    $fileEntity->setActive(true);
                    $fileEntity->setFileToken($fileToken);
                    $this->getMapper()
                        ->getInstance()
                        ->persist($fileEntity);
                    $this->getMapper()
                        ->getInstance()
                        ->flush($fileEntity);
                    
                    $dataFile = array(
                        'name' => $file['name'],
                        'size' => $file['size'],
                        'id' => $fileEntity->getId(),
                        'url' => $outputUrl = $this->url()->fromRoute('bs-file-manager/output', array(
                            'id' => $fileEntity->getId()
                        )),
                        'thumbnailUrl' => '',
                        'deleteUrl' => $this->url()->fromRoute('bs-file-manager/delete', array(
                            'id' => $fileEntity->getId(),
                            'fileToken' => $fileToken
                        )),
                        'deleteType' => "DELETE"
                    );
                }
                $response[] = $dataFile;
            }
        }
        $json->setVariable('files', $response);
        return $json;
    }

    /**
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function deleteAction()
    {
        $file = $this->getMapper()
            ->getRepository('File')
            ->findOneBy([
            'id' => $this->params('id'),
            'fileToken' => $this->params('fileToken')
        ]);
        
        try {
            $this->getMapper()
                ->getInstance()
                ->remove($file);
            $this->getMapper()
                ->getInstance()
                ->flush($file);
            return new JsonModel(array(
                'status' => true
            ));
        } catch (\Exception $e) {
            return new JsonModel(array(
                'status' => false,
                'message' => $e->getMessage()
            ));
        }
    }
}