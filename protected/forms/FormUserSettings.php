<?php

class FormUserSettings extends CFormModel
{
	// base info
    public $first_name;
    public $last_name;
    public $middle_name;
    public $email;
    public $avatar;
    public $delete_avatar;
	
	// contact info
    public $phone;
    public $address;
	
	// password
    public $password;
    public $password2;
    
    public function rules()
    {
		$user = $this->getUser();
		
        return array(
			array('email, first_name, last_name', 'filter', 'filter'=>'trim'),
            array('email, first_name, last_name', 'required'),
            array('delete_avatar', 'numerical', 'integerOnly'=>true),
			array('password, password2', 'length', 'min'=>6),
			array('password2', 'compare', 'compareAttribute'=>'password'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'User', 'criteria'=>array(
				'condition'=>'email!="'.$user->email.'"'
			)),
			array('middle_name, phone, address', 'safe'),
			array('avatar', 'file', 'types'=>'jpg,jpeg,png,gif', 'maxSize'=>5*1024*1024, 'allowEmpty'=>true), // 5Mb
        );
    }
	
    public function attributeLabels()
    {
        return array(
            'first_name'	=> 'Имя',
            'last_name'		=> 'Фамилия',
            'middle_name'	=> 'Отчество',
            'phone'			=> 'Телефон',
            'address'		=> 'Адрес',
            'email'			=> 'Эл. почта',
            'avatar'		=> 'Аватар',
            'password'		=> 'Новый пароль',
            'password2'		=> 'Новый пароль еще раз',       
        );
    }
	
	/**
	 * Check captcha enabled
	 * @return bool
	 */
    public function showCaptcha()
	{
		return false;
	}
	
	/**
	 * Get user
	 * @return User object
	 */
    public function getUser()
	{
		if (!Yii::app()->user->isAuthenticated())
			throw new CHttpException(404);
		
		$user = Yii::app()->user->getModel();
		if (!$user)
			throw new CHttpException(404);
		
		return $user;
	}
	
	/**
	 * Save form
	 * @return void
	 */
    public function save($validate=true)
    {
		if ($validate && !$this->validate())
			return false;
		
		$user = $this->getUser();
		$data = (object) $this->attributes;
		
		// загрузка аватара
		$this->avatar = CUploadedFile::getInstance($this, 'avatar');
		$file = Yii::app()->storage->store($this, 'avatar');
		
		print_r($file);
		exit;
		
		// удалить старую
		if (($file||$this->delete_avatar) && $user->avatar)
		{
			@unlink(ROOT_PATH . Yii::getPathOfAlias('files_user'). DIRECTORY_SEPARATOR . $user->avatar);
			$user->avatar = NULL;
		}
		
		// загрузить новую
		if ($file)
		{
			$file_name = $user->id .'.'. strtolower($file->extensionName);
			$file_path = ROOT_PATH . Yii::getPathOfAlias('files_user') . DIRECTORY_SEPARATOR . $file_name;
			if ($file->saveAs($file_path))
			{
				$image = Yii::app()->image->load($file_path);
				$image->resize(100, '100%');
				$image->crop(100, 100);
				$image->save();
				$user->avatar = $file_name;
			}
		}
		
		// изменение пароля
		if (!empty($this->password))
		{
			$data->password = User::hashPassword($this->password);
		}
		else
		{
			unset($data->password);
		}
		
		$result = UserAdmin::editProfile($user, $data);
		return !$result->error;
	}
}