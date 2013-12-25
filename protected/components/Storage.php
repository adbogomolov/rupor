<?php

class Storage extends CComponent
{
	public $baseUrl;
	public $basePath; // Полный путь к храниллищу
	public $baseDir = 'files';
	
	public $scheme;
	public $provider; // TODO: ftp, Amazon s3
	
	public $server; // active server, default 0
	public $servers = array();
	
	private $owner;
	
	public function init()
	{
		$this->server = (int) $this->server;
		if (!empty($this->servers[$this->server]))
		{
			foreach($this->servers[$this->server] as $key => $value)
			{
				if (property_exists($this, $key))
					$this->$key = $value;
			}
		}
		
		if (!$this->baseUrl)
			$this->baseUrl = Yii::app()->getBaseUrl(1) .'/'. $this->baseDir;
		if (!$this->basePath)
			$this->basePath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->baseDir;
		
		$this->basePath = realpath($this->basePath);
		
		if (!$this->basePath)
			throw new StorageException('basePath is not valid');
		
		$this->reset();
	}
	
	/**
	 * Scope: Обнулить компонент
	 * @return this
	 */
	public function reset()
	{
		$this->scheme = new StorageSchemeDefault();
		$this->owner = null;
		return $this;
	}
	
	/**
	 * Scope: Изменить схему хранения
	 * @param StorageScheme
	 * @return this
	 */
	public function scheme(StorageScheme $scheme)
	{
		$this->scheme = $scheme;
		return $this;
	}
	
	/**
	 * Scope: Установить владельца
	 * @param CActiveRecord
	 * @return this
	 */
	public function owner(ActiveRecord &$obj)
	{
		$this->owner = $obj;
		return $this;
	}
	
	/**
	 * Выбрать файл
	 * @return StorageFile
	 */
	public function get($id, $process=array())
	{
		return StorageFile::model()->findByPk($id);
	}
	
	/**
	 * Удалить файл
	 * @return StorageFile
	 */
	public function delete($id)
	{
		if ($file = $this->get($id))
			return $file->delete();
	}
	
	/**
	 * @return StorageFile
	 */
	public function getModel()
	{
		return StorageFile::model();
	}
	
	/**
	 * Добавить новый файл в хранилище
	 * @param CUploadedFile
	 * @return StorageFile
	 */
	public function store(CUploadedFile $instance)
	{
		if (!is_file($instance->tempName))
			return;
		
		$mime = explode('/', $instance->type);
		
		$file = new StorageFile;
		$file->server = $this->server;
		$file->name = $instance->name;
		$file->extension = $instance->extensionName;
		$file->size = $instance->size;
		$file->mime_major = $mime[0];
		$file->mime_minor = $mime[1];
		$file->path = $instance->tempName;
		$file->hash = sha1_file($instance->tempName);
		if ($this->owner) $file->setOwner($this->owner);
		$file->save();
		
		$this->reset();
		
		if ($file->id)
			return $file;
	}
}

class StorageException extends CException{}

/**
 * Основная схема хранения файлов
 * @example: cs1.site.com/tmp/j87uyhgtr54eraq123e45rt654ret49
 * @return string
 */
class StorageScheme
{
	public function generate(StorageFile &$model)
	{
		return '/tmp/'.uniqid(rand(),1) . '.'
			. strtolower($model->extension);
	}
}
/**
 * @example: cs1.site.com/comment/00f/fe4/54f/j87uyhgtr54eraq123e45rt654ret49.jpg
 * @need params [owner]
 * @return string
 */
class StorageSchemeDefault extends StorageScheme
{
	public function generate(StorageFile &$model)
	{
		if (empty($model->owner))
			return parent::generate($model);
		
		return '/'
			. strtolower($model->owner) . '/'
			. substr($model->hash, 0, 3) . '/'
			. substr($model->hash, 3, 3) . '/'
			. substr($model->hash, 6, 3) . '/'
			. substr($model->hash, 9) . '.'
			. strtolower($model->extension);
	}
}
/**
 * @example: cs13.site.com/user/1000/456/5646.jpg
 * @need params [owner] and [owner_id]
 * @return string
 */
class StorageSchemeDynamic extends StorageScheme
{
	public function generate(StorageFile &$model)
	{
		if (empty($model->owner) || empty($model->owner_id))
			return parent::generate($model);
		
		return '/'
			. strtolower($model->owner) . '/'
			. ($model->owner_id + 999 - (($model->owner_id - 1) % 1000)) . '/'
			. $model->owner_id . '/'
			. $model->id . '.'
			. strtolower($model->extension);
	}
}