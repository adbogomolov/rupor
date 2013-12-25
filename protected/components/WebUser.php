<?php

class WebUser extends CWebUser
{
	public $afterLoginUrl = '/';
	public $loginRequiredAjaxResponse = 'Необходима авторизация';
	
	protected $_model;
	
	public function init()
	{
		parent::init();
		
		if (!$this->isGuest)
		{
			$user = $this->getModel();
			
			if (!$user) $this->logout();
			
			// Добавление организации
			if ($this->hasState('officer'))
			{
				$officer = $this->getState('officer');
				$this->setState('officer', null);
				Yii::app()->controller->redirect($officer['returnUrl']);
			}
			
			// loginza закрепление соц. аккаунта
			if ($this->hasState('social'))
			{
				$social = $this->getState('social');
				if ($user->hasAttribute($social['attribute']))
				{
					$user->setAttribute($social['attribute'], $social['identity']);
					$user->update(array($social['attribute']));
				}
				$this->setState('social', null);
			}
		}
	}
	
	/**
	 * Проверка доступа
	 * @return boolean
	 */
    public function checkAccess($operation, $params=array())
    {
		switch($operation){
			case('changestatus'):
				if (!$this->isGuest)
				{
					if (!empty($params['status']))
					{
						$user = $this->getModel();
						switch($params['status']){
						case(Request::STATUS_INPROGRESS):
							return !empty($user->officer_id); // Только интстанции могут поставить статус "в работе"
							break;
						case(Request::STATUS_UNSOLVED):
							return empty($user->officer_id); // Только пользователи могут поставить статус "не решена"
							break;
						}
					}
					return true;
				}
			break;
		}
		
		return parent::checkAccess($operation, $params);
    }
	
	/**
	 * Роль пользователя
	 * @return string
	 */
	public function getRole()
	{
        if ($user = $this->getModel())
		{
            return $user->role;
        }
    }
	
	/**
	 * Модель пользователя
	 * @return User model
	 */
	public function getModel($force=false)
	{
		if ($this->isAuthenticated() && ($this->_model === null||$force))
			$this->_model = User::model()->findByPk($this->id);
        return $this->_model;
	}
	
	/**
	 * Get real user ip address
	 * @return string as real client ip address, etc. 127.0.0.1
	 */
    public function getRealIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
			return $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			return $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	 * Get user authenticated status
	 * @return bool
	 */
    public function isAuthenticated()
    {
        return !$this->getIsGuest();
    }
}