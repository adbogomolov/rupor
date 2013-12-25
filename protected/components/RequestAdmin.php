<?php

/**
 * Администрирование заявок
 *
 * @author irina
 */
class RequestAdmin {
	
	const GEOCODE_URL = 'http://maps.googleapis.com/maps/api/geocode/json?';
	const SOLVED = 1;
	const UNSOLVED = 2;
	const INPROGRESS = 3;
	
	const REQUEST_COUNT_LIMIT = 10;
	const LIMIT_OF_NEW = 10;
	const REQUESTS_ON_PAGE = 10;

	public static function statusList() {
		return array(
			self::SOLVED => 'Решена',
			self::UNSOLVED => 'Не решена',
			self::INPROGRESS => 'В работе',
		);
	}
	
	public static function status($code) {
		$statuses = self::statusList();
		return $statuses[$code];
	}

	/**
	 * Определение координат местоположения по адресу 
	 * @param string $address
	 * @param string $city
	 * @return array
	 */
	public function determineCoords ($address, $city = null) {
        
		    $params = 'address=';
            $params .= str_replace(' ', '+', $address);  
            if ($city) {
                $params .= '+' . $city;
            }    
            $params .= '&sensor=false';
            $data = Yii::app()->CURL->run(self::GEOCODE_URL . $params);
            $json = json_decode($data);
            if (empty($json->results)) {
                return false;
            }
            $results = $json->results;
            $location = $results[0]->geometry->location;
            return array(
                        'lat' => $location->lat, 
                        'lng' => $location->lng,
                        );
    }
	
	/**
	 * Опредление адреса по координатам
	 * @param float $lat
	 * @param float $lng
	 * @return string
	 */
	public static function determineAddress ($lat, $lng) {
		
		$params = 'latlng='.$lat.','.$lng.'&sensor=false';
		$data = Yii::app()->CURL->run(self::GEOCODE_URL . $params);
		$json = json_decode($data);		
		if (empty($json->results)) {
			return false;
		}
		$results = $json->results;		
		return $results[0]->formatted_address;
	}

	/**
	 * Добавление заявки
	 * 	 
	 * @param object $data
	 * @param int $authorId
	 * @return object 
	 */
	public static function addRequest ($data, $authorId = null) {
		
		$request = new BRRequest();
		foreach ($request->attributes as $attribute => $value) {
			
			if (isset($data->$attribute)) {
				
				$request->$attribute = $data->$attribute;
			}
			$request->author_id = $authorId;
			$request->status = self::INPROGRESS;
			$request->created = date('Y-m-d H:i:s', time());
		}
		
		if ($request->validate()) {
			$request->save();
			self::addLocation($request, $data);
			if (isset($data->tags)) {
				self::bindOfficer($request->id, $data->tags);
			}
			FeedAdmin::pushTheNew($request->id, FeedAdmin::START_REQUEST);
			if ($authorId) {
				LikeAdmin::like($request->id, $authorId);
				UserAdmin::subscribe($authorId, $request->id);
				NotificationAdmin::check($authorId, $request->id);
			}
			$result = array('error' => false, 'request' => $request);
		} else {
			$result = array('error' => true, 'request' => $request);
		}
		
		return (object)$result;
	}
	
	/**
	 * Добавление местоположения к заявке
	 * @param Request $request
	 * @param object $location
	 * @return boolean
	 */
	public static function addLocation ($request, $location) {
		
		$coordinates = array();
		if(isset($location->address)) {
			
			$coordinates = self::determineCoords($location->address);
			$coordinates['address'] = $location->address;
		}
		if (isset($location->lat) && isset($location->lng)) {
			 
			 $coordinates['lat'] = $location->lat;
			 $coordinates['lng'] = $location->lng;			 
			 if (!isset($location->address)) {
				 $coordinates['address'] = self::determineAddress($location->lat, $location->lng);				 
			 }
        } 		        
		if (!empty($coordinates)) {
			$request->lat = $coordinates['lat'];
			$request->lng = $coordinates['lng'];
			$request->address = $coordinates['address'];
			$request->update();
			return true;
		}
		return false;
	}
	/**
	 * Выборка заявок в квадрате местности, по статусу, тегам и слову в названии
	 * @param object $coords
	 * @param int $status
	 * @param string $keyword
	 * @param array $tags
	 * @return Request
	 */
	public static function getByCoords ($coords, $status, $keyword, $tags) {
		
		$criteria = new CDbCriteria();
        $criteria->condition = 't.lng<:lng_left AND t.lng>:lng_right AND t.lat>:lat_bottom AND t.lat<:lat_top';
		$criteria->order= 't.lat';
		if ($status) {
			$criteria->addCondition('t.status IN ('.  implode(',', $status).')');
		}		
		if ($tags) {
			
			$criteria->AddCondition('t.id = tag_request.request_id');
			$condition = 'tag_request.tag_id IN (' . implode(',', $tags) .')';
			$criteria->join = 'JOIN tag_request ON ' . $condition;
			$criteria->group = 'id';
		}
        $params = array(
					'lng_left' => $coords->lng_left,
					'lng_right' => $coords->lng_right,
					'lat_bottom' => $coords->lat_bottom,
					'lat_top' => $coords->lat_top,							
					);		
		if ($keyword) {
			$criteria->compare('t.title', $keyword, true);
			$params['ycp0'] = '%' . $keyword . '%';
		}
		$criteria->params = $params;
		return BRRequest::model()->findAll($criteria);
	}
	
