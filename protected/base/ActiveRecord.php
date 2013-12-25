<?php

class ActiveRecord extends CActiveRecord
{
	const CACHE_DURATION = 1000;
	
	/**
	 * Get the Object Identity string
	 * @return string
	 */
	public function getIdentity()
	{
		return get_class($this) .'_'. $this->primaryKey;
	}

	/**
	 * Experimetnal string accessor. Returns an html string representation of the object
	 * @return string
	 */
	public function toString($htmlOptions=array())
	{
		$text = !empty($htmlOptions['text']) ? $htmlOptions['text'] : $this->getTitle();
		$href = !empty($htmlOptions['href']) ? $htmlOptions['href'] : $this->getHref();
		return CHtml::link($text, $href, $htmlOptions);
	}
	
	/**
	 * Хранилище файлов
	 * @return StorageComponent
	 */
	public function getStorage()
	{
		if (!empty(Yii::app()->storage))
		{
			return Yii::app()->storage->owner($this)->scheme(new StorageSchemeDefault);
		}
	}
	
	/**
	* Gets the title of the item. This would be a name for users
	* @return string
	*/
	public function getTitle()
	{
		return (string) $this->getAttribute('title');
	}

	/**
	* Gets the description of the item. This might be about me for users (todo
	* @return string
	*/
	public function getDescription()
	{
		return (string) $this->getAttribute('description');
	}
	
	/**
	 * Gets an URL to this model
	 * @param array $config
	 * @return string The URL
	 */
	public function getHref(array $config=array())
	{
		if (empty($config['route']))
		{
			$action = !empty($config['action']) ? $config['action'] : 'index';
			$controller = !empty($config['controller']) ? $config['controller'] : 'profile';
			$route = get_class($this) .'/'. $controller .'/'. $action;
		}
		else
		{
			$route = ltrim($config['route'], '/');
		}
		$params = !empty($config['params']) ? $config['params'] : array('id' => $this->primaryKey);
		return Yii::app()->createUrl('/'. $route, $params);
	}
	
	/**
	 * Get formatted date
	 * @see CDateTime::format()
	 * @param string an field name in this resource
	 * @return string formatted date
	 */
	public function getDate($field='creation_date', $newly=true)
	{
		if ($this->hasAttribute($field))
			return CDateTime::format($this->{$field}, $newly);
	}
	
	/**
	 * Deletes rows with the specified condition.
	 * If condition is empty, use default criteria
	 * @see ActiveRecord::deleteAll()
	 */
	public function deleteAll($condition='', $params=array())
	{
		if (!$condition)
		{
			// get default condition
			$condition = $this->getDbCriteria();
		}
		return parent::deleteAll($condition, $params);
	}

	/**
	 * Set is deleted
	 * @param int Value
	 * @return void
	 */
    public function setDeleted($value=1)
	{
		if ($this->hasAttribute('deleted'))
		{
			$this->scenario = 'deleted';
			$this->deleted = (int) $value;
			$this->update(array('deleted'));
		}
	}
	
	/**
	 * Find ActiveRecord Model by identity
	 * @param string Model identiry. Example: User_1
	 * @return ActiveRecord Object
	 */
    public static function modelByIdentity($identity)
	{
		$identity = trim($identity);
		@list($class, $id) = explode('_', $identity);
		if (!empty($class) && !empty($id) && class_exists($class))
		{
			if ($model = ActiveRecord::model($class))
				return $model->findByPk($id);
		}
	}
	
    // именованноеусловие с параметром
    public function lang($lang){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "lang='$lang'",
        ));
        return $this;
    }
	
	// Caching
	
	/**
	 * Flush cache after delete model
	 * @return void
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		
		if (Yii::app()->cache)
		{
			Yii::app()->cache->set(get_class($this), time(), 0);
			Yii::app()->cache->delete($this->getIdentity());
		}
	}
	
	/**
	 * Flush cache after save model
	 * @return void
	 */
	protected function afterSave()
	{
		parent::afterSave();
		
		if (Yii::app()->cache)
		{
			if (!$this->isNewRecord)
			{
				Yii::app()->cache->set($this->getIdentity(), time(), 0);
			}
		}
	}
	
	/**
	 * Add Default Dependency
	 * @return this
	 */
	public function cache($duration, $dependency=null, $queryCount=1)
	{
		if ($dependency == null)
			$dependency = new CTagCacheDependency(get_class($this));
		$this->getDbConnection()->cache($duration, $dependency, $queryCount);
		return $this;
	}
	
	/**
	 * Find by primary key and caching
	 * @param bool $force - don't cache model
	 * @return object model
	 */
	public function findByPk($pk, $condition='', $params=array())
	{
		$class = get_class($this);
		$this->cache(self::CACHE_DURATION, new CTagCacheDependency($class, $class .'_'. serialize($pk)));
		return parent::findByPk($pk, $condition, $params);
	}
}