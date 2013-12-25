<?php
return CMap::mergeArray(
	array(
		'modules' => array(
			'gii' => array(
				'class'=>'system.gii.GiiModule',
				'password'=>'dizair',
				'ipFilters'=>false,
			)
		),
		'components'=>array(
			'urlManager' => array(
				'rules' => array(
					'gii'=>'gii',
					'gii/<controller:\w+>'=>'gii/<controller>',
					'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				),
			),
		),
	),
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			
			'cache' => new CDummyCache(),
			
			'db' => array (
				'enableProfiling'=>true,
				'enableParamLogging' => true,
				'schemaCachingDuration' => 0,
			),
			
			'log' => array(
				'routes' => array(
					array(
						'class'=>'ext.db_profiler.DbProfileLogRoute',
						'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
						'slowQueryMin' => 0.01, // Minimum time for the query to be slow
						'showInFireBug' => true,
					),
				),
			),
			
		),
	)
);