<?php
return array(
    'controllers' => array(
        'factories' => array(
            'BsFile\Controller\Image' => 'BsFile\Factory\ImageControllerFactory',
            'BsFile\Controller\File' => 'BsFile\Controller\Factory\FileControllerFactory'
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'BsFile\Model\Events\Listener' => 'BsFile\Model\Events\Listener',
            'BsFile\Model\Mapper\ODM\Mapper' => 'BsFile\Model\Mapper\ODM\Mapper',
            'BsFile\Service\FileService' => 'BsFile\Service\FileService'
        ),
        'aliases' => array(
            'bsfile_event_listener' => 'BsFile\Model\Events\Listener',
            'bsfile_mapper' => 'BsFile\Model\Mapper\ODM\Mapper',
            'odm'=>'doctrine.documentmanager.odm_default',
            'bsfile_service' => 'BsFile\Service\FileService'
        )
    ),

    'view_helpers' => array(
        'invokables' => array(
            'bsfile_thumbnail' => 'BsFile\View\Helper\ImageThumbnail',
            'bsfile_url' => 'BsFile\View\Helper\FileUrl',
            'bsfile_ajaxfilemultiuploader'=>'BsFile\View\Helper\AjaxFileMultiUploader',
            'bsfile_readable_bytes'=>'BsFile\View\Helper\ReadableBytes'
        ),
        'aliases' => array(
            'bs_thumbnail' => 'bsfile_thumbnail'
        )
    ),
    'controller_plugins' => array(

    'invokables' => array(
    'bsfile_output'=>'BsFile\Controller\Plugin\OutputFile',
    'bsfile_readable_bytes'=>'BsFile\Controller\Plugin\ReadableBytes'
    ),
    ),

    'view_manager' => array(

        'template_path_stack' => array(
            'BsFile' => __DIR__ . '/../view'
        )
    ),
    'router' => array(
        'routes' => array(

            'bs-file-manager' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/file',
                    'defaults' => array(
                        '__NAMESPACE__' => 'BsFile\Controller',
                        'controller' => 'BsFile\Controller\Image',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    'upload-file' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/upload/:fileToken[/:fileField][/:fileType]',
                            'constraints' => array(
                                'fileToken' => '[a-zA-Z0-9]*',
                                'fileField' => '[a-zA-Z0-9_]*',
                                'fileType' => '[a-zA-Z0-9_]*'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'BsFile\Controller',
                                'controller' => 'BsFile\Controller\File',
                                'action' => 'upload'
                            )
                        )
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/delete/:id/:fileToken',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9]*'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'BsFile\Controller',
                                'controller' => 'BsFile\Controller\File',
                                'action' => 'delete'
                            )
                        )
                    ),
                    'output' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/:id[/:filename]',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9]*',
                                'filename' => '.*[.].*'
                            ),
                            'defaults' => array(
                                'controller' => 'BsFile\Controller\File',
                                'action' => 'output'
                            )
                        )
                    ),


                    /*IMAGE ROUTE*/
                    'image' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/image',
                            'defaults' => array(
                                'controller' => 'BsFile\Controller\Image',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'show' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/:id/:filename',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9]*',
                                        'filename' => '.*'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'BsFile\Controller\Image',
                                        'action' => 'show'
                                    )
                                )
                            ),
                            'show-thumbnail' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/:id/:filename',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9]*',
                                        'filename' => '.*'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'BsFile\Controller\Image',
                                        'action' => 'show',
                                        'params' => array(
                                            'type' => 'thumbnail'
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);
