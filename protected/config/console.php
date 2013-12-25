<?php

$config = require(dirname(__FILE__).'/main.php');
unset($config['defaultController']);
unset($config['theme']);

return CMap::mergeArray(
	$config,
	array(
		'components'=>array(
			'cache' => new CDummyCache(), // disabled cache
			'log' => array(
				'class'  => 'CLogRouter',
				'routes' => array(
					array(
						'class' => 'CFileLogRoute',
						'logFile' => 'cron.log',
						'levels' => 'error, warning',
					),
					array(
						'class' => 'CFileLogRoute',
						'logFile' => 'cron_trace.log',
						'levels' => 'trace',
					),
				),
			),
		),
	)
);