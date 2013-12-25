<?php

class User extends ActiveRecord
{
	/**
	 * This is the model class for table "user".
	 *
	 * The followings are the available columns in table 'user':
	 * @property integer $id
	 * @property string $email
	 * @property string $first_name
	 * @property string $last_name
	 * @property string $password
	 * @property integer $role
	 * @property string $avatar
	 * @property string $created
	 * @property string $fb
	 * @property string $vk
	 * @property string $mr
	 * @property string $tw
	 *
	 * The followings are the available model relations:
	 * @property Area[] $areas
	 * @property Comment[] $comments
	 * @property IpHistory[] $ipHistories
	 * @property Like[] $likes
	 * @property Note[] $notes
	 * @property Note[] $notes1
	 * @property Request[] $requests
	 * @property Subscription[] $subscriptions
	 * @property Token[] $tokens
	 */
	const ROLE_USER = 0;
	const ROLE_ADMIN = 100;
	const ROLE_BlOCKED = -1;
	
	public $_password;
	public $_password2;

    /**
     * Список существующих ролей
     * @return array
     */
    public static function rolesList() {
        return array(
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_USER => 'Пользователь',
            self::ROLE_BlOCKED => 'Заблокировн',
        );
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, first_name', 'required'),
			array('role, officer_id, avatar', 'numerical', 'integerOnly'=>true),
			array('email, first_name, last_name, password, fb, vk, mr, tw', 'length', 'max'=>255),
			array('email, fb, vk, mr, tw', 'unique'),
			
			array('rating', 'numerical'),
			array('_password,_password2, password', 'length', 'min'=>6),
			array('_password2', 'compare', 'compareAttribute' => '_password'),
			
			array('avatar', 'file', 'types'=>'jpg,jpeg,png,gif', 'maxSize'=>5*1024*1024, 'allowEmpty'=>true), // 5Mb
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, first_name, last_name, password, role, avatar, created, fb, vk, mr, tw', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'areas' => array(self::HAS_MANY, 'Area', 'user_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'author_id'),
			'ipHistories' => array(self::HAS_MANY, 'IpHistory', 'user_id'),
			'likes' => array(self::HAS_MANY, 'Like', 'user_id'),
			'requests' => array(self::HAS_MANY, 'Request', 'author_id'),
			'subscriptions' => array(self::HAS_MANY, 'Subscription', 'user_id'),
			'tokens' => array(self::HAS_MANY, 'Token', 'user_id'),
			// 'badges' => array(self::HAS_MANY, 'UserBadge', 'user_id'),
			'officer' => array(self::BELONGS_TO, 'Officer', 'officer_id'),
			// stat
			'request_count' => array(self::STAT, 'Request', 'author_id'),
			'comment_count' => array(self::STAT, 'Comment', 'author_id'),
		);
	}

	/**
	 * @return array
	 */
	function behaviors()
	{
		return array(
			'eavAttr' => array(
				'class' => 'EEavBehavior',
				'tableName' => '{{user_profile}}',
				'entityField' => 'user_id',
			)
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Эл.почта',
			'first_name' => 'Имя',
			'last_name' => 'Фамилия',
			'password' => 'Хеш пароля',
			'role' => 'Роль',
			'avatar' => 'Аватар',
			'created' => 'Дата создания',
			'fb' => 'Fb',
			'vk' => 'Vk',
			'mr' => 'Mr',
			'tw' => 'Tw',
			'_password' => 'Пароль',
			'_password2' => 'Повтор пароля',
			
			// Профиль
			'eavAttributes[phone]' => 'Телефон',
			'eavAttributes[address]' => 'Адрес',
		);
	}
	
	/**
	 * Имя пользователя
	 * @return string
	 */
	public function getName($officer=true)
	{
		$name = trim($this->first_name.' '.$this->last_name);
		
		if (empty($name))
			$name = $this->login;
		
		// Организация
		if ($officer && $this->officer_id && $this->officer)
			return $name . ' (' . $this->officer->post . ')';
		else
			return $name;
	}
	
	/**
	 * Изменить рейтинг пользователя
	 * @return void
	 */
	public function setRating($rating)
	{
		$this->rating += (float) $rating;
		$this->update(array('rating'));
	}
	
	/**
	 * Получить процентное соотношение от максимального рейтинга пользователя
	 * @return int
	 */
	public function getRating()
	{
		$sql = "SELECT
				((rating * 100) / (SELECT MAX(rating) FROM {$this->tableName()})) as rate
				FROM {$this->tableName()}
				WHERE id = {$this->id}";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query()->read();
		return ceil($result['rate']);
	}
	
	/**
	 * Получить место в рейтинге
	 * @todo: при большем кол-ве данных будет медленно, нужно переделать
	 * @return int
	 */
	public function getPlace()
	{
		$sql = "SELECT *
				FROM (SELECT id, @b:=@b+1 as place FROM (SELECT id FROM {$this->tableName()} ORDER BY rating DESC, id) as g, (SELECT @b:=0) as b) as a
				WHERE id = {$this->id}";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query()->read();
		return $result['place'];
	}
	
	/**
	 * Хеширование пароля (Взято из bitrix при переносе)
	 * @return string
	 */
	public static function hashPassword($password, $salt=null)
	{
		if (!$salt) $salt = Text::random();
		return $salt.md5($salt.$password);
	}
	
	/**
	 * Проверка пароля
	 * @return bool
	 */
	public function checkPassword($password)
	{
		return (bool)($this->password === self::hashPassword($password, $this->salt));
	}
	
	/**
	 * Соль для пароля (первые 8 символов хеша)
	 * @return string
	 */
	public function getSalt()
	{
		return substr($this->password,0,8);
	}
	
	/**
	 * Заглушка
	 */
	public function getBadges()
	{
		return array();
	}
	
	/**
	 * Хранилище файлов юзеров
	 * @return StorageComponent
	 */
	public function getStorage()
	{
		if (!empty(Yii::app()->storage))
		{
			return Yii::app()->storage->owner($this)->scheme(new StorageSchemeDynamic);
		}
	}
	
	/**
	 * Аватар
	 * @return string
	 */
	private $_avatar;
	public function getAvatar($htmlOptions=array())
	{
		if ($this->_avatar===null && $this->avatar)
			$this->_avatar = Yii::app()->storage->get($this->avatar);
		if ($this->_avatar)
			return CHtml::image($this->_avatar->baseUrl, null, $htmlOptions);
		else
			return CHtml::image(Yii::app()->theme->baseUrl . '/images/nophoto.gif', null, $htmlOptions);
	}
	
	/**
	 * Удалить аватар
	 * @return void
	 */
	public function deleteAvatar($htmlOptions=array())
	{
		$this->storage->delete($this->avatar);
		$this->_avatar=null;
		$this->avatar=null;
	}
	
	/**
	 * BeforeSave event
	 * @return void
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			if (!$this->role)
				$this->role = self::ROLE_USER;
			if (!$this->created)
				$this->created = date('Y-m-d H:i:s');
		}
		
		if (!empty($this->_password))
			$this->password = self::hashPassword($this->_password);
		
		return parent::beforeSave();
	}
	
	/**
	 * BeforeSave event
	 * @return void
	 */
	public function afterSave()
	{
		parent::afterSave();
		
		// Загрузка аватара
		if ($this->storage && $instance = CUploadedFile::getInstance($this, 'avatar'))
		{
			if ($file = $this->storage->store($instance))
			{
				// Обработка файла
				$image = Yii::app()->image->load($file->basePath);
				$image->resize(100, '100%');
				$image->crop(100, 100);
				$image->save();
				
				// Удалить старый файл
				$this->deleteAvatar();
				
				$this->avatar = $file->id;
				$this->update(array('avatar'));
			}
		}
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('fb',$this->fb,true);
		$criteria->compare('vk',$this->vk,true);
		$criteria->compare('mr',$this->mr,true);
		$criteria->compare('tw',$this->tw,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
}