<?php

/**
 * This is the model class for table "storage".
 *
 * The followings are the available columns in table 'storage':
 * @property string $id
 * @property string $path
 * @property string $name
 * @property string $extension
 * @property string $mime_major
 * @property string $mime_minor
 * @property string $size
 * @property string $owner
 * @property string $owner_id
 * @property string $hash
 * @property string $created
 */
class StorageFile extends CActiveRecord
{
	private $_owner;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StorageFile the static model class
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
		return '{{storage}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, extension, mime_major, mime_minor, size', 'required'),
			array('path, name', 'length', 'max'=>255),
			array('extension', 'length', 'max'=>10),
			array('hash', 'length', 'max'=>40),
			array('mime_major', 'length', 'max'=>64),
			array('mime_minor', 'length', 'max'=>128),
			array('owner', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, path, name, extension, mime_major, mime_minor, size, owner, owner_id, hash, created', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Scope: Выборка по владельцу
	 * @return this
	 */
	public function byOwner(CActiveRecord &$obj, $operator='AND')
	{
		$criteria = new CDbCriteria;
		$criteria->compare('owner', get_class($obj));
		$criteria->compare('owner_id', $obj->primaryKey);
		$this->getDbCriteria()->mergeWith($criteria, $operator);
		return $this;
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'path' => 'Путь',
			'name' => 'Название',
			'extension' => 'Расширение',
			'mime_major' => 'Mime Major',
			'mime_minor' => 'Mime Minor',
			'size' => 'Размер',
			'owner' => 'Владелец',
			'owner_id' => 'Владелец ID',
			'hash' => 'Хеш',
			'created' => 'Дата',
		);
	}
	
	public function getBasePath()
	{
		return Yii::app()->storage->basePath . $this->path;
	}
	
	public function getBaseUrl()
	{
		return Yii::app()->storage->baseUrl . $this->path;
	}
	
	/**
	 * Уменьшеная копия
	 * @param int Размер
	 * @return string
	 */
	public function getThumbUrl($size=100)
	{
		if ($this->isImage)
		{
			$parts = explode('.', $this->path);
			$extension = array_pop($parts);
			$parts[] = $size;
			$parts[] = $extension;
			$path = implode('.', $parts);
			if (!file_exists($path))
			{
				$image = Yii::app()->image->load($this->basePath);
				$image->resize($size, '100%');
				$image->crop($size, $size);
				$image->save(Yii::app()->storage->basePath . $path);
			}
			return Yii::app()->storage->baseUrl . $path;
		}
		return $this->baseUrl;
	}
	
	/**
	 * Название файла
	 * @return string
	 */
	public function getTitle()
	{
		return $this->name;
	}
	
	/**
	 * Проверить, является ли файл изображением
	 * @return boolean
	 */
	public function getIsImage()
	{
		return ($this->mime_major == 'image');
	}
	
	/**
	 * @return string
	 */
	public function toString($htmlOptions=array())
	{
		if ($this->isImage)
		{
			$htmlOptions['rel'] = 'modal';
			$htmlOptions['class'] = "attachment-image";
			return CHtml::link(CHtml::image($this->thumbUrl, $this->name), $this->baseUrl, $htmlOptions);
		}
		else
		{
			$htmlOptions['target'] = '_blank';
			$htmlOptions['class'] = "attachment-file";
			return CHtml::link($this->name . ' ('. Text::bytes($this->size) .')', $this->baseUrl, $htmlOptions);
		}
	}
	
	/**
	 * Временной хеш
	 * @return string
	 */
	public function getTempHash()
	{
		return sha1($this->created);
	}
	
	/**
	 * Получить владельца файла
	 * @return ActiveRecord
	 */
	public function getOwner()
	{
		if ($this->_owner===null)
		{
			if ($this->owner && $this->owner_id &&
			   ($obj = ActiveRecord::model($this->owner)) &&
			   ($obj = $obj->findByPk($this->owner_id))
			){
				$this->_owner = $obj;
			}
		}
		return $this->_owner;
	}
	/**
	 * Установить владельца файла
	 * @param ActiveRecord
	 * @return void
	 */
	public function setOwner(ActiveRecord &$obj)
	{
		$this->_owner = $obj;
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
		
		if ($this->_owner)
		{
			$this->owner = get_class($this->_owner);
			$this->owner_id = $this->_owner->primaryKey;
		}
		
		// VarDumper::dump($this->attributes);
		// exit;
		
		return parent::beforeSave();
	}
	
	/**
	 * @return void
	 */
	public function afterSave()
	{
		$scheme = Yii::app()->storage->scheme;
		
		// У владельца указана своя схема
		if (($owner = $this->getOwner()) && $owner->storage)
			$scheme = $owner->storage->scheme;
		
		// схема хранения файла
		$schemePath = $scheme->generate($this);
		
		// изменилась схема хранения файла
		if ($schemePath != $this->path)
		{
			$file_path = Yii::app()->storage->basePath . $schemePath;
			$file_dir = dirname($file_path);
			// если не создана папка
			if (!is_dir($file_dir))
			{
				@mkdir($file_dir, 0777, true);
			}
			if (is_dir($file_dir))
			{
				if (!is_file($this->path))
				{
					$this->path = Yii::app()->storage->basePath . $this->path;
				}
				// копировать файл
				if (@copy($this->path, $file_path))
				{
					@unlink($this->path); // удалить старый файл
					$this->path = $schemePath;
					$this->updateByPk($this->id,array('path'=>$this->path));
				}
			}
		}
	}
	
	/**
	 * @return void
	 */
	public function afterDelete()
	{
		if (!self::model()->exists('path=:path', array('path'=>$this->path)))
		{
			@unlink($this->basePath);
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
		$criteria->compare('path',$this->path,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('mime_major',$this->mime_major);
		$criteria->compare('mime_minor',$this->mime_minor);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('hash',$this->hash);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}