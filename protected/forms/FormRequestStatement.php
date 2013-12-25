<?php

class FormRequestStatement extends CFormModel
{
    public $request_id;
    public $tag_id; // Тег с шаблоном заявления
    public $officers = array();
    public $body;
	// user contacts
    public $name;
    public $email;
    public $address;
    public $phone;
	
    private $request;
	
    public function rules()
    {
        return array(
			array('request_id, name, email', 'required'),
			array('officers', 'required', 'message'=>'Выберите инстанцию'),
			array('name, email, address, phone', 'length', 'max'=>255),
			array('email', 'email'),
			array('body', 'safe'),
			array('request_id', 'numerical', 'integerOnly'=>true),
			array('request_id', 'validate_request'),
        );
    }
	
    public function attributeLabels()
    {
        return array(
			'name' => 'ФИО',
			'email' => 'Эл. почта',
			'address' => 'Адрес',
			'phone' => 'Телефон',
			'officers' => 'Инстранции',
			'body' => 'Обращение',
        );
    }
	
	/**
	 * @return void
	 */
    public function validate_request($attribute, $params)
	{
		$this->request = Request::model()->findByPk($this->request_id);
		if (!$this->request)
			$this->addError($attribute, 'Проблема не найдена.');
	}
	
	/**
	 * @return void
	 */
    public function save($validate=true)
    {
		if ($validate && !$this->validate())
			return false;
		
		$time = time();
		$created_time = strtotime($this->request->created); // время в формате UNIX Timestamp
		$result = false;
		
		foreach($this->officers as $officer_id)
		{
			$officer = Officer::model()->findByPk($officer_id);
			if (!$officer) continue;
			
			// Проверка доступности
			$available_time = $created_time + (60 * 60 * 24 * $officer->days_count);
			if ($available_time > $time) continue;
			
			$officerRequest = new OfficerRequest();
			$officerRequest->form = $this;
			$officerRequest->attributes = array(
				'statement' => 1, // пометить что это заявление
				'officer_id' => $officer_id,
				'request_id' => $this->request_id,
			);
			
			if ($officerRequest->save())
			{
				$result = true;
			}
		}
		
		return $result;
    }
}