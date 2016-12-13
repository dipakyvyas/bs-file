<?php
namespace BsFile\Form;


use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectSelect;
/**
 *
 * @author Mat Wright <mat.wright@broadshout.com>
 *        
 */
class FileForm extends Form
{
  
    
    public function __construct()
    {
     
        // we want to ignore the name passed
        parent::__construct('article');
    
       
        
        // Element pour le champs name
       $this->add(array(
            'name' => 'file',
            'required' => false,
            'type' => 'file',
            'attributes' => array(
                'required' => false
            ),
            'options' => array(
                'label' => 'Images: '
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save Changes',
                'id' => 'submitbutton',
               'class' => "btn btn-default"
            )
        ));
    }
    
}

?>