<?php

class UserIdentity extends CUserIdentity
{
	private $_id;
	
	public function authenticate()
	{
		//if (strpos($this->username,"@")) {
			$user = User::model()->findByAttributes(array('email'=>$this->username));
		//} else {
		//	$user=User::model()->active()->findByAttributes(array('nick_name'=>$this->username));
		//}
		
		if (!$user)
		{
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		else if (!$user->checkPassword($this->password))
		{
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		else
		{
			$this->_id = $user->primaryKey;
			$this->setState('role', $user->role);
			$this->errorCode = self::ERROR_NONE;
		}
		
		return $this->getIsAuthenticated();
	}
	
	public function getId()
	{
		return $this->_id;
	}
}