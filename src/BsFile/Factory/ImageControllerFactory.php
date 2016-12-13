<?php
/**
 *
 * @author florent guenebeaud <florent@bstechnologies.com>
 * @copyright broadshout technologies sarl
 * @link bstechnologies.com
 *
 */
namespace BsFile\Factory;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \BsBase\Factory as BSFactory;

/**
 * @deprecated Use BsFile\Model\Mapper\ODM\Factory
 */
class ImageControllerFactory extends BSFactory\BaseControllerFactory implements FactoryInterface, BSFactory\ControllerFactoryInterface, BSFactory\DocumentManagerAwareControllerFactoryInterface, BSFactory\FormManagerAwareControllerFactoryInterface, BSFactory\ConfigAwareControllerFactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$this->setServiceManager($serviceLocator->getServiceLocator());
		$this->getController()->setImageManager($this->getServiceManager()
				->get('BsFile_image_resize'));

		 
		$this->attachDefaultServices();
		return $this->getController();
	}
}

