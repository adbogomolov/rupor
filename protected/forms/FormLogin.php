<?php
/**
 *  @method EForm getForm()
 */
class FormLogin extends CFormModel
{
	const REMEMBER_DURATION = 604800;
	
    public $email;
    public $password;
    public $remember = true;

    public function rules()
    {
        return array(
            array('email, password', 'required'),            
            array('email', 'email'),
			array('remember', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'email'   => 'Эл. почта',
            'password'=> 'Пароль',
            'remember'=> 'Запомнить меня?',
        );
    }
	
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors())
        {
			$identity = new UserIdentity($this->email, $this->password);
			$identity->authenticate();
			
			switch($identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					Yii::app()->user->login($identity, $this->remember ? self::REMEMBER_DURATION : 0);
					break;
				case UserIdentity::ERROR_UNKNOWN_IDENTITY:
					$this->addError('password', 'Вы ввели не правильные данные');
					break;
			}
        }
    }
}