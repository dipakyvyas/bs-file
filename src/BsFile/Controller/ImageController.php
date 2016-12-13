<?php
namespace BsFile\Controller;

use Zend\View\Model\ViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use BsBase\Controller\BaseController;
use Zend\Filter\File\RenameUpload;
use BsFile\Library\BsImage;

/**
 * @deprecated use FileController
 *
 * @author florent guenebeaud <florent@bstechnologies.com>
 * @copyright broadshout technologies sarl
 * @link bstechnologies.com
 *
 */
class ImageController extends BaseController
{

 private $imageManager;

    public function setImageManager($imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function getImageManager()
    {

        if (! $this->imageManager) {
            $this->imageManager = $this->serviceLocator->get('BsFile_image_resize');
        }
        return $this->imageManager;
    }


    public function imagesAction() {

        $params = $this->params()->fromRoute('params');
        $config = $this->getConfig();
        if (isset($params)) {
            if (!isset ($params['route_error']) && empty($params['route_error'])) {
                throw new \Exception('An error has occured while Error file .', true);
            }
            if (!isset($params['parent-entity']) && empty($params['parent-entity'])) {
                $this->flashMessenger()->addErrorMessage("An has occured, no object name specified");
                return $this->redirect()->toRoute($params['route_error']);
            }
            //Get Class Name
            $entityClass = $params['parent-entity'];
            //Get Repository
            $repository = $this->getDocumentManager()->getRepository($entityClass);
        } else {
            throw new \Exception('An error has occured while Error file .', true);
        }
        $view = new ViewModel();
        $imageObject = new \BsFile\Domain\Aggregate\Image();
        $form = $this->getFormManager()->get('\BsFile\Form\FileForm');


        $fileInput = new \Zend\InputFilter\FileInput('file');
        $fileInput->setRequired(false);
        $fileFilter=new RenameUpload("./data/tmp/");
        $fileFilter->setRandomize(true);
        $fileInput->getFilterChain()->attach($fileFilter);
        $fileInput->getValidatorChain()->addValidator(new \Zend\Validator\File\Extension(array(
            'jpg',
            'jpeg',
            'png'
        )));
        $fileInput->getValidatorChain()->addValidator(new \Zend\Validator\File\Size('2MB'));

        $imageFilter = $imageObject->getInputFilter()->add($fileInput);

        $form->setInputFilter($imageFilter);

        //Get Document
        if ($this->params('idEntity')) {
             $entity = $repository->find($this->params('idEntity'));
        } else {
            $this->flashMessenger()->addErrorMessage("An has occured, this object does not exist. Please retry");
            return $this->redirect()->toRoute($params['route_error']);
        }
        if ($entity) {
            $formArrayCollection = new ArrayCollection();
            $images = $entity->getImages();
            $this->return['images'] = $images;
            $this->return['entity'] = $entity;
        } else {
            $this->flashMessenger()->addErrorMessage("An has occured, this object does not exist. Please retry");
            return $this->redirect()->toRoute($params['route_error']);
        }
        $prg = $this->fileprg($form);
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            // returned a response to redirect us
            return $prg;
        }

        // Delete Image from Entity

        if ($this->params()->fromQuery('delete')) {
            $idImage = $this->params()->fromQuery('delete');
            $imageDelete = $this->getDocumentManager()
    		->getRepository('BsFile\Domain\Aggregate\Image')->find($idImage);
            if ($imageDelete) {
                $entity->removeImage($imageDelete);
                $this->getDocumentManager()->persist($entity);
                if ($this->getDocumentManager()
    		->getRepository('BsFile\Domain\Aggregate\Image')->remove($imageDelete)) {
                    $this->getDocumentManager()->flush();
                    $this->flashMessenger()->addSuccessMessage("The image has been deleted successfully");
                    return $this->redirect()->refresh();
                } else {

                    $this->flashMessenger()->addErrorMessage("An error has occured while deleting image. Please retry");
                    return $this->redirect()->toRoute($params['route_error']);

                }
            } else {
                $this->flashMessenger()->addErrorMessage("An error has occured while deleting image. Please retry");
                return $this->redirect()->toRoute($params['route_error']);
            }
        }

        // if the $prg has data process form
        if ($prg !== false) {
            // add the data to the form
            $form->setData($prg);

            if ($form->isValid()) {
                $formData = $form->getData();
                if (isset($formData['file']) && ! empty($formData['file'])) {
                    $imageObject = new \BsFile\Domain\Aggregate\Image();
                    // resize the image to 500px
                    $bsimage = BsImage::resize($formData['file']['tmp_name'], 800);
                    //file_put_contents($formData['file']['tmp_name'], $bsimage);
                    $meta = $formData['file'];
                    $imageObject->setName($formData['file']['name']);
                    $imageObject->setFile($formData['file']['tmp_name']);
                    $imageObject->setMime($formData['file']['type']);
                    $imageObject->setUploadDate(new \DateTime('NOW'));
                    $imageObject->setMeta($meta);

                    //BsFile_image_service
                    if ( !$this->getDocumentManager()
    		->getRepository('BsFile\Domain\Aggregate\Image')->create($imageObject)) {
                        throw new \Exception('An error has occured .', true);
                    }
                    $entity->addImage($imageObject);
                    $this->getDocumentManager()->persist($entity);
                    $this->getDocumentManager()->flush();
                    unlink($formData['file']['tmp_name']);
                    $this->flashMessenger()->addSuccessMessage("The image has been updated successfully");
                }
            } else {
                throw new \Exception('An error has occured .', true);exit;
            }
        }
        $this->return['form'] = $form;
        $view->setVariables($this->return);
        return $view;
    }


    /**
     * Prints an image to the screen
     *
     * @return Ambigous <\Zend\Stdlib\ResponseInterface, \Zend\Cache\Storage\mixed>
     */
    public function showAction()
    {

    	//check if custom entity passed in route
    	if($this->params()->fromRoute('params') && array_key_exists('entity', $this->params()->fromRoute('params'))){
    	    $params=$this->params()->fromRoute('params');
    	    $entity=$params['entity'];
    	    //ensure class exists and is within global scope
    	    if(!class_exists($entity)){
    	    throw new \Exception('class '.$entity.' does not exist');
    	    }

    	    //check entity class is AbstractFile
    	    if(!in_array('BsFile\Domain\Aggregate\ImageInterface', class_implements($entity))){
    	        throw new \Exception('class '.$entity.' must extend BsFile\Domain\Aggregate\ImageInterface');
    	    }

    	}else{
    	    $entity='BsFile\Domain\Aggregate\Image';
    	}

    	$width=$this->params()->fromQuery('width');

    	$this->getCache()->getOptions()->setNamespace('BsFileImage');

    	//check if a width parameter is present, if so resize image.
    	$success=false;
    	//set default cache succes hit to false
    	$cachedResponse = $this->getCache()->getItem('bsfile_'.($width?$width.'_':null).$this->params('id'), $success);

    	if (! $success) {

    		/**
    		 * BsFile\Domain\Aggregate\Image $imageEntity
    		 */
    		$imageEntity = $this->getDocumentManager()
        		->getRepository($entity)
        		->find($this->params('id'));

    		if (! $imageEntity) {
    			$this->getResponse()->setStatusCode(404);

    		}
    		if ($width) {


    		    $content = BsImage::resize($imageEntity, $width, null,true);

    		} else {
        		$content=$imageEntity->getFile()
        		    ->getBytes();
    		}

    		$etag = md5($content);
    		$this->getResponse()->setContent($content);
    		$headers=$this->getResponse()
    		    ->getHeaders();
    		$headers->clearHeaders()
        	    ->addHeaderLine('Content-Type', $imageEntity->getMime())
        		->addHeaderLine('Content-Length', strlen($content))
        		->addHeaderLine('Expires', date(DATE_RFC822,strtotime('+1 year')))
        		->addHeaderLine('Etag', $etag)
    		->addHeaderLine('Pragma', 'private');


    		$cachedResponse=$this->getResponse();
    		$this->getCache()->setItem('bsfile_'.($width?$width.'_':null).$this->params('id'), $cachedResponse);

    	}



    	return $cachedResponse;
    }


}

?>