	/**
	 * Выборка заявок по тегам
	 * @param int $tagId
	 * @param int $firstId
	 * @return Request
	 */
	public static function getByTag ($tagId, $firstId, $status, $keyword) {
		
		$criteria =new CDbCriteria();
		$criteria->condition = 't.id = tag_request.request_id';
		$criteria->order = 'id DESC';
		$criteria->limit = self::REQUESTS_ON_PAGE;
		if ($status) {
			$criteria->addCondition('t.status IN ('.  implode(',', $status).')');
		}	
		if ($firstId) {
			$criteria->addCondition('t.id<'. $firstId);
		}
		if ($keyword) {
			$criteria->compare('t.title', $keyword, true);
			$criteria->params['ycp0'] = '%' . $keyword . '%';
		}
		if (is_array($tagId)) {
			$condition = 'tag_request.tag_id IN (' . implode(',', $tagId) .')';
		} else {
			$condition = 'tag_request.tag_id=' . $tagId;
		}
		$criteria->join = 'JOIN tag_request ON ' . $condition;
		$criteria->group = 'id';
		return Request::model()->findAll($criteria);
	}
	
	/**
	 * Список тегов
	 * @return array
	 */
	public static function tagList () {
				
		$parentTags = Tag::model()->findAllByAttributes(array('parent_tag_id' => null));
		$result = array();
		foreach ($parentTags as $tag) {
			
			$item = (array)$tag->attributes;
			$childs = Tag::model()->findAllByAttributes(array('parent_tag_id' => $tag->id));
			$childArray = array();
			foreach ($childs as $child) {
				
				$childArray[] = $child->attributes;				
			}
			$item['childs'] = $childArray;
			$result[] = $item;
		}
		return $result;
	}
	/**
	 * Выборка заявок по статусу
	 * @param int $status
	 * @return Request
	 */
	public static function getByStatus ($status) {
		
		$criteria = new CDbCriteria();
		$criteria->order = 'created DESC';
		$criteria->condition = 'status=' . $status;
		return Request::model()->findAll($criteria);
	}
	
	/**
	 * Выборка заявок конкретного юзера
	 * @param int $userId
	 * @return Request
	 */
	public static function getMy ($userId) {
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'author_id='.$userId;
		$criteria->order = 'created DESC';
		return Request::model()->findAll($criteria);
	}
	
	/**
	 * Добавление комментария к заявке
	 * @param int $userId
	 * @param int $requestId
	 * @param string $message
	 * @return array
	 */
	public static function addComment ($userId, $requestId, $message) {
		
		$comment = new Comment();
		$comment->attributes = array(
			'author_id' => $userId,
			'request_id' => $requestId,
			'message' => $message,
			'created' => date('Y-m-d H:i:s', time()),
		);
		if ($comment->validate() && $comment->save()) {
			
			FeedAdmin::pushTheNew($requestId, FeedAdmin::ADD_COMMENT, $comment->id);			
			$result = array('error' => false, 'comment' => $comment);
		} else {
			$result = array('error' => true, 'comment' => $comment);
		}
		return (object)$result;
	}
	/**
	 * Изменение статуса заявки
	 * @param int $requestId
	 * @param int $status
	 */
	public static function changeStatus ($requestId, $status) {
		
		$request = Request::model()->findByPk($requestId);
		$request->status = $status;
		$request->update();
		FeedAdmin::pushTheNew($requestId, FeedAdmin::CHANGE_STATUS, $status);
		NotificationAdmin::send($request, $status);
	}
	/**
	 * Привязка тегов к заявке
	 * @param Tag $tags
	 * @param int $requestId
	 */
	public static function tagRequest ($tags, $requestId) {
		
		foreach ($tags as $tagId) {
			
			$criteria = new CDbCriteria();
			$criteria->condition = 't.id='.$tagId;
			if (Tag::model()->exists($criteria)) {
				
				$tagRequest = new TagRequest();
				$tagRequest->attributes = array(
					'tag_id' => $tagId,
					'request_id' => $requestId,
				);
				$tagRequest->save();
			}
		}		
	}
	/**
	 * Выборка новых заявок
	 * @param int $limit
	 * @return Request
	 */
	public static function latest ($limit = self::REQUEST_COUNT_LIMIT) {
		
		$criteria = new CDbCriteria();
		$criteria->order = 'created DESC';
		$criteria->limit = $limit;
		
		return Request::model()->findAll($criteria);
	}		
	/**
	 * Добавление расширенной информации по заявке(лайки, автор, ответственные, теги, вики)
	 * @param Request $requests
	 * @param int $userId
	 * @return array
	 */
	public static function additionalInfo ($requests, $userId = null) {
		
		$result = array();
		foreach ($requests as $request) {
				
			$item = (array)$request->attributes;
			//LIKE			
			$item['like'] = LikeAdmin::count($request->id);
			if ($userId) {
				$condition = 'user_id=:user_id AND request_id=:request_id';
				$params = array('user_id' => $userId, 'request_id' => $request->id);
				$item['i_liked'] = Like::model()->exists($condition, $params);
				$item['i_subscribed'] = Subscription::model()->exists($condition, $params);
				$item['i_notificated'] = Notification::model()->exists($condition, $params);
			}
			//AUTHOR
			$item['author'] = $request->author_id ? array(
				'first_name' => $request->author->first_name,
				'last_name' => $request->author->last_name,
			) : UserAdmin::UNREGISTERED_USER;
			//OFFICERS
			$officers = self::getOfficers($request->id);
			$item['officers'] = array();
			foreach ($officers as $officer) {
				$item['officers'][] = $officer->attributes;
			}
			//TAGS
			$tags = TagRequest::model()->findAllByAttributes(array('request_id' => $request->id));
			foreach ($tags as $tag) {
				$item['tag'][] = $tag->tag->attributes;
			}
			//WIKI
			$item['wiki'] = WikiAdmin::selectForRequest($request->id);			
			//COMMENTS
			$item['comments'] = CommentAdmin::getCountByRequest($request->id);
			//FILES
			$condition = 'request_id=:request_id';
			$params = array('request_id' => $request->id);
			$item['files'] = RequestFile::model()->findAll($condition, $params);
			$item['url'] = Yii::app()->getBaseUrl(true) .'/data/';
			$result[] = $item;
		}
		return $result;
	}
	/**
	 * Список ответственных, привязанных к заявке
	 * @param int $requestId
	 * @return Officer
	 */	
	public static function getOfficers ($requestId) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN officer_request ON t.id=officer_request.officer_id AND officer_request.request_id='.$requestId;
		return Officer::model()->findAll($criteria);
	}
	/**
	 * Выборка заявок по юзеру
	 * @param int $userId
	 * @param int $firstId
	 * @return Request
	 */
	public static function getByUser ($userId, $firstId = null) {
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'author_id='.$userId;
		$criteria->order = 'created DESC';
		$criteria->limit = self::LIMIT_OF_NEW;		
		if ($firstId) {
			$criteria->addCondition('id<'.$firstId);
		}
		$requests = Request::model()->findAll($criteria);
		return $requests;
	}
	/**
	 * Привязка отвественного к заявке
	 * @param int $requestId
	 * @param Tag $tags
	 */
	public static function  bindOfficer ($requestId, $tags) {
		
		$officers = OfficerAdmin::getByTag($tags);
		$officers = OfficerAdmin::intersectionWithArea($requestId, $officers);
		foreach ($officers as $officer) {
			
			$officerRequest = new OfficerRequest();
			$officerRequest->attributes = array(
				'officer_id' => $officer->id,
				'request_id' => $requestId,
			);
			$officerRequest->save();
		}
	}
	/**
	 * Список всех заявок
	 * @param int $firstId
	 * @return Request
	 */
	public static function getAll ($firstId, $status, $keyword) {
		
		$criteria = new CDbCriteria();
		$criteria->limit = self::REQUESTS_ON_PAGE;
		$criteria->order = 'id DESC';
		if ($firstId) {
			$criteria->condition = 'id<'.$firstId;
		}
		if ($status) {
			$criteria->addCondition('t.status IN ('.  implode(',', $status).')');
		}
		if ($keyword) {
			$criteria->compare('t.title', $keyword, true);
			$criteria->params['ycp0'] = '%' . $keyword . '%';
		}
		return Request::model()->findAll($criteria);
	}
	/**
	 * Список тегов, относящихся к заявке
	 * @param int $requestId
	 * @return Tag
	 */
	public static function getRequestTag ($requestId) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN tag_request ON t.id = tag_request.tag_id AND tag_request.request_id='.$requestId;
		return Tag::model()->findAll($criteria);
	}
	
	public static function saveFile ($filename, $previewKey) {
		
		$requestFile = new RequestFile();
		$requestFile->attributes = array(			
			'filename' => $filename,
			'preview_key' => $previewKey,
		);		
		if ($requestFile->validate() && $requestFile->save()) {
			return true;
		}
		return false;
	}
	
	public static function updateFile ($key, $requestId, $angle) {
		
		$requestFile = RequestFile::model()->find('preview_key=:key', array('key' => $key));
		if (!$requestFile){
			return false;
		}
		$requestFile->attributes = array(
			'request_id' => $requestId,
			'angle' => $angle,
		);
		$requestFile->update();
		return true;
	}

	public static function getByPeriod ($from, $to) {
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'created>=:from';
		$criteria->addCondition('created<=:to');
		$criteria->params = array('from' => $from, 'to' => $to);
		
		return Request::model()->findAll($criteria);
	}
}

?>
