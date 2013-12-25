<?php

/**
 * This is the model class for table "officer".
 *
 * The followings are the available columns in table 'officer':
 * @property integer $id
 * @property string $fullname
 * @property string $position
 * @property string $email
 * @property string $phone
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Area[] $areas
 * @property AreaOfficer[] $areaOfficers
 * @property OfficerLayer[] $officerLayers
 * @property OfficerRequest[] $officerRequests
 * @property OfficerTag[] $officerTags
 * @property Request[] $requests
 */
class Officer extends CActiveRecord
{
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
		return 'officer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fullname, email, created', 'required'),
			array('fullname, position, email, phone', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fullname, position, email, phone, created', 'safe', 'on'=>'search'),
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
			'areas' => array(self::HAS_MANY, 'Area', 'officer_id'),
			'areaOfficers' => array(self::HAS_MANY, 'AreaOfficer', 'officer_id'),
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
			'fullname' => 'Fullname',
			'position' => 'Position',
			'email' => 'Email',
			'phone' => 'Phone',
			'created' => 'Created',
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
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}