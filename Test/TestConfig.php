<?php
return array(
    'modules' => array(
        'BsFile',
        'BsBase',
        'DoctrineModule',
        'DoctrineMongoODMModule',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            './config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'BsFile',
            'vendor',
        ),
    ),
    
);