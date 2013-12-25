<?php

class SiteController extends Controller
{
    public function filters()
	{
		return array();
	}
	
	/**
	 * Конвертор базы
	 * @return void
	 */
    public function actionConvert()
    {
		$db2 = Yii::app()->db2;
		
		// foreach($db2->createCommand('SELECT * FROM b_iblock_section_element')->queryAll() as $row)
		// {
			// $row = (object) $row;
			// $tag = Tag::model()->findByAttributes(array('tmp_id'=>$row->IBLOCK_SECTION_ID));
			// $request = Request::model()->findByAttributes(array('tmp_id'=>$row->IBLOCK_ELEMENT_ID));
			// if ($tag && $request)
			// {
				// $model = new TagRequest;
				// $model->request_id= $request->id;
				// $model->tag_id= $tag->id;
				// $model->save();
			// }
		// }
		// exit;
		
		// foreach($db2->createCommand('SELECT * FROM b_iblock_section')->queryAll() as $row)
		// {
			// $row = (object) $row;
			// $tag = new Tag;
			// $tag->tmp_id= $row->ID;
			// $tag->tmp_parent_id= $row->IBLOCK_SECTION_ID;
			// $tag->name= $row->NAME;
			// $tag->save();
		// }
		
		// foreach(Tag::model()->findAll() as $tag)
		// {
			// if ($tag->tmp_parent_id)
			// {
				// $_tag = Tag::model()->findByAttributes(array('tmp_id'=>$tag->tmp_parent_id));
				// if ($_tag)
				// {
					// $tag->parent_tag_id = $_tag->id;
					// $tag->save();
				// }
			// }
		// }
		// exit;
		
		// foreach($db2->createCommand('SELECT * FROM b_user')->queryAll() as $row)
		// {
			// $row = (object) $row;
			
			// if (User::model()->exists('email = "' . $row->EMAIL . '"'))
				// continue;
			
			// $user = new User;
			// $user->tmp_id = $row->ID;
			// $user->first_name = $row->NAME;
			// $user->last_name = $row->LAST_NAME;
			// $user->email = $row->EMAIL;
			// $user->login = $row->LOGIN;
			// $user->password = $row->PASSWORD;
			// $user->gender = $row->PERSONAL_GENDER;
			
			// $user->created = $row->DATE_REGISTER;
			// $user->lastlogin = $row->LAST_LOGIN;
			// $user->lastactive = $user->lastlogin;
			
			// if (!$user->gender)
				// $user->gender = NULL;
			// if (!$user->email)
				// $user->email = NULL;
			// if (!$user->first_name)
				// $user->first_name = NULL;
			// if (!$user->last_name)
				// $user->last_name = NULL;
			
			// $user->profile = array(
				// 'phone' => $row->PERSONAL_PHONE,
				// 'address' => $row->PERSONAL_STREET,
				// 'city' => $row->PERSONAL_CITY,
			// );
			
			// if ($row->EXTERNAL_AUTH_ID)
			// {
				// switch($row->EXTERNAL_AUTH_ID)
				// {
					// case('Facebook'):
						// $user->fb = $row->XML_ID;
						// break;
					// case('Twitter'):
						// $user->tw = $row->XML_ID;
						// break;
					// case('GoogleOAuth'):
						// $user->gl = $row->XML_ID;
						// break;
					// case('VKontakte'):
						// $user->vk = $row->XML_ID;
						// break;
					// case('OPENID#http://openid.mail.ru/login'):
						// $user->mr = $row->XML_ID;
						// break;
					// case('OPENID#http://openid.yandex.ru/server/'):
						// $user->ya = $row->XML_ID;
						// break;
				// }
			// }
			
			// $user->save();
			// if ($user->errors)
			// {
				// VarDumper::dump($user->errors);
				// exit;
			// }
		// }
		
		// exit;
		
		/*
		// Обнволение данных ленты активности
		foreach(Request::model()->findAll() as $request)
		{
			$feed = Feed::model()->find(array(
				'condition'=> 'event = 0 AND request_id = ' . $request->id,
				// 'order' => 'id DESC'
			));
			if (!$feed) continue;
			
			$feed->user_id = $request->author_id;
			$feed->save();
			
			// $request->lastactive = $feed->created;
			// $request->save();
		}
		exit;
		*/
		
		// foreach(Request::model()->findAll() as $request)
		// {
			// $feed = Feed::model()->findByAttributes(array(
				// 'request_id' => $request->id,
				// 'event' => Feed::EVENT_START_REQUEST,
			// ));
			// if (!$feed) continue;
			
			// $feed->created = $request->created;
			// $feed->save();
		// }
		// foreach(Comment::model()->findAll() as $comment)
		// {
			// $feed = Feed::model()->findByAttributes(array(
				// 'request_id' => $comment->request_id,
				// 'event' => Feed::EVENT_ADD_COMMENT,
				// 'data' => $comment->id,
			// ));
			// $feed->user_id = $comment->author_id;
			// $feed->created = $comment->created;
			// $feed->save();
		// }
		// exit;
		
		foreach($db2->createCommand('SELECT * FROM b_uts_user')->queryAll() as $row)
		{
			$user = User::model()->findByAttributes(array(
				'tmp_id' => (int) $row['VALUE_ID']
			));
			
			if ($user && !empty($row['UF_VOTING']))
			{
				$requests = explode(',', $row['UF_VOTING']);
				foreach($requests as $id)
				{
					$id = (int) $id;
					if ($id<=0) continue;
					$request = Request::model()->findByAttributes(array(
						'tmp_id' => $id
					));
					if ($request)
						$request->like($user->id);
				}
			}
		}
		exit;
		
		foreach($db2->createCommand('SELECT * FROM b_iblock_element WHERE IBLOCK_ID = 1 AND ID=35120')->queryAll() as $row)
		{
			$row = (object) $row;
			$row->status = 0;
			$row->address = '';
			
			// дополнительные
			$row->properties = array();
			$row->point = array();
			$row->files = array();
			$row->files_comment = array();
			$row->tags = array();
			$row->polygon = '';
			$row->request_id = 0;
			$row->request_work = array();
			
			foreach($db2->createCommand('SELECT * FROM b_iblock_element_property WHERE IBLOCK_ELEMENT_ID = ' . $row->ID)->queryAll() as $prop)
			{
				if ($prop['IBLOCK_PROPERTY_ID'] == 1) // ADDRESS
				{
					$row->address = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 2) // LAT
				{
					$row->point[0] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 10) // STATUS
				{
					$row->status = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 30) // LNG
				{
					$row->point[1] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 34) // FILES comment
				{
					$row->files_comment[] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 75) // FILES request
				{
					$row->files[] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 26) // polygon
				{
					$row->polygon = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 27) // polygon
				{
					$row->user_id = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 56) // request_work
				{
					$row->request_work[] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 58) // TAG
				{
					$row->tags[] = $prop['VALUE'];
				}
				if ($prop['IBLOCK_PROPERTY_ID'] == 19) // REQUEST_ID / FOR COMMENT
				{
					$row->request_id = $prop['VALUE'];
				}
				
				$row->properties[$prop['ID']] = array(
					$prop['IBLOCK_PROPERTY_ID'],
					$prop['VALUE'],
				);
			}
			
			// $user = User::model()->findByAttributes(array(
				// 'tmp_id' => $row->CREATED_BY
			// ));
			
			print_r($row);
			exit;
			
			/*
			// ФАЙЛЫ ДЛЯ ПРОБЛЕМ
			$model = Request::model()->findByAttributes(array(
				'tmp_id' => $row->ID
			));
			if (!$model)
				continue;
			if ($row->files)
			{
				foreach($row->files as $fid)
				{
					$file = $db2->createCommand('SELECT * FROM b_file WHERE ID = ' . $fid)->queryRow();
					$path = BASE_PATH .'/../files/upload/'. $file['SUBDIR'] .'/'. $file['FILE_NAME'];
					$instance = new CUploadedFile(
						$file['ORIGINAL_NAME'],
						$path,
						CFileHelper::getMimeType($path),
						filesize($path),
						0
					);
					$model->storage->store($instance);
				}
				// VarDumper::dump($row);
				// exit;
			}
			*/
			
			/*
			// ФАЙЛЫ ДЛЯ КОММЕНТАРИЕВ
			$comment = Comment::model()->findByAttributes(array(
				'tmp_id' => $row->ID
			));
			if (!$comment)
				continue;
			if ($row->files_comment)
			{
				foreach($row->files_comment as $fid)
				{
					$file = $db2->createCommand('SELECT * FROM b_file WHERE ID = ' . $fid)->queryRow();
					$path = BASE_PATH .'/../files/upload/'. $file['SUBDIR'] .'/'. $file['FILE_NAME'];
					$instance = new CUploadedFile(
						$file['ORIGINAL_NAME'],
						$path,
						CFileHelper::getMimeType($path),
						filesize($path),
						0
					);
					$comment->storage->store($instance);
				}
				// VarDumper::dump($row);
				// exit;
			}
			*/
			
			/*
			// IBLOCK = 5
			$officer = new Officer;
			$officer->tmp_id = $row->ID;
			$officer->post = $row->NAME;
			$officer->created = $row->DATE_CREATE;
			
			if ($row->polygon)
			{
				$location = array();
				foreach(explode(';', $row->polygon) as $i => $point)
				{
					if (!$i) $first = $point;
					$location[] = array_reverse(explode(',', $point));
				}
				if ($first!=$point)
				{
					$location[] = array_reverse(explode(',', $first));
				}
				$officer->location = array($location);
				$officer->address = Request::geocode(str_replace(',','.',implode(';', $location[0])));
			}
			
			// print_r($row);
			// exit;
			// print_r($officer->attributes);
			// exit;
			
			$officer->save();
			
			if ($officer->id)
			{
				if ($row->request_work)
				{
					foreach($row->request_work as $tmp_id)
					{
						$request = Request::model()->findByAttributes(array(
							'tmp_id' => $tmp_id
						));
						if ($request)
						{
							$officerRequest = new OfficerRequest;
							$officerRequest->tmp_id = $tmp_id;
							$officerRequest->officer_id = $officer->id;
							$officerRequest->request_id = $request->id;
							$officerRequest->save();
						}
					}
				}
				
				if ($row->tags)
				{
					foreach($row->tags as $tmp_id)
					{
						$tag = Tag::model()->findByAttributes(array(
							'tmp_id' => $tmp_id
						));
						if ($tag)
						{
							$officerTag = new OfficerTag;
							$officerTag->officer_id = $officer->id;
							$officerTag->tag_id = $tag->id;
							$officerTag->save();
						}
					}
				}
				if ($row->user_id)
				{
					$user = User::model()->findByAttributes(array(
						'tmp_id' => $row->user_id
					));
					if ($user)
					{
						$user->officer_id = $officer->id;
						$user->save();
					}
				}
			}
			*/
			
			// exit;
			
			// IBLOCK = 3
			// $request = Request::model()->findByAttributes(array(
				// 'tmp_id' => $row->request_id
			// ));
			// if (!$request)
				// continue;
			// $comment = new Comment;
			// $comment->tmp_id = $row->ID;
			// $comment->author_id = $user ? $user->id : NULL;
			// $comment->request_id = $request->id;
			// $comment->created = $row->DATE_CREATE;
			// $comment->message = $row->PREVIEW_TEXT;
			// $comment->save();
			
			// IBLOCK = 1
			// if (empty($row->point[0]) || empty($row->point[1]))
				// continue;
			// $request = new Request;
			// $request->tmp_id = $row->ID;
			// $request->tmp_user_id = $row->CREATED_BY;
			// $request->author_id = $user ? $user->id : 0;
			// $request->title = $row->NAME;
			// $request->description = $row->PREVIEW_TEXT;
			// $request->status = ($row->status==5 ? Request::STATUS_SOLVED : ($row->status==2 ? Request::STATUS_UNSOLVED : Request::STATUS_INPROGRESS));
			// $request->lat = $row->point[0];
			// $request->lng = $row->point[1];
			// $request->created = $row->DATE_CREATE;
			// $request->address = Request::geocode(str_replace(',','.',implode(';', $row->point)));
			// $request->save();
		}
    }
	
	/**
	 * Главная страница
	 * @return void
	 */
    public function actionIndex($layer=null)
    {
		// Первый раз зашли на сайт, показываем промо страницу
		if (!isset(Yii::app()->request->cookies['rupor_init']))
		{
			$cookie = new CHttpCookie('rupor_init',1);
			$cookie->expire = time() + (60*60*24*365); // (1 year)
			Yii::app()->request->cookies['rupor_init'] = $cookie; 
			$this->redirect('/promo', true, 301);
		}
		
		$this->render('index', array(
			// 'layers' => Layer::model()->findAll(),
			// 'layer' => $layer,
		));
    }
	
	/**
	 * Поиск
	 * @return void
	 */
    public function actionSearch($q=null)
    {
		$model = new Request('search');
		$model->unsetAttributes();
		$model->query = $q;
		
		// if (isset($_REQUEST['Request']))
		// {
			// $model->setAttributes($_REQUEST['Request']);
		// }
		
		$this->render('search', array(
			'dataProvider' => $model->search(),
			'query' => $q,
		));
    }
	
	/**
	 * Статистика
	 * @return void
	 */
    public function actionStats()
    {
		$cookie = CJSON::decode($_COOKIE['rupor']);
		$location = !empty($cookie['location']) ? $cookie['location'] : null;
		$limit = 6;
		
		if (!$location)
			throw new CHttpException(404);

		function formatRow(&$row)
		{
			if (empty($row['status'])) return;
			
			switch($row['status']){
			case(Request::STATUS_SOLVED):
				$row['status'] = 'Решено';
				$row['color'] = '#83B928';
				break;
			case(Request::STATUS_UNSOLVED):
				$row['status'] = 'Не решено';
				$row['color'] = '#E62828';
				break;
			default:
				$row['status'] = 'В работе';
				$row['color'] = '#F1D728';
				break;
			}
		}
		
		// Статистика по статусу
		$by_status = Yii::app()->db->createCommand()
			->select("status, COUNT(*) as total")
			->from('{{request}}')
			->where('address LIKE :location', array(':location'=>$location.',%'))
			->group('status')
			->queryAll();
		
		foreach($by_status as &$row)
		{
			formatRow($row);
		}
		
		// Статистика по адресу
		$by_location = Yii::app()->db->createCommand()
			->select("
				SUBSTRING_INDEX(address, ',', ". (substr_count($location, ',')+2) .") as addr,
				SUBSTRING_INDEX(SUBSTRING_INDEX(address, ',', ". (substr_count($location, ',')+2) ."), ',', -1) as location,
				COUNT(*) as total
			")
			->from('{{request}}')
			->where('address LIKE :location', array(':location'=>$location.',%'))
			->group('addr')
			->order('total DESC')
			->limit($limit)
			->queryAll();
		
		// Статистика по темам / 1 уровень
		$by_tags = array();
		foreach(Tag::treeArray() as $tag)
		{
			$tids = array_keys($tag['children']);
			$tids[] = $tag['id'];
			
			$command = Yii::app()->db->createCommand()
				->select("status, COUNT(*) as total")
				->from('{{request}} R')
				->leftJoin('{{tag_request}} T', 'R.id = T.request_id')
				->where('address LIKE :location AND tag_id IN('. implode(',', $tids) .')', array(':location'=>$location.',%'))
				->group('status');
			
			$item = array(
				'id' => $tag['id'],
				'name' => $tag['name'],
				'total' => 0,
				'data' => array(),
			);
			
			foreach($command->queryAll() as $row)
			{
				formatRow($row);
				$item['data'][] = $row;
				$item['total'] += $row['total'];
			}
			
			$by_tags[] = $item;
		}
		
		// Статистика организаций
		$criteria = new CDbCriteria;
		$criteria->select = 't.*, (SELECT COUNT(*) FROM feed WHERE user_id = U.id AND event='.Feed::EVENT_CHANGE_STATUS.' AND data='.Request::STATUS_INPROGRESS.') as count';
		$criteria->order = 'count DESC';
		$criteria->join = 'LEFT JOIN user U ON U.officer_id = t.id';
		$criteria->limit = $limit;
		$officers = Officer::model()->findAll($criteria);
		
		// Статистика пользователей
		$criteria = new CDbCriteria;
		$criteria->order = 'rating DESC';
		$criteria->limit = $limit;
		$users = User::model()->findAll($criteria);
		
		$this->render('stats', array(
			'location' => $location,
			'by_location' => $by_location,
			'by_status' => $by_status,
			'by_tags' => $by_tags,
			'officers' => $officers,
			'users' => $users,
		));
    }
	
	/**
	 * Переход по ссылке
	 * @return void
	 */
    public function actionAway($to)
    {
		if (isset($_POST['go']))
			$this->redirect($to);
		$this->render('away');
    }
	
	/**
	 * Страницу загрузки браузера
	 * @return void
	 */
	public function actionBadBrowser()
	{
		$this->render('badbrowser');
	}
	
	/**
	 * Ошибка
	 * @return void
	 */
	public function actionError()
	{
	    if ($error = Yii::app()->errorHandler->error)
	    {
			if (Yii::app()->request->isAjaxRequest)
				exit($error['code']);
			
			$this->pageTitle = $error['code'];
			
			try
			{
				return $this->render($error['code'], $error);
			}
			catch (Exception $e)
			{
				return $this->render('error', $error);
			}
	    }
		throw new CHttpException(404);
	}
}