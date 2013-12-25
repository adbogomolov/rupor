<?php

class BaseController extends CController
{
	public $layout = '//layouts/main';
	public $breadcrumbs = array();
	
	public function init()
	{
		header('Content-type: text/html;charset=utf-8');
		parent::init();
		
		// Отладочная информация
		if (defined('YII_DEBUG') && YII_DEBUG)
		{
			Yii::app()->clientScript->registerScript('debug', '
				if (typeof(console)==\'object\') {
					console.info("Использовано памяти: '. Text::bytes(Yii::getLogger()->getMemoryUsage(), null, '%01.2f %s') .'");
					console.info("Время выполнения: '. sprintf('%0.5f', Yii::getLogger()->getExecutionTime()) .'");
				}
			');
		}
		
		// disable jquery loading on ajax requests
		if (Yii::app()->request->isAjaxRequest)
		{
			$this->layout = null;
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
		}
	}
}