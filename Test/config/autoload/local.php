<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'odm_default' => array(
                'server' => 'bs-server.local',
                'port' => '27017',
                'connectionString' => null,
                'user' => null,
                'password' => null,
                'dbname' => 'BsFile',
                'options' => array()
            )
        ),
        'configuration' => array(
            'odm_default' => array(
                'metadata_cache' => 'array',
                
                'driver' => 'odm_default',
                
                'generate_proxies' => true,
                'proxy_dir' => 'cache/DoctrineMongoODMModule/Proxy',
                'proxy_namespace' => 'DoctrineMongoODMModule\Proxy',
                
                'generate_hydrators' => true,
                'hydrator_dir' => 'cache/DoctrineMongoODMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',
                'default_db' => 'projectmanager_beta',
                'filters' => array(),
                'logger' => null
            )
        ),
        
        'documentmanager' => array(
            'odm_default' => array(
                'connection' => 'odm_default',
                'configuration' => 'odm_default',
                'eventmanager' => 'odm_default'
            )
        ),
        
        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array()
            )
        )
    )
);