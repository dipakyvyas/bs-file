<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/BsFile for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace BsFile;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Doctrine\ODM\MongoDB\Events;

class Module implements AutoloaderProviderInterface
{

 public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $serviceManager = $e->getApplication()->getServiceManager();


        $dm = $serviceManager->get('doctrine.documentmanager.odm_default');
        $ODMListener = $serviceManager->get('bsfile_event_listener');

        $evm = $dm->getEventManager();
        $evm->addEventListener(Events::prePersist, $ODMListener);
        $evm->addEventListener(Events::postPersist, $ODMListener);
        $evm->addEventListener(Events::preUpdate, $ODMListener);
        $evm->addEventListener(Events::postUpdate, $ODMListener);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                /**
                 * @Deprecated use BsFile\Storage\Repository for repository
                 */
                "BsFile_image_service" => function ($service_manager)
                {
                    $dm = $service_manager->get('doctrine.documentmanager.odm_default');
                    return $dm->getRepository('\BsFile\Domain\Aggregate\Image');
                },

                "BsFile_image_resize" => function ($service_manager)
                {
                    return new \BsFile\Library\BsImage();
                }
            )
        );
    }

    public function getFormElementConfig()
    {
        return array(
            'factories' => array(
                '\BsFile\Form\FileForm' => function ($sm)
                {
                    $sl = $sm->getServiceLocator();
                    $form = new \BsFile\Form\FileForm();
                    return $form;
                }
            )
        );
    }
}
