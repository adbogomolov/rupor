<?php

/**
 * This is the model class for table "officer".
 *
 * The followings are the available columns in table 'officer':
 * @property integer $id
 * @property string $fullname
 * @property string $post
 * @property string $email
 * @property string $created
 *
 * The followings are the available model relations:
 * @property OfficerLayer[] $officerLayers
 * @property OfficerRequest[] $officerRequests
 * @property OfficerTag[] $officerTags
 * @property Request[] $requests
 */
class Officer extends CActiveRecord
{
	public $count;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Officer the static model class
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
		return '{{officer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post', 'required'),
			array('fullname, post, email, address', 'length', 'max'=>255),
			array('days_count', 'numerical', 'integerOnly'=>true),
			array('location', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fullname, post, email, created', 'safe', 'on'=>'search'),
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
			'officerLayers' => array(self::HAS_MANY, 'OfficerLayer', 'officer_id'),
			'officerRequests' => array(self::HAS_MANY, 'OfficerRequest', 'officer_id'),
			'officerTags' => array(self::HAS_MANY, 'OfficerTag', 'officer_id'),
			'requests' => array(self::HAS_MANY, 'Request', 'officer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fullname' => 'ФИО',
			'post' => 'Должность',
			'email' => 'Эл. адрес',
			'address' => 'Адрес',
			'location' => 'Область',
			'created' => 'Добавлено',
		);
	}
	
	/**
	 * Scope: Поиск по теме
	 * 
	 * @param array $tags IDs тегов
	 * @return this
	 */
	public function byTags($tags, $operator='AND')
	{
		$criteria = new CDbCriteria;
		$criteria->with = 'officerTags';
		$criteria->addInCondition('officerTags.tag_id', $tags);
		
		$this->getDbCriteria()->mergeWith($criteria, $operator);
	}
	
	/**
	 * Scope: Поиск по локации
	 * 
	 * @param string $address адрес
	 * @param array $point координаты точки
	 * @return this
	 */
	public function byLocation($address, array $point=array(), $operator='AND')
	{
		$criteria = new CDbCriteria;
		
		// поиск по адресу
		$address = explode(',', $address);
		$i=0;
		while($address)
		{
			$criteria->condition .= ' OR address = :addr' . $i;
			$criteria->params[':addr'.$i] = implode(',', $address);
			array_pop($address);
			$i++;
		}
		$criteria->condition = ltrim($criteria->condition, ' OR ');
		
		// поиск вхождения точки в область
		if ($point && count($point) == 2)
		{
			$criteria->addCondition(new CDbExpression("IF(!ISNULL(location), CONTAINS(location, POINTFROMTEXT('POINT(". implode(' ', $point) .")')), 1)"));
		}
		
		$this->getDbCriteria()->mergeWith($criteria, $operator);
		
		return $this;
	}
	
	/**
	 * Получить массив вершин полигона
	 * @return array
	 */
	public function getPolygon()
	{
		if (empty($this->location))
			return array();
		
		$mem = fopen('php://memory', 'r+');
		fwrite($mem, $this->location);
		fseek($mem, 4);
		
		$data = unpack('cbyte_order', fread($mem,1));
		$byte_order = $data['byte_order'];
		
		$data['type'] = current(unpack($byte_order?'V':'N', fread($mem,4)));
		$data['count'] = current(unpack($byte_order?'V':'N', fread($mem,4)));
		$data['polygons'] = array();
		
		for ($i=0;$i<$data['count'];$i++)
		{
			$data['polygons'][$i] = array();
			// count lines
			$count = current(unpack($byte_order?'V':'N', fread($mem,4)));
			for ($k=0;$k<$count;$k++) {
				$point = unpack('d*', fread($mem,16));
				$data['polygons'][$i][] = array_values($point);
			}
		}
		
		fclose($mem);
		return $data['polygons'];
	}
	
	/**
	 * @return void
	 */
	protected function afterFind()
	{
		$this->location = CJSON::encode($this->getPolygon());
		parent::afterFind();
	}
	
	/**
	 * @return boolean
	 */
	protected function beforeSave()
	{
		if (!empty($this->location))
		{
			$location = array();
			$_location = $this->location;
			if (!is_array($_location))
			{
				$_location = preg_replace('/([0-9]*\.?[0-9]+)/', '"\\1"', $_location);
				$_location = CJSON::decode($_location);
			}
			
			foreach($_location as $key => $value)
			{
				foreach($value as $point)
				{
					$location[$key][] = implode(' ', $point);
				}
				$location[$key] = implode(',', $location[$key]);
			}
			$this->location = new CDbExpression("GEOMFROMTEXT('POLYGON((". implode('),(', $location) ."))')");
		}
		
		return parent::beforeSave();
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
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('post',$this->post,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}