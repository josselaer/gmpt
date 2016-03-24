<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
        'GMPT' => [
            'username' =>'testuser',
            'password' => 'test',
            'host' => 'localhost',
            'dbname' => 'GMPT',
            'db' => 'mysql',
        
        ],
        'GMPTdb' => [
            'username' =>'gmpt_master_user',
            'password' => 'gmptMaster1',
            'host' => 'gmpt-dev.cze344fgq3d6.us-west-2.rds.amazonaws.com',
            'dbname' => 'GMPT',
            'db' => 'mysql',
        
        ],
    ],
];
