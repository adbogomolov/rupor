<?php

class Controller extends BaseController
{
	public $layout = '//layouts/column1';
    public $meta = array();
    public $js = array();

	/**
	 * Initialization
	 */
    public function init()
    {
		parent::init();

		// Global Javascript params
		$this->js = array(
			'cookieName' => 'rupor',
			'domain' => Yii::app()->request->serverName,
			'lang' => Yii::app()->language,
			'uid' => (int) Yii::app()->user->id,
			'dir' => Yii::app()->locale->orientation,
			'date' => explode(' ', date('Y m d H i s')),
			'params' => $_GET,
		);

		if(stripos($_SERVER['HTTP_HOST'], 'widget') !== false)
		{
			$this->layout = '//layouts/iframe';
		}
	}

	/**
	 * Filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * AccessRules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',
				'users' => array('@'),
			),
            array('deny')
		);
	}
}