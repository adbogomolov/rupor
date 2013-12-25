<?php

class FormRecovery extends CFormModel
{
    public $email;
    public $verifyCode;
    public $password;
    public $password2;
    public $user; // User object
	
    public function rules()
    {
        return array(
            array('email', 'required', 'except'=>'complete'),
            array('email', 'email', 'except'=>'complete'),
            array('email', 'validate_email', 'except'=>'complete'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!$this->showCaptcha()),
            array('password, password2', 'required', 'on'=>'complete'),
			array('password, password2', 'length', 'min'=>6),
			array('password2', 'compare', 'compareAttribute'=>'password'),
        );
    }
	
    public function attributeLabels()
    {
        return array(
            'email' => 'Эл. почта',
            'password' => 'Новый пароль',
            'password2' => 'Повтор пароля',
            'verifyCode' => 'Код проверки',
        );
    }
	
	/**
	 * Отобразить каптчу?
	 */ 
    public function showCaptcha()
	{
		return 0;
	}
	
	/**
	 * Проверка адреса
	 */ 
    public function validate_email($attribute, $params)
    {
    	if (!$this->hasErrors())
    	{
			$this->user = User::model()->find('email = :email', array(':email' => $this->email));
    		if (!$this->user)
			{
    		    $this->addError('email', 'Эл. почта не найдена.');
			}
    	}
    }
	
	/**
	 * Отправка сообщения
	 */ 
    public function save($validate=true)
    {
		if ($validate && !$this->validate())
			return false;
		
		if ($this->scenario == 'complete')
		{
			$this->user->password = User::hashPassword($this->password);
			$this->user->recovery = null;
			$this->user->update(array('password', 'recovery'));
			return true;
		}
		
		// random hash by email
		$hash = sha1($this->email . uniqid());
		
		$this->user->recovery = $hash;
		$this->user->update(array('recovery'));
		
		return Mail::send('recovery', $this->email, array(
			'recoveryurl' => Yii::app()->createAbsoluteUrl('/user/recovery', array('hash'=> $hash))
		));
    }
}