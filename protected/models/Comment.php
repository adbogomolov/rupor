<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $author_id
 * @property integer $request_id
 * @property string $message
 * @property string $created
 *
 * The followings are the available model relations:
 * @property User $author
 * @property Request $request
 */
class Comment extends ActiveRecord
{
	public $file;
	private $_files;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_id, message, created', 'required'),
			array('author_id, request_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, author_id, request_id, message, created', 'safe', 'on'=>'search'),
			array('file', 'file', 'maxSize'=>5*1024*1024, 'types'=>'jpg,jpeg,png,gif,pdf,doc,docx', 'allowEmpty'=>true)
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
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'request' => array(self::BELONGS_TO, 'Request', 'request_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'author_id' => 'Автор',
			'request_id' => 'Проблема',
			'message' => 'Сообщение',
			'created' => 'Дата',
		);
	}
	
	/**
	 * Получить список файлов
	 * @return array
	 */
	public function getFiles($condition='', $params=array())
	{
		if (!empty($this->storage) && $this->_files===null)
			$this->_files = $this->storage->model->byOwner($this)->findAll($condition, $params);
		return $this->_files;
	}
	
	/**
	 * @return void
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			if (!$this->created)
				$this->created = date('Y-m-d H:i:s');
		}
		return parent::beforeSave();
	}
		
	/**
	 * @return void
	 */
	public function afterSave()
	{
		// Добавить действие в активность
		Feed::add($this->request_id, Feed::EVENT_ADD_COMMENT, $this->id);
		
		// Увеличить рейтинг автора
		if ($this->author_id)
			$this->author->setRating(1);
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
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('request_id',$this->request_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}
}