<?php

class RequestController extends Controller
{
	public function accessRules()
	{
		return array(
            array('allow',
                'actions'=>array('test'),
				'roles'=>array(User::ROLE_ADMIN),
            ),
            array('deny',
                'actions'=>array('like', 'subscribe', 'changestatus'),
				'users'=>array('?'),
            ),
            array('deny',
                'actions'=>array('test'),
				'users'=>array('*'),
            ),
		);
	}

	/**
	 * Получить код виджета
	 * @return void
	 */
    public function actionWidget()
    {
		$this->render('widget');
	}

	/**
	 * @return void
	 */
    public function actionWidgetTest()
    {
		$src = '/request/widget';
		
		echo <<<HTML
		<html>
		<body>
			<iframe src="http://e-rupor.ru/request/widget?location=Сургут" width="100%" height="500"></iframe>
		</body>
		</html>
HTML;
	}
	
	/**
	 * Тестирование
	 * @return void
	 */
    public function actionTest($count=1000)
    {
		return;
		
		// Выборка ответственных
		
		// $point = array(61,25921449720678, 73,39668066137678);
		$point = array(1, 1);
		$officers = Officer::model()
			->byLocation('Россия, Ханты-Мансийский автономный округ, Сургут', $point)
			->findAll();
		
		foreach($officers as $officer)
		{
			print_r(CVarDumper::dump($officer->attributes, 10, 1, 1));
		}
		
		return;
		
		// Добавить большое количество точек в область
		
		// Кировоградская область
		$bounds = array(
			array(47.749129, 29.749166),
			array(49.24617, 33.893696),
		);
		
		function c($float, $len=8){ return str_pad(str_replace('.', '', $float), 8, 0); }
		
		$i=0;
		while($i++<$count)
		{
			$lat = mt_rand(c($bounds[0][0]), c($bounds[1][0])) * 0.000001;
			$lng = mt_rand(c($bounds[0][1]), c($bounds[1][1])) * 0.000001;
		
			$request = new Request();
			$request->title = 'Point ('.$lat.'x'.$lng.')';
			$request->lat = $lat;
			$request->lng = $lng;
			$request->description = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
			$request->status = mt_rand(1, 3);
			$request->save();
		}
	}

	/**
	 * Изменить статус проблемы AJAX
	 * @param integer $id ID Проблемы
	 * @param integer $status Статус
	 * @return void
	 */
    public function actionChangeStatus($id, $status)
    {
		$model = Request::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException(404);
		
		$result = new stdClass;
		
		if ($model->status != $status && Yii::app()->user->checkAccess('changestatus',array(
			'status'=>$status
		)))
		{
			$model->status = $status;
			if ($model->save())
			{
				$result->status = 1;
				$result->message = 'Вы успешно изменили статус проблемы!';
				
				Feed::add($model->id, Feed::EVENT_CHANGE_STATUS, $model->status);
				NotificationAdmin::send($model, $model->status);
			}
			else
			{
				$result->status = 0;
				$result->message = 'Ошибка на сервере.';
			}
		}
		
		header('Content-type: application/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Подписка AJAX
	 * @return void
	 */
    public function actionSubscribe($id)
    {
		$model = Request::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException(404);
		
		$result = new stdClass;
		$result->status = $model->subscribe();
		
		switch($result->status){
		case(1): $result->message = 'Спасибо, вы успешно подписались!'; break;
		case(-1): $result->message = 'Вы ушпешно отписались.'; break;
		default: $result->message = 'Ошибка на сервере.'; break;
		}
		
		header('Content-type: application/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Лайк AJAX
	 * @return void
	 */
    public function actionLike($id)
    {
		$model = Request::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException(404);
		
		$result = new stdClass;
		
		if ($model->like())
		{
			$result->message = 'Спасибо, ваш голос учтен!';
			$result->count = $model->likes_count;
			$result->status = 1;
		}
		else
		{
			$result->message = 'Вы уже голосовали ранее.';
			$result->status = 0;
		}
		
		header('Content-type: application/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Загрузка файла для коммента AJAX
	 * @return void
	 */
    public function actionUploadComment()
    {
		$result = new stdClass;
		
		if (Yii::app()->request->isPostRequest)
		{
			$model = new Comment;
			$model->file = CUploadedFile::getInstanceByName('qqfile');
			if ($model->validate(array('file')))
			{
				if ($file = $model->storage->store($model->file))
				{
					if ($file->isImage)
					{
						$image = Yii::app()->image->load($file->basePath);
						$image->resize(1000, '100%');
						$image->save($file->basePath);
					}
					$result->success = 1;
					$result->id = $file->id;
					$result->hash = $file->tempHash;
				}
			}
			else
			{
				if (!empty($model->errors['file']))
					$result->error = implode("\n", $model->errors['file']);
			}
		}
		
		header('Content-type: text/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Загрузка файла AJAX
	 * @return void
	 */
    public function actionUpload()
    {
		$result = new stdClass;
		
		if (Yii::app()->request->isPostRequest)
		{
			$model = new Request;
			$model->file = CUploadedFile::getInstanceByName('qqfile');
			if ($model->validate(array('file')))
			{
				if ($file = $model->storage->store($model->file))
				{
					$image = Yii::app()->image->load($file->basePath);
					$image->resize(1000, '100%');
					$image->save($file->basePath);
					
					$result->success = 1;
					$result->id = $file->id;
					$result->hash = $file->tempHash;
				}
			}
			else
			{
				if (!empty($model->errors['file']))
					$result->error = implode("\n", $model->errors['file']);
			}
		}
		
		header('Content-type: text/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Принять в работу
	 * @return void
	 */
    public function actionAccept($id, $hash)
    {
		$officerRequest = OfficerRequest::model()->findByPk($id);
		if (!$officerRequest ||
			$officerRequest->hash() !== $hash || // не верный хеш
			// $officerRequest->view_date || // уже было просмотрено
			!$officerRequest->request // не найдена проблема
		)
			throw new CHttpException(404);
		
		// уже просмотрено
		if ($officerRequest->view_date)
		{
			Yii::app()->user->setFlash('notice', 'Эта проблема уже принята Вами в работу!');
			$this->redirect($officerRequest->request->getHref());
		}
		
		if (Yii::app()->user->isGuest)
		{
			Yii::app()->user->setState('officer', array(
				'id' => $officerRequest->officer_id,
				'returnUrl' => Yii::app()->createAbsoluteUrl(Yii::app()->request->url)
			));
			$this->redirect(Yii::app()->user->loginUrl);
		}
		else
		{
			// Обновить дату просмотра
			$officerRequest->view_date = date('Y-m-d H:i:s');
			$officerRequest->update(array('view_date'));
			
			// Привязать пользователя
			$user = Yii::app()->user->getModel();
			$user->officer_id = $officerRequest->officer_id;
			$user->update(array('officer_id'));
			
			// Статус "В работе"
			$officerRequest->request->status = Request::STATUS_INPROGRESS;
			$officerRequest->request->update(array('status'));
			$officerRequest->request->subscribe();
			
			Feed::add($officerRequest->request->id, Feed::EVENT_CHANGE_STATUS, Request::STATUS_INPROGRESS);
			
			Yii::app()->user->setFlash('notice', 'Проблема принята в работу!');
			$this->redirect($officerRequest->request->getHref());
		}
	}

	/**
	 * Получить список ответственных AJAX
	 * @param array $tags Тема
	 * @param string $address Адрес
	 * @param array $point Координаты точки
	 * @return array
	 */
    public function actionOfficers(array $tags=array(), $address=null, array $point=array())
    {
		$model = new Officer('search');
		$model->unsetAttributes();
		
		if ($address) $model->byLocation($address, $point);
		
		$result = array();
        $flag0 = false;
		foreach($model->findAll('email!="" AND days_count=0 AND global = 0') as $officer)
		{
			$valid = false;
            // TODO: Оптимизировать
			if (!$officer->officerTags)
			{
				$valid = true;
			}
			else
			{
				if (empty($tags) || empty($tags[0]))
					continue;
				foreach($officer->officerTags as $tag)
				{
					if (in_array($tag->tag_id, $tags))
					{
						$valid = true;
						break;
					}
				}
			}
			
			if (!$valid)
				continue;
			
			$result[] = array(
				'id' => $officer->id,
				'name' => $officer->fullname,
				'post' => $officer->post,
			);
            $flag0 = true;
		}
        
        if (!$flag0) {
        
            //Если организаций с days_count=0 нет, то загружаем организации с days_count<31 
            $model2 = new Officer('search');
            $model2->unsetAttributes();
            
            if ($address) $model2->byLocation($address, $point);

            foreach($model2->findAll('email!="" AND days_count>0 AND days_count<31 AND global = 0') as $officer)
            {
                $valid = false;
                // TODO: Оптимизировать
				if (empty($tags) || empty($tags[0]))
					continue;

                if (!$officer->officerTags)
                {
                    $valid = true;
                }
                else
                {
                    if (empty($tags) || empty($tags[0]))
                        continue;
                    foreach($officer->officerTags as $tag)
                    {
                        if (in_array($tag->tag_id, $tags))
                        {
                            $valid = true;
                            break;
                        }
                    }
                }
                
                if (!$valid)
                    continue;
                
                $result[] = array(
                                  'id' => $officer->id,
                                  'name' => $officer->fullname,
                                  'post' => $officer->post,
                                  );
            }
        
        }
        
		
		header('Content-type: application/json;charset=utf-8');
		echo CJSON::encode($result);
	}
	
	/**
	 * Просмотр проблемы
	 * @return void
	 */
    public function actionView($id, $from=null)
    {
		$model = Request::model()->findByPk($id);
		
		// Если проблема на нейдена выводим 404
		if (!$model)
			throw new CHttpException(404);
		
		$user = Yii::app()->user->getModel();
		
		// Форма создания заявления
		$statement = new FormRequestStatement();
		$statement->request_id = $model->id;
		$statement->body = $model->description;
		
		if ($user)
		{
			$statement->name = $user->name;
			$statement->email = $user->email;
			$statement->address = $user->getEavAttribute('address');
			$statement->phone = $user->getEavAttribute('phone');
		}
		
		// Отправка заявления
		if (!empty($_POST['FormRequestStatement']))
		{
			$statement->setAttributes($_POST['FormRequestStatement']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormRequestStatement')
			{
				$validate = CActiveForm::validate($statement);
				if ($validate=='[]')
				{
					$statement->save(false);
				}
				echo $validate;
				Yii::app()->end();
			}
			else
			{
				if ($statement->save())
				{
					Yii::app()->user->setFlash('notice', 'Заявление успешно отправлено!');
					$this->refresh();
				}
			}
		}
		
		// ID выбраных ответственных
		$choosen_officers = array();
		foreach($model->officers as $obj)
		{
			$choosen_officers[$obj->officer_id] = $obj->officer_id;
		}
		
		// получить всех ответственных для данной проблемы
		$officers = array();
		foreach($model->getAllOfficers() as $officer)
		{
			$officer = (object) $officer->attributes;
			$officer->choosen = (bool) isset($choosen_officers[$officer->id]);
			$officers[$officer->id] = $officer;
		}
		
		// Данные для шаблона
		$data = array(
			'model' => $model,
			'user' => $user,
			'statement' => $statement,
			'officers' => $officers,
		);
		
		// Файл отображения по умолчанию
		$file = 'view';
		
		// Для новости свой файл отображения
		if ($model->type == Request::TYPE_POST)
		{
			$file = 'view_post';
		}
		
		// Для ajax запрсоов свой файл отображения
		if (Yii::app()->request->isAjaxRequest && !empty($from) && $this->getViewFile('_view_'.$from))
		{
			$file = '_view_'.$from;
		}
		
		if (Yii::app()->request->isAjaxRequest)
			$this->renderPartial($file, $data, false, true);
		else
			$this->render($file, $data);
	}
	
	/**
	 * Добавление проблемы
	 * @return void
	 */
    public function actionAdd()
    {
		$model = new FormRequest;
		
		if (!empty($_POST['FormRequest']))
		{
			$model->setAttributes($_POST['FormRequest']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormRequest')
			{
				$validate = CActiveForm::validate($model);
				if ($validate=='[]')
				{
					$result = new stdClass;
					if ($model->save())
					{
						$result->status = 1;
						$result->message = 'Проблема успешно добавлена!';
						$result->returnUrl = Yii::app()->createAbsoluteUrl($model->request->getHref());
					}
					else
					{
						$result->message = 'Возникла ошибка при добавлении проблемы.';
					}
					$validate = CJSON::encode($result);
				}
				echo $validate;
				Yii::app()->end();
			}
			
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Проблема успешно добавлена!');
				$this->redirect($model->request->getHref());
			}
		}
		
		$data = array(
			'tags' => Tag::treeList(),
			'model' => $model,
		);
		
		if (Yii::app()->request->isAjaxRequest)
			$this->renderPartial('add', $data, false, true);
		else
			$this->render('add', $data);
    }
	
	/**
	 * Получить список проблем
	 * @return void
	 */
    public function actionPoints($location)
    {
		if (empty($location))
			throw new CHttpException(400);
		
		$request =  Yii::app()->request;
		
		// Критерия поиска
		$criteria = new CDbCriteria;
        $criteria->condition = 'visible';
		$criteria->addCondition('address LIKE :address');
		$criteria->params[':address'] = $location.'%';
		
		$user = $request->getParam('my') && !Yii::app()->user->isGuest ?
			Yii::app()->user->model :
			($request->getParam('uid') ? $request->getParam('uid') : null);
			
		// Мои проблемы
		if ($user)
		{
			if ($user->officer_id)
			{
				$criteria->join = 'LEFT OUTER JOIN officer_request O ON O.request_id = t.id';
				$criteria->addCondition('O.officer_id = ' . $user->officer_id);
			}
			else
			{
				$criteria->addCondition('author_id = ' . $user->id);
			}
		}
		
		// Фильтр по статусу
		if ($status=(array)$request->getParam('status'))
		{
			$criteria->addInCondition('status', $status);
		}
		
		// Фильтр по теме
		if ($tags=(array)$request->getParam('tags'))
		{
			$criteria->with = array('tagRequests');
			$criteria->together = true;
			$criteria->addInCondition('tag_id', $tags);
		}
		
		// Фильтр по дате
		if ($from=$request->getParam('from'))
		{
			$criteria->addCondition('t.created >= :created_from');
			$criteria->params[':created_from'] = date('Y-m-d H:i:s', strtotime($from));
		}
		if ($to=$request->getParam('to'))
		{
			$criteria->addCondition('t.created <= :created_to');
			$criteria->params[':created_to'] = date('Y-m-d H:i:s', strtotime($to));
		}
		
		$result = array();
		switch($request->getParam('by'))
		{
			case('feed'):
				
				$criteria->order = 't.lastactive DESC';
				$criteria->offset = (int) $request->getParam('offset');
				$criteria->limit = 10;
				
				foreach(Request::model()->findAll($criteria) as $request)
				{
					// Изображение
					$image = $request->files ? $request->files[0]->getThumbUrl(300) : null;
					
					// Последнее событие
					$event = Feed::model()->find(array(
						'condition' => 'request_id = ' . $request->id,
						'order' => 'id DESC'
					));
					if ($event) $event = $event->toString(1);
					
					$result[] = array(
						'id' => $request->id,
						'title' => $request->title,
						'status' => $request->status,
						'lat' => $request->lat,
						'lng' => $request->lng,
						'url' => $request->getHref(),
						'description' => Text::limit_chars($request->description, 100),
						'event' => $event,
						'date' => CDateTime::format($request->lastactive),
						'address' => explode(',', str_replace(', ',',',$request->address)),
						'address' => $request->address,
						'image' => $image,
						'comment_count' => $request->comments_count,
						'likes_count' => $request->likes_count,
					);
				}
				break;
			case('map'):
			default:
				foreach(Request::model()->findAll($criteria) as $request)
				{
					$result[] = array(
						'id' => $request->id,
						'title' => $request->title,
						'status' => $request->status,
						'lat' => $request->lat,
						'lng' => $request->lng,
					);
				}
				break;
		}
		
		header('Content-type: application/json;charset=utf-8');
		echo CJSON::encode($result);
    }
}