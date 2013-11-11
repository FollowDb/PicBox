<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'PicBox',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.helpers.*',
	),
        'aliases' => array(
            'xupload' => 'ext.xupload'
        ),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'root',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1', '192.168.1.2', '172.16.1.9'),
//		),
		
	),
    
	'defaultController'=>'pic',

	// application components
	'components'=>array(
                'image'=>array(
                    'class'=>'application.extensions.image.CImageComponent',
                      // GD or ImageMagick
                      'driver'=>'GD',
                      // ImageMagick setup path
                      'params'=>array('directory'=>'/opt/local/bin'),
                  ),
                'format' => array(
                        'dateFormat' => 'd.m.Y H:i:s'
                ),
		'user'=>array(
			// enable cookie-based authentication
			'loginUrl'=>('/'),
			'allowAutoLogin'=>true,
		),
//		'db'=>array(
//			'connectionString' => 'sqlite:protected/data/blog.db',
//			'tablePrefix' => 'tbl_',
//		),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=picbox',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
//                        'urlSuffix'=>'.html',
                        'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
			),
		),
 
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info, error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);