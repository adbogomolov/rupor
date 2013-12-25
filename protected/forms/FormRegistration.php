<?php

class FormRegistration extends CFormModel
{
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $password2;
    public $verifyCode;
    public $rules;
    
    public function rules()
    {
        $module = Yii::app()->getModule('user');
        return array(
			array('email, first_name, last_name', 'filter', 'filter'=>'trim'),
            array('email, first_name, last_name', 'required'),
            array('rules', 'required', 'requiredValue'=>1, 'message'=>'Вы должны согласится с условиями'),
            array('password, password2', 'required'),
			array('password, password2', 'length', 'min'=>6),
			array('password', 'compare', 'compareAttribute'=>'password2'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'User'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!$this->showCaptcha()),
        );
    }
	
    public function attributeLabels()
    {
        return array(
            'first_name'	=> 'Имя',
            'last_name'		=> 'Фамилия',
            'email'			=> 'Эл. почта',
            'password'		=> 'Пароль',
            'password2'		=> 'Пароль еще раз',
            'verifyCode'	=> 'Код проверки',
        );
    }
	
    public function showCaptcha()
	{
		return false;
	}
	
    public function save($validate=true)
    {
		if ($validate && !$this->validate())
			return false;
		
        $user = new User();
        $user->setAttributes($this->attributes);
        $user->password = User::hashPassword($this->password);
		
        if ($user->save())
		{
			Mail::send('registration', $this->email, $this->attributes);
        }
       
		return !$user->errors;
	}
}