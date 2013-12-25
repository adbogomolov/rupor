<?php

/**
 * This is the model class for table "officer_request".
 *
 * The followings are the available columns in table 'officer_request':
 * @property integer $id
 * @property integer $officer_id
 * @property integer $request_id
 *
 * The followings are the available model relations:
 * @property Request $request
 * @property Officer $officer
 */
class OfficerRequest extends CActiveRecord
{
	public $form;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OfficerRequest the static model class
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
		return '{{officer_request}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('officer_id, request_id', 'required'),
			array('officer_id, request_id, statement', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, officer_id, request_id, view_date, created', 'safe', 'on'=>'search'),
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
			'request' => array(self::BELONGS_TO, 'Request', 'request_id'),
			'officer' => array(self::BELONGS_TO, 'Officer', 'officer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'officer_id' => 'Officer',
			'request_id' => 'Request',
		);
	}

	/**
	 * Хеш заявления
	 * @return void
	 */
	public function hash()
	{
		return sha1($this->created . Yii::app()->name);
	}

	/**
	 * Ссылка принятия заявления
	 * @return void
	 */
	public function acceptUrl()
	{
		return Yii::app()->createAbsoluteUrl('/request/accept', array(
			'id'=>$this->primaryKey,
			'hash'=>$this->hash()
		));
	}

	/**
	 * @return void
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->created = date('Y-m-d H:i:s');
		}

		return parent::beforeSave();
	}

	/**
	 * @return void
	 */
	public function afterSave()
	{
		if ($this->isNewRecord && $this->officer && $this->request)
		{
			$options = array();

			$data = array(
				'template' => '', // Шаблон заявления
				'accept_url' => $this->acceptUrl(),
				'officer_post' => $this->officer->post,
				'officer_days' => $this->officer->days_count,
				'officer_address' => $this->officer->address,
				'request_address' => $this->request->address,
				'request_url' => Yii::app()->createAbsoluteUrl($this->request->getHref()),
				'body' => $this->request->description,
				'user_name' => '',
				'user_email' => '',
				'user_address' => '',
				'user_phone' => '',
			);

			// Данные пользователя
			if (!Yii::app()->user->isGuest)
			{
				$user = Yii::app()->user->model;
				$data['user_name'] = $user->getName();
				$data['user_email'] = $user->email;
				$data['user_address'] = $user->getEavAttribute('address');
				$data['user_phone'] = $user->getEavAttribute('phone');
				$options['From'] = '=?UTF-8?B?'. base64_encode($user->getName()) .'?= <'.$user->email.'>';
			}

			// TODO hack сделано для случая когда фио и емайл или адрес приходит с формы в виджте
			//
			if(!empty($_POST['widget_data']))
			{
				$data['user_name'] = $_POST['widget_data']['full_name'];

				if(filter_var($_POST['widget_data']['address_or_email'], FILTER_VALIDATE_EMAIL))
				{
					$data['user_email'] = $_POST['widget_data']['address_or_email'];
				}
				else
				{
					$data['user_email'] = $_POST['widget_data']['address_or_email'];
				}
			}

			// Шаблон заявления
			if ($this->request->tagRequests)
			{
				foreach($this->request->tagRequests as $obj)
				{
					if ($obj->tag && !empty($obj->tag->statement))
					{
						$data['template'] = $obj->tag->statement;
						break;
					}
				}
			}

			// Отправка заявления через форму
			if ($this->form)
			{
				if (!empty($this->form->name)) $data['user_name'] = $this->form->name;
				if (!empty($this->form->email)) $data['user_email'] = $this->form->email;
				if (!empty($this->form->address)) $data['user_address'] = $this->form->address;
				if (!empty($this->form->phone)) $data['user_phone'] = $this->form->phone;
				if (!empty($this->form->body)) $data['body'] = $this->form->body;
			}

			// Отправка на почту
			Mail::send('request_statement', $this->officer->email, $data, $options);

			// Добавление записи в ленту активности
			if ($this->statement)
			{
				Feed::add($this->request_id, Feed::EVENT_ADD_STATEMENT, $this->id);
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
		$criteria->compare('officer_id',$this->officer_id);
		$criteria->compare('request_id',$this->request_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}