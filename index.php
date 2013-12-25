<?php

if ($_SERVER['REMOTE_ADDR'] == '195.62.15.28' || $_SERVER['REMOTE_ADDR'] == '188.190.93.47')
{
	define('ENV', 'devel');
}

defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__));
defined('YII_PATH') || define('YII_PATH', (getenv('YII_PATH') ? getenv('YII_PATH') : BASE_PATH . '/../yii'));
defined('ENV') || define('ENV', (getenv('ENV') ? getenv('ENV') : 'main'));

if (ENV == 'devel')
{
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	
	define('YII_DEBUG', true);
	define('YII_TRACE_LEVEL', 3);
	$yii = YII_PATH.'/yii.php';
}
else
{
	$yii = YII_PATH.'/yiilite.php';
}

error_reporting(0);
require_once($yii);
Yii::createWebApplication(BASE_PATH.'/protected/config/'.ENV.'.php')->run();