<?php

class Loginza extends CComponent
{
	public $auth_url = 'https://loginza.ru/api/authinfo?token=';
	public $token_url = 'https://loginza.ru/api/widget?token_url=';
	public $widget_url = '//loginza.ru/js/widget.js';
	
	public $return_url = '/site/loginza';
	
	public $providers = array(
		'vkontakte', 'facebook', 'twitter', 'loginza'
		, 'myopenid', 'webmoney', 'rambler', 'flickr', 'lastfm', 'openid'
		, 'mailru', 'verisign', 'aol', 'steam', 'google', 'yandex'
		, 'mailruapi'
	);
	
	public function init()
	{
	}
	
	/**
	 * Получение данных от сервиса Loginza.
	 * Предварительно нужно установить $token
	 */
	public function getData($token)
	{
		return json_decode(file_get_contents($this->auth_url.$token) ,true);
	}
	
	/**
	 * Виджет авторизации, привязки аккаунта
	 */
	public function render($text, $return_url='')
	{
        Yii::app()->clientScript->registerScriptFile($this->widget_url, CClientScript::POS_END);
		if (!$return_url)
			$return_url = $this->return_url;
		$url = $this->token_url . Yii::app()->createAbsoluteUrl($return_url) . '&overlay=loginza&providers_set=' . implode(',', $this->providers);
		// echo CHtml::link($text, $url, array('class'=>'loginza'));
		
		echo '<iframe src="'.$url.'" style="width:450px;height:300px;" scrolling="no" frameborder="no"></iframe>';
    }
}