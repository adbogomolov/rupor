<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property string $title
 * @property integer $author_id
 * @property string $description
 * @property string $video
 * @property integer $status
 * @property string $address
 * @property double $lat
 * @property double $lng
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Feed[] $feeds
 * @property Like[] $likes
 * @property Notification[] $notifications
 * @property OfficerRequest[] $officerRequests
 * @property User $author
 * @property Officer $officer
 * @property RequestFile[] $requestFiles
 * @property Subscription[] $subscriptions
 * @property TagRequest[] $tagRequests
 */
class Request extends ActiveRecord
{
	const TYPE_REQUEST = 0;
	const TYPE_POST = 1;

	const STATUS_SOLVED = 1;
	const STATUS_UNSOLVED = 2;
	const STATUS_INPROGRESS = 3;

	// Для поиска по нескольким аттрибутам
	public $query;

	// Загрузка файла для проблемы / поле нужно для валидации
	public $file;

	private $_files;

	public static function typeList()
	{
		return array(
			self::TYPE_REQUEST => 'Проблема',
			self::TYPE_POST => 'Новость',
		);
	}

	public static function statusList()
	{
		return array(
			self::STATUS_SOLVED => 'Решена',
			self::STATUS_UNSOLVED => 'Не решена',
			self::STATUS_INPROGRESS => 'В работе',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Request the static model class
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
		return '{{request}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, address, lat, lng', 'required'),
			array('author_id, status, visible, type', 'numerical', 'integerOnly'=>true),
			array('lat, lng', 'numerical'),
			array('title, address', 'length', 'max'=>255),
			array('video', 'filter', 'filter'=>'trim'),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, author_id, description, status, address, lat, lng, created', 'safe', 'on'=>'search'),
			// Файл для проблемы
			array('file', 'file', 'maxSize'=>5*1024*1024, 'types'=>'jpg,jpeg,png,gif', 'allowEmpty'=>true)
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
			'comments' => array(self::HAS_MANY, 'Comment', 'request_id'),
			'comments_count' => array(self::STAT, 'Comment', 'request_id'),
			'feed' => array(self::HAS_MANY, 'Feed', 'request_id'),
			'likes' => array(self::HAS_MANY, 'Like', 'request_id'),
			'likes_count' => array(self::STAT, 'Like', 'request_id'),
			// 'notifications' => array(self::HAS_MANY, 'Notification', 'request_id'),
			'officers' => array(self::HAS_MANY, 'OfficerRequest', 'request_id'),
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'subscriptions' => array(self::HAS_MANY, 'Subscription', 'request_id'),
			'tagRequests' => array(self::HAS_MANY, 'TagRequest', 'request_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Тип',
			'title' => 'Заголовок',
			'author_id' => 'Автор',
			'description' => 'Описание',
			'status' => 'Статус',
			'address' => 'Адрес',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'created' => 'Дата добавления',
		);
	}

    public function defaultScope()
    {
        return array(
            'order' => 'created DESC',
        );
    }

