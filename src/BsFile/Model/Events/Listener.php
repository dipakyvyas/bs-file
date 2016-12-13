<?php
namespace BsFile\Model\Events;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use BsFile\Model\Mapper\FileTokenAwareInterface;
use BsFile\Model\Mapper\FileParamsAwareInterface;

class Listener implements ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    private $files = [];

    private function processToken(LifecycleEventArgs $eventArgs, $recompute=false){
        $entity = $eventArgs->getDocument();
        if ($entity instanceof FileTokenAwareInterface) {

            $mapper = $this->getServiceLocator()->get('bsfile_mapper');
            $fileToken = $entity->getFileToken();
            if(!$fileToken){
                return false;
            }
            $repo = $mapper->getRepository('AbstractFile');
            $files = $repo->findBy(array(
                'fileToken' => $fileToken
            ));

            foreach ($files as $file) {
                if($file->getField()){

                    $method='add'.ucfirst($file->getField());
                    if(!method_exists($entity, $method)){
                        throw new \Exception('FileTokenAwareInterface objects must always implementer adders for file fields ');
                    }
                    $entity->$method($file);
                }else{
                    $entity->addFile($file);
                }

                $file->setFileToken(null);
                $files[] = $file;
            }
            if($recompute){
                $dm = $eventArgs->getDocumentManager();
                $class = $dm->getClassMetadata(get_class($entity));

                $dm->getUnitOfWork()->recomputeSingleDocumentChangeSet($class, $entity);
            }

        }
    }

    private function setParams(LifecycleEventArgs $eventArgs){
        $entity = $eventArgs->getDocument();
        if ($entity instanceof FileParamsAwareInterface) {

            $dm = $this->getServiceLocator()->get('bsfile_mapper')->getInstance();
            foreach ($entity->getFiles() as $file) {
                $file->setParams($entity->fileParams());
                $dm->flush($file);
            }
        }
    }

    /**
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->processToken($eventArgs, true);
    }

    /**
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
       $this->processToken($eventArgs);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->setParams($eventArgs);
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
       $this->setParams($eventArgs);
    }

    public function __destruct()
    {
        $mapper = $this->getServiceLocator()->get('bsfile_mapper');
        $repo = $mapper->getRepository('AbstractFile');
        foreach ($this->files as $file) {
       $repo->save($file);
        }
    }
}
