<?php
/**
 * Администрирование ответственных
 *
 * @author irina
 */
class OfficerAdmin {
	
	/**
	 * Выборка ответственных по тегам
	 * @param Tag $tags
	 * @return Officer
	 */
	public static function getByTag ($tags) {
				
		$criteria = new CDbCriteria();		
		$criteria->join = 'JOIN officer_tag ON t.id=officer_tag.officer_id AND officer_tag.tag_id IN (' . implode(',', (array)$tags) .')';
		$criteria->group = 't.id';
		$officers = Officer::model()->findAll($criteria);		
		return $officers;
	}
	
	/**
	 * Отсев ответственных для заявки по зоне ответственности
	 * 
	 * @param int $requestId
	 * @param Officer $officers
	 * @return object
	 */
	public static function intersectionWithArea ($requestId, $officers) {
		
		$result = array();
		$request = Request::model()->findByPk($requestId);
		$point = array('lat' => $request->lat, 'lng' => $request->lng);
		foreach ($officers as $officer) {
			
			$criteria = new CDbCriteria();
			$criteria->join = 'JOIN area ON t.area_id=area.id AND area.officer_id='.$officer->id;
			$polygon = Coordinate::model()->findAll($criteria);	
			
			if (AreaAdmin::isBelongs((object)$point, $polygon)) {
				$result[] = $officer;
				self::sendEmail($officer, $requestId);
			}
		}
		return (object)$result;
	}
	/**
	 * Оповещение об открытии новой заявки
	 * @param officer $officer
	 * @param int $requestId
	 */
	public static function sendEmail ($officer, $requestId) {
		
		$subject = 'Новая заявка';
		$message = 'На рупор.ру была добавлена новая заявка, находящаяся в Вашей зоне и категории ответственности:
id: ' . $requestId;
		$mail = new BRMail($officer->email, $subject, $message);
		$mail->send();
	}
	/**
	 * Список ответственных по id заявки
	 * @param int $requestId
	 * @return officer
	 */
	public static function getByRequest ($requestId) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN officer_request ON t.id=officer_request.officer_id AND officer_request.request_id='.$requestId;
		return Officer::model()->findAll($criteria);
	}
	/**
	 * Список тегов по id ответственного
	 * @param int $officerId
	 * @return array
	 */
	public static function getTags ($officerId) {
		
		$tagsObject = OfficerTag::model()->findAllByAttributes(array('officer_id' => $officerId));
		return CHtml::listData($tagsObject, 'tag_id', 'tag_id');
	}
	/**
	 * Пересечение тегов для группы ответственных
	 * @param officer $officers
	 * @return type
	 */
	public static function officerTag ($officers) {
		
		$result = array();
		foreach ($officers as $officer) {
			
			$tags = self::getTags($officer->id);
			if (empty($result)) {
				$result = $tags;
			}
			$result = array_intersect($result, $tags);
		}
		return $result;
	}
	/**
	 * Список заявок для ответственного
	 * @param int $officerId
	 * @return RequestAdmin
	 */
	public static function getRequests ($officerId) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN officer_request ON t.id=officer_request.request_id AND officer_request.officer_id=' . $officerId;
		
		$requests = Request::model()->findAll($criteria);
		return RequestAdmin::additionalInfo($requests);
	}
	/**
	 * Информация об ответственных: атрибуты модели, теги, заявки
	 * @param Officer $officers
	 * @return array
	 */
	public static function fullInfo ($officers = null) {
		
		$result = array();
		if (!$officers) {
			$officers = Officer::model()->findAll();
		}
		foreach ($officers as $officer) {
			
			$item = $officer->attributes;			
			$item['tags'] = self::getTags($officer->id);
			$item['requests'] = self::getRequests($officer->id);
			$result[] = $item;
		}
		return $result;
	}
}
	
?>