	/**
	 * Геокодирования Yandex
	 * @return string
	 */
	public static function geocode($str)
	{
		$params = array(
			'text' => $str,
			'output'  => 'json',
			'source'  => 'form',
			'z' => 17,
		);
		$ch = curl_init('http://maps.yandex.ua/?' . http_build_query($params, '', '&'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
			'X-Requested-With: XMLHttpRequest',
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$response = CJSON::decode($response);

		VarDumper::dump($response,40);
		exit;

		if (!empty($response['vpage']['data']['locations']) && $response['vpage']['data']['locations']['found'] > 0)
		{
			// VarDumper::dump($response['vpage']['data']['locations']['GeoObjectCollection']['features'][0]['properties'], 10);
			// exit;
			return $response['vpage']['data']['locations']['GeoObjectCollection']['features'][0]['properties']['GeocoderMetaData']['text'];
		}
	}

	/**
	 * Статус подписки
	 * @return bool
	 */
	public function subscribed()
	{
		if (Yii::app()->user->isGuest)
			return;
		$criteria = new CDbCriteria();
		$criteria->compare('request_id', $this->id);
		$criteria->compare('user_id', Yii::app()->user->id);
		return Subscription::model()->exists($criteria);
	}

	/**
	 * Подписка
	 * @return bool
	 */
	public function subscribe()
	{
		if (Yii::app()->user->isGuest)
			return;

		$criteria = new CDbCriteria();
		$criteria->compare('request_id', $this->id);
		$criteria->compare('user_id', Yii::app()->user->id);

		$model = Subscription::model()->find($criteria);

		// подписатся
		if (!$model)
		{
			$model = new Subscription();
			$model->request_id = $this->id;
			$model->user_id = Yii::app()->user->id;
			if ($model->save())
			{
				return 1;
			}
		}
		// отписатся
		elseif ($model->delete())
		{
			return -1;
		}
	}

	/**
	 * Лайк проблемы
	 * @return bool
	 */
	public function like($uid=null)
	{
		if (Yii::app()->user->isGuest)
			return;
		if (!$uid)
			$uid = Yii::app()->user->id;

		$criteria = new CDbCriteria();
		$criteria->compare('request_id', $this->id);
		$criteria->compare('user_id', $uid);

		if (!Like::model()->exists($criteria))
		{
			$like = new Like();
			$like->request_id = $this->id;
			$like->user_id = $uid;
			if ($like->save())
			{
				return true;
			}
		}
	}

	/**
	 * Название статуса
	 * @return string
	 */
	public function getStatusName()
	{
		$list = self::statusList();
		return isset($list[$this->status]) ? $list[$this->status] : '?';
	}

	/**
	 * Лейбл статуса Название статуса
	 * @return string
	 */
	public function getStatusLabel()
	{
		switch($this->status){
			case(self::STATUS_SOLVED):
				return '<span class="label label-success">Решена</span>';
			case(self::STATUS_UNSOLVED):
				return '<span class="label label-important">Не решена</span>';
			case(self::STATUS_INPROGRESS):
				return '<span class="label label-warning">В работе</span>';
		}
	}

	/**
	 * Ссылка на проблему
	 * @return string
	 */
	public function getHref(array $config=array())
	{
		return Yii::app()->createUrl('/request/view', array('id'=>$this->id));
	}

	/**
	 * Получить список вики данных
	 * @return array
	 */
	public function getWikies()
	{
		return WikiAdmin::selectForRequest($this->id);
	}

	/**
	 * Получить список файлов
	 * @return array
	 */
	public function getFiles($condition='', $params=array())
	{
		if (!empty($this->storage))
		{
			$items = $this->storage->model->byOwner($this)->findAll($condition, $params);
			if ($this->_files===null && empty($condition))
				$this->_files = $items;
			else
				return $items;
		}
		return $this->_files;
	}

	/**
	 * Получить список видео
	 * @return array
	 */
	public function getVideos()
	{
		$videos = array();
		if ($this->video)
		{
			foreach(explode("\n", $this->video) as $id)
			{
				$videos[] = (object) array(
					'id' => $id,
					'baseUrl' => 'http://img.youtube.com/vi/'. $id .'/0.jpg',
					'thumbUrl' => 'http://img.youtube.com/vi/'. $id .'/default.jpg',
					'embed' => 'http://www.youtube.com/embed/' . $id,
				);
			}
		}
		return $videos;
	}


	public function getTagsAsArray()
	{
		$tags = array();
		foreach($this->tagRequests as $obj)
		{
			$tags[$obj->tag_id] = $obj->tag->name;
		}


		return $tags;
	}

	/**
	 * Получить список ответственных
	 * @return array
	 */
	public function getAllOfficers()
	{
		$tags = array();
		foreach($this->tagRequests as $obj)
		{
			$tags[] = $obj->tag_id;
		}

		$officers = Officer::model()
			->byLocation($this->address, array($this->lat, $this->lng))
			->findAll('email!="" AND global = 0');

		$result = array();
		foreach($officers as $officer)
		{
			$valid = false;

			// TODO: Оптимизировать
			if (!$officer->officerTags)
			{
				$valid = true;
			}
			else
			{
				if (empty($tags) || empty($tags[0]))
					continue;
				foreach($officer->officerTags as $tag)
				{
					if (in_array($tag->tag_id, $tags))
					{
						$valid = true;
						break;
					}
				}
			}

			if (!$valid)
				continue;

			$result[$officer->id] = $officer;
		}
		return $result;
	}

	/**
	 * @return void
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			if (empty($this->created) || $this->created == '0000-00-00 00:00:00')
				$this->created = date('Y-m-d H:i:s');
			// Дата последней активности = дате создания
			$this->lastactive = $this->created;
			// Если новая запись, автор текущий авторизирвоаный пользователь
			if ($this->author_id==null)
				$this->author_id = Yii::app()->user->id;
			// Если не указан статус, по умолчанию "Не решена"
			if ($this->status == null)
				$this->status = self::STATUS_UNSOLVED;
		}

		return parent::beforeSave();
	}

	/**
	 * @return void
	 */
	public function afterSave()
	{
		if ($this->isNewRecord)
		{
			Feed::add($this->id, Feed::EVENT_START_REQUEST);
			if ($this->author)
			{
				// Увеличить рейтинг автора
				$this->author->setRating(3);
			}
		}
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function feed()
	{
		$criteria=new CDbCriteria;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'lastactive DESC',
            ),
		));
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
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('lng',$this->lng);
		$criteria->compare('created',$this->created,true);

		if ($this->query)
		{
			$criteria->addSearchCondition('title',$this->query);
			$criteria->addSearchCondition('description',$this->query,true,'OR');
			$criteria->addSearchCondition('address',$this->query,true,'OR');
		}

		$data = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));

		return $data;
	}

	/**
	 * Функция для построения полного адреса черз обратное геокодирование
	 * @param array $params
	 */
	public static function buildAddress($params)
	{
		$params = (object)$params;
		$center = $params->center;

		if(!is_array($center))
		{
			$center = explode(',', $center);
		}

		// Пыть-ях не возращает area нюфтеганский район, и для других такая же проблема может быть
		// немного смешаем координату чтоб вернуло какую то улицу или дом и там будет уже район
		//
		if(!empty($params->kind) && $params->kind == 'locality')
		{
			$center[0] += 0.005;
		}

		$request = array(
				'geocode' => implode(',', $center),
				'format'  => 'json',
				'sco' 	  => 'latlong',
				//'kind'	  => 'house'
		);

		$response = Yii::app()->curl->get('http://geocode-maps.yandex.ru/1.x/', $request);

		if(!$response)
		{
			return false;
		}

		$response = json_decode($response);

		if(!$response)
		{
			return false;
		}

		$items = array();
		$names = array();


		// В этих область встречаеться 2 значность, тоесть абривиатура то нету,
		// приводим все к 1 виду
		//
		$provinceFix = array(
			'Ханты-Мансийский автономный округ - Югра' => 'ХМАО',
			'Ханты-Мансийский автономный округ' => 'ХМАО',
			'Ямало-Ненецкий автономный окру' => 'ЯНАО'
		);

		$yandexVariant = false;
		foreach($response->response->GeoObjectCollection->featureMember as $r)
		{
			$kind = $r->GeoObject->metaDataProperty->GeocoderMetaData->kind;
			$name = $r->GeoObject->name;

			if(empty($yandexVariant))
			{
				$yandexVariant = $r->GeoObject->metaDataProperty->GeocoderMetaData->text;
			}

			if($kind == 'province')
			{
				$name = strtr($name, $provinceFix);
			}

			//var_dump($r->GeoObject->metaDataProperty->GeocoderMetaData);
			//echo($name . ':' . $kind . ',');

			$items[] = (object)array('kind' => $kind, 'name' => $name);
		}

		$main = $items[0];
		$items = array_reverse($items);
		$houseDetected= false;


		// Присутсвует ли дом в списке
		//
		foreach($items as $item)
		{
			if($item->kind == 'house')
			{
				$houseDetected = 1;
			}
		}

		foreach($items as $item)
		{
			if($item->kind == 'area')
			{
				// Если тип ареа и его нет в варианте гекодирования от  яндекса
				//
				if(stripos($yandexVariant, $item->name) === false && $params->kind != 'area')
				{
					continue;
				}
			}


			// Если уже был найден конкретный дом в его имени есть уже улица
			//
			if($item->kind == 'street' && $houseDetected)
			{
				continue;
			}

			$names[$item->kind] = $item->name;

			// Имя или кинд надо для того чтоб обрезать результат геокодирование идет вплоть аж до улицы, а например надо только сургут
			// используеться в пикере городов на главной
			//
			if(!empty($params->name))
			{
				if($item->name == $params->name)
				{
					break;
				}
			}
			// If kind was send this kind is last
			//
			elseif($params->kind && $item->kind == $params->kind)
			{
				break;
			}
		}

		$names = array_unique($names);

		//echo implode(', ', $names);

		return (object) array(
			'main_item' => $main,
			'address' => implode(', ', $names),
			'parts' => $items
		);
	}
}