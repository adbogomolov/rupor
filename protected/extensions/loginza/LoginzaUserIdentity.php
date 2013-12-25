<?php

class LoginzaUserIdentity extends CBaseUserIdentity
{
	const ERROR_TOKEN = 500;
	
	public $id;
	public $token;
	public $data;
	
	public function __construct($token)
	{
		$this->token = $token;
		$this->data = Yii::app()->loginza->getData($this->token);
	}
	
	public function authenticate()
	{
		if (!empty($this->data['error_message']))
		{
			$this->errorCode = self::ERROR_TOKEN;
			return false;
		}
		
		// @todo: вынести в отдельную таблицу (identity,provider,user_id)
		switch(true){
		case(strpos($this->data['provider'], 'vk.com')!==false):
			$this->data['identity'] = $this->data['uid'];
			$attribute = 'vk';
			break;
		case(strpos($this->data['provider'], 'facebook.com')!==false):
			$this->data['identity'] = $this->data['uid'];
			$attribute = 'fb';
			break;
		case(strpos($this->data['provider'], 'twitter.com')!==false):
			$this->data['identity'] = $this->data['uid'];
			$attribute = 'tw';
			break;
		case(strpos($this->data['provider'], 'mail.ru')!==false):
			$this->data['identity'] = $this->data['identities'][0];
			$attribute = 'mr';
			break;
		case(strpos($this->data['provider'], 'google')!==false):
			$this->data['identity'] = $this->data['email'];
			$attribute = 'gl';
			break;
		case(strpos($this->data['provider'], 'yandex')!==false):
			$attribute = 'ya';
			break;
		default:
			$attribute = $this->data['provider'];
		}
		
		$this->data['attribute'] = $attribute;
		
		$user = User::model()->findByAttributes(array(
			$this->data['attribute'] => $this->data['identity']
		));
		
		if (!$user)
		{
			Yii::app()->user->setState('social', $this->data);
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		else
		{
			$this->id = $user->primaryKey;
			$this->errorCode = self::ERROR_NONE;
		}
		
		return $this->getIsAuthenticated();
	}
	
	public function getId()
	{
		return $this->id;
	}
}