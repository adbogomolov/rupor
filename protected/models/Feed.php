<?php

/**
 * This is the model class for table "feed".
 * 
 * The followings are the available columns in table 'feed':
 * @property integer $id
 * @property integer $request_id
 * @property integer $user_id
 * @property integer $event
 * @property integer $data
 * @property string $created
 * 
 * The followings are the available model relations:
 * @property Request $request
 */
class Feed extends CActiveRecord
{
	const EVENT_START_REQUEST = 0;
	const EVENT_CHANGE_STATUS = 1;
	const EVENT_ADD_COMMENT = 2;
	const EVENT_ADD_STATEMENT = 3;
	
	public static function eventList()
	{
		return array(
			self::EVENT_START_REQUEST => 'Добавил проблему',
			self::EVENT_CHANGE_STATUS => 'Изменил статус',
			self::EVENT_ADD_COMMENT => 'Добавил комментарий',
			self::EVENT_ADD_STATEMENT => 'Добавил заявление',
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Feed the static model class
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
		return '{{feed}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_id', 'required'),
			array('request_id, user_id, event, data', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, request_id, event, data, created', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'request_id' => 'Проблема',
			'event' => 'Событие',
			'data' => 'Внешний ID',
			'created' => 'Дата',
		);
	}
	
	/**
	 * Добавление записи в активность
	 * @return bool
	 */
	public static function add($request_id, $event, $data=null)
	{
		$events = self::eventList();
		if (!$events[$event]) return;
		
		$model = new Feed;
		$model->request_id = $request_id;
		$model->event = $event;
		$model->data = $data;
		
		return $model->save();
	}
	
	/**
	 * Пользователь который совершил действие
	 * @return string
	 */
	public function user()
	{
		if ($this->user_id)
		{
			$model = User::model()->findByPk($this->user_id);
			if ($model) return $model->getName();
		}
		return 'Аноним';
	}
	
	/**
	 * Текст действия
	 * @return string
	 */
	public function event()
	{
		$events = self::eventList();
		$text = isset($events[$this->event]) ? $events[$this->event] : '';
		
		switch($this->event){
		case(self::EVENT_CHANGE_STATUS):
			$statuses = Request::statusList();
			$status = isset($statuses[$this->data]) ? $statuses[$this->data] : '';
			$text .= ' на "'. $status .'"';
			break;
		case(self::EVENT_ADD_STATEMENT):
			$officerRequest = OfficerRequest::model()->findByPk($this->data);
			if ($officerRequest)
			{
				$officer = Officer::model()->findByPk($officerRequest->officer_id);
				if ($officer)
					$text = 'Отправил(а) заявление: "'. $officer->post .'"';
			}
			break;
		}
		
		return $text;
	}
	
	/**
	 * @return void
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->user_id = Yii::app()->user->id;
			$this->created = date('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}
	
	/**
	 * Текст активности
	 * @return string
	 */
	public function toString($short=false)
	{
		$username = $this->user ? $this->user->getName() : 'Аноним';
		
		if ($short)
			$request = false;
		else
			$request = $this->request ? CHtml::link($this->request->title, $this->request->getHref()): '?';
		
		switch($this->event){
		
		case(self::EVENT_START_REQUEST):
			if ($short)
				return "{$username} добавил(а) проблему";
			return "{$username} добавил(а) новую проблему &laquo;{$request}&raquo;";
			break;
			
		case(self::EVENT_CHANGE_STATUS):
			$statuses = Request::statusList();
			$status = isset($statuses[$this->data]) ? $statuses[$this->data] : '';
			if ($request)
				return "{$username} изменил(а) статус проблемы &laquo;{$request}&raquo; на &laquo;{$status}&raquo;";
			else
				return "{$username} изменил(а) статус на &laquo;{$status}&raquo;";
			break;
			
		case(self::EVENT_ADD_COMMENT):
			if (!$request)
				return "{$username} добавил(а) новый комментарий";
			$comment = Comment::model()->findByPk($this->data);
			$message = $comment ? $comment->message : '';
			return "{$username} добавил(а) новый комментарий &laquo;{$message}&raquo; для проблемы &laquo;{$request}&raquo;";
			break;
			
		case(self::EVENT_ADD_STATEMENT):
			if (!$request)
				return "{$username} отправил(а) заявление";
			$officerRequest = OfficerRequest::model()->findByPk($this->data);
			if ($officerRequest)
			{
				$officer = Officer::model()->findByPk($officerRequest->officer_id);
				return "{$username} отправил(а) заявление для проблемы &laquo;{$request}&raquo;. Получатель: &laquo;{$officer->post}&raquo;";
			}
			break;
		
		}
	}
	
	/**
	 * @return void
	 */
	public function afterSave()
	{
		if ($this->isNewRecord)
		{
			if ($this->request)
			{
				// изменить дату последней активности проблемы
				$this->request->lastactive = date('Y-m-d H:i:s');
				$this->request->update(array('lastactive'));
				
				// отправка уведомлений подписчикам
				if ($this->request->subscriptions)
				{
					foreach($this->request->subscriptions as $subscription)
					{
						// самому себе не отправлять
						if ($this->user_id == $subscription->user_id)
							continue;
						
						$user = User::model()->findByPk($subscription->user_id);
						if (!$user) continue;
						
						Mail::send('request_event', $user->email, array(
							'text' => $this->toString(),
							'user_name' => $user->getName(),
							'request_title' => $this->request->title,
							'request_url' => Yii::app()->createAbsoluteUrl($this->request->getHref()),
							'request_address' => $this->request->address,
						));
					}
				}
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('request_id',$this->request_id);
		$criteria->compare('event',$this->event);
		$criteria->compare('data',$this->data);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}
}