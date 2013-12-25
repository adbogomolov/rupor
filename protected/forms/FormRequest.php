<?php

class FormRequest extends CFormModel
{
    public $title;
    public $description;
    public $address;

    // Добавление с виджета
    public $full_name;
    public $address_or_email;

    public $lat;
    public $lng;
    public $files = array();
    public $videos = array();
    public $tags = array();
    public $officers = array();
    public $notify_email;

    private $_request;

    public function rules()
    {
        return array(
			array('title, description, address, lat, lng, tags', 'required'),
			array('full_name', 'validate_full_name'),
			array('address_or_email', 'validate_address_or_email'),
			array('title, address', 'length', 'max'=>255),
			array('description', 'safe'),
			array('notify_email', 'numerical', 'integerOnly'=>true),
			array('officers', 'validate_officers'),
			array('files', 'validate_files'),
			array('videos', 'validate_video'),
			array('tags', 'validate_tags'),
			array('address', 'validate_address'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Название',
            'description' => 'Описание',
            'address_or_email' => 'Почтовый или электроный адрес для ответа',
            'full_name' => 'Ваши фамилия, имя и отчество',
            'address' => 'Адрес',
            'files' => 'Фото/видео',
            'videos' => 'Ссылки на видео с Youtube.com',
            'tags' => 'Тема',
            'officers' => 'Получатели',
            'notify_email' => 'Уведомления на email',
        );
    }

    // Валидация полного имени
    //
    public function validate_full_name($attribute, $params)
    {
    	// Если это не форма с виджета
    	//
    	if(!isset($_POST['FormRequest']['full_name']))
    	{
    		return false;
    	}

    	if (empty($this->full_name))
    	{
    		$this->addError($attribute, 'Необходимо заполнить поле «Ваши фамилия, имя и отчество».');
    	}
    }

    // Валидация аддреса или емайла
    //
    public function validate_address_or_email($attribute, $params)
    {
    	// Если это не форма с виджета
    	//
    	if(!isset($_POST['FormRequest']['full_name']))
    	{
    		return false;
    	}

    	if (empty($this->address_or_email))
    	{
    		$this->addError($attribute, 'Необходимо заполнить поле «Почтовый или электроный адрес для ответа».');
    	}

    }

	// Валидация тэгов
    public function validate_address($attribute, $params)
	{
		if (empty($this->address) || empty($this->lat) || empty($this->lng))
			$this->addError($attribute, 'Укажите правильный адрес.');
	}

	// Валидация тэгов
    public function validate_tags($attribute, $params)
	{
		if (empty($this->tags))
			$this->addError($attribute, 'Необходимо выбрать тему.');
	}

	// Валидация фото
    public function validate_files($attribute, $params)
	{
	}

	// Валидация видео
    public function validate_video($attribute, $params)
	{
		foreach($this->videos as $key => $value)
		{
			if (empty($value))
				continue;
			if (!preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value))
				$this->addError('videos', 'Ссылка №'.($key+1).' не корректна.');
		}
	}

	// Валидация получателей
    public function validate_officers($attribute, $params)
	{
		// if ($this->officers)
			// $this->addError($attribute, '');
	}

    public function getRequest()
	{
		return $this->_request;
	}

    public function save($validate=true)
    {
		if ($validate && !$this->validate())
			return false;

		$request = $this->_request = new Request();
		$request->setAttributes($this->attributes);

		// Если форма из виджета
		//
		if(isset($this->attributes['full_name']))
    	{
			$_POST['widget_data'] = array('full_name' => $this->attributes['full_name'], 'address_or_email' => $this->attributes['address_or_email']);
    	}

		if ($request->save())
		{
			if ($request->author_id)
			{
				// +1
				$request->like();
				// Подписка на уведомления
				if ($this->notify_email)
					$request->subscribe();
			}

			// тэги
			foreach(array_unique($this->tags) as $tag_id)
			{
				$tagRequest = new TagRequest();
				$tagRequest->attributes = array(
					'tag_id' => $tag_id,
					'request_id' => $request->id,
				);
				$tagRequest->save();
			}

			// TODO: сделать для всех global
			$this->officers[] = 9;

			// ответственные организации
			foreach(array_unique($this->officers) as $officer_id)
			{
				$officerRequest = new OfficerRequest();
				$officerRequest->attributes = array(
					'officer_id' => $officer_id,
					'request_id' => $request->id,
				);
				$officerRequest->save();
			}

			// Если доступно хранилище и передан список файлов
			if ($request->storage && $this->files)
			{
				foreach(array_unique($this->files) as $id => $hash)
				{
					$file = $request->storage->get($id);
					if ($file->tempHash == $hash) // проверка временого хеша
					{
						$file->setOwner($request);
						$file->save();
					}
				}
			}

			// YouTube видео
			if ($this->videos)
			{
				$videos = array();
				foreach(array_unique($this->videos) as $url)
				{
					if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
					{
						$videos[] = trim($match[1]); // youtube video id
					}
				}
				if (!empty($videos))
				{
					$request->video = implode("\n", $videos);
					$request->update(array('video'));
				}
			}

			return true;
		}
    }
}