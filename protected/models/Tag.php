<?php

/**
 * This is the model class for table "tag".
 *
 * The followings are the available columns in table 'tag':
 * @property integer $id
 * @property string $name
 * @property integer $parent_tag_id
 *
 * The followings are the available model relations:
 * @property OfficerTag[] $officerTags
 * @property Tag $parentTag
 * @property Tag[] $tags
 * @property Layer $layer
 * @property TagLayer[] $tagLayers
 * @property TagRequest[] $tagRequests
 */
class Tag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tag the static model class
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
		return '{{tag}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('parent_tag_id, position', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('statement', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parent_tag_id', 'safe', 'on'=>'search'),
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
			'officerTags' => array(self::HAS_MANY, 'OfficerTag', 'tag_id'),
			'parentTag' => array(self::BELONGS_TO, 'Tag', 'parent_tag_id'),
			'tags' => array(self::HAS_MANY, 'Tag', 'parent_tag_id'),
			'tagLayers' => array(self::HAS_MANY, 'TagLayer', 'tag_id'),
			'tagRequests' => array(self::HAS_MANY, 'TagRequest', 'tag_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'parent_tag_id' => 'Parent Tag',
			'wiki_id' => 'Wiki',
		);
	}
	
    public function defaultScope()
    {
        return array(
            'order' => 'position',
        );
    }
	
	/**
	 * @return array
	 */
	public static function treeRecursion(&$tags, $prefix='', $level=0)
	{
		$list = array();
		foreach($tags as $tag)
		{
			$list[$tag['id']] = trim(str_repeat($prefix, $level).' '.$tag['name']);
			if (!empty($tag['children']))
				$list += self::treeRecursion($tag['children'], $prefix, $level+1);
		}
		return $list;
	}
	
	/**
	 * @return array
	 */
	public static function treeList($condition=null, $params=array())
	{
		$tags = self::treeArray($condition, $params);
		return self::treeRecursion($tags, '&nbsp;&nbsp;');
	}
	
	/**
	 * @return array
	 */
	public static function treeArray($condition=null, $params=array())
	{
		$tags = self::model()->findAll($condition, $params);
		$tree = array();
		$flat = array();
		
		foreach($tags as $tag)
		{
			$child = $tag->id;
			$parent = $tag->parent_tag_id;
			
			if (!isset($flat[$child]))
				$flat[$child] = $tag->attributes+array('children'=>array());
			else
				$flat[$child] = $tag->attributes+$flat[$child];
			
			if (!empty($parent))
			{
				if (!isset($flat[$parent]))
					$flat[$parent] = array('children'=>array());
				$flat[$parent]['children'][$child] =& $flat[$child];
			}
			else
				$tree[$child] =& $flat[$child];
		}
		
		return $tree;
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_tag_id',$this->parent_tag_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}