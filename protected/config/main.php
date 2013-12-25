<?php

define('DOMAIN', 'e-rupor.ru');

return array(
    'basePath'			=> dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'				=> 'Rupor',
    'language'			=> 'ru',
    'theme'				=> 'default',
    'defaultController'	=> 'site',
	'preload'			=> array('log'),
	'import'       		=> array('application.base.*', 'application.models.*', 'application.forms.*', 'application.components.*'),
    'params'			=> array(
		// 'RequestFile_maxSize' => 5 * 1024 & 1024, // 2Mb
		// 'RequestFile_allowedTypes' => array('jpg','jpeg','png','gif'),
	),
    'components'		=> array(
		'loginza' => array(
			'class' => 'application.extensions.loginza.Loginza',
			'return_url' => '/user/loginza',
			'providers' => array('vkontakte','facebook','twitter','mailruapi','google','yandex')
		),
		'curl' => array(
    		'class'   => 'application.extensions.curl.Curl',
    		'options' => array(
        		CURLOPT_TIMEOUT => 10
    	)),
		'storage' => array(
			'class' => 'application.components.Storage',
		),
		'db' => array(
			'class' => 'CDbConnection',
			// 'connectionString' => 'mysql:host=aws.voodoo-mobile.com;dbname=rupor',
			'connectionString' => 'mysql:host=127.0.0.1;dbname=rupor',
			'username' => 'root',
			// 'password' => 'w3d0mag1c',
			'password' => 'r.u.p.o.r',
			'emulatePrepare' => false,
			'charset' => 'utf8',
			'enableParamLogging' => 1,
			'enableProfiling' => 0,
			'schemaCachingDuration' => 1000,
			'tablePrefix' => '',
		),
		// Старая бд
		// 'db2' => array(
			// 'class' => 'CDbConnection',
			// 'connectionString' => 'mysql:host=127.0.0.1;dbname=bardakovka',
			// 'username' => 'root',
			// 'password' => 'r.u.p.o.r',
			// 'emulatePrepare' => false,
			// 'charset' => 'utf8',
			// 'enableProfiling' => 1,
		// ),
        'urlManager' => array(
            'urlFormat'			=> 'path',
			'showScriptName'	=> false,
            'rules' => array(
				'badbrowser'	=> 'site/badbrowser',
				'search'		=> 'site/search',
				'stats'			=> 'site/stats',
				'away'			=> 'site/away',
				// ...
				'page/<url:\w+>'										=> 'page/view',
				'wiki/<id:\w+>'											=> 'wiki/view',
				'request/<id:\d+>'										=> 'request/view',
				// администрация
				'admin/index'											=> 'admin/index',
				'admin/<controller:\w+>/<action:\w+>/<id:\w+>'			=> '<controller>Admin/<action>',
				'admin/<controller:\w+>/<action:\w+>'					=> '<controller>Admin/<action>',
				'admin/<controller:\w+>'								=> '<controller>Admin',
				// общие правила
				// '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'	=> '<module>/<controller>/<action>',
				// '<module:\w+>/<controller:\w+>/<action:\w+>'			=> '<module>/<controller>/<action>',
				// '<module:\w+>/<controller:\w+>'							=> '<module>/<controller>/index',
				'<controller:\w+>/<action:\w+>'							=> '<controller>/<action>',
				'<controller:\w+>'										=> '<controller>/index',
            ),
        ),
        'assetManager' => array(
            'forceCopy' => false,
            'linkAssets' => true,
        ),
		'authManager' => array(
			'class' => 'PhpAuthManager',
			'defaultRoles' => array('guest'),
		),
        // 'request' => array(
            // 'enableCsrfValidation' => false,
            // 'csrfTokenName' => 'hash',
        // ),
        'user' => array(
            'class' => 'WebUser',
			'loginUrl' => array('/user/login'),
			'allowAutoLogin' => true,
			// 'identityCookie' => array('domain' => '.'.DOMAIN),
        ),
		'session' => array(
			'cookieParams' => array(
				'class' => 'CHttpSession',
				// 'domain' => '.'.DOMAIN,
			)
		),
		// 'clientScript'=>array(
			// 'packages' => array(
				// 'jquery' => array(
					// 'baseUrl'=>'//ajax.googleapis.com/ajax/libs/jquery/1.10.2/',
					// 'js'=>array('jquery.min.js'),
				// )
				// 'rupor' => array(
					// 'baseUrl' => '/themes/default/js/',
					// 'js' => array('base.js'),
					// 'depends' => array('jquery'),
				// ),
				// 'rupor.map' => array(
					// 'baseUrl' => '/themes/default/js/',
					// 'js' => array('rupor.map.js'),
					// 'depends' => array('rupor'),
				// )
			// )
		// ),
		'errorHandler'=>array(
            'errorAction' => 'site/error',
        ),
		'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning, info',
				),
			),
        ),
		// custom
        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            'driver' => 'GD',
        ),
        // 'mail' => array(
            // 'class' => 'application.components.Mail',
			// 'from' => '<noreply@'. DOMAIN .'>',
        // ),
	),
);