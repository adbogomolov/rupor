<?php

/**
 * This is the model class for table "officer_layer".
 *
 * The followings are the available columns in table 'officer_layer':
 * @property integer $id
 * @property integer $officer_id
 * @property integer $layer_id
 *
 * The followings are the available model relations:
 * @property Layer $layer
 * @property Officer $officer
 */
class OfficerLayer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OfficerLayer the static model class
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
		return 'officer_layer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('officer_id, layer_id', 'required'),
			array('officer_id, layer_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, officer_id, layer_id', 'safe', 'on'=>'search'),
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
			'layer' => array(self::BELONGS_TO, 'Layer', 'layer_id'),
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
			'layer_id' => 'Layer',
		);
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
		$criteria->compare('layer_id',$this->layer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}