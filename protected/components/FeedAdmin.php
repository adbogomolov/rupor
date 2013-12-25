<?php

/**
 * Администрирование ленты новостей по заявкам
 *
 * @author irina
 */
class FeedAdmin {
	
	const START_REQUEST = 0;
	const ADD_COMMENT = 1;
	const CHANGE_STATUS = 2;
	
	const LIMIT = 10;
	/**
	 * Добавление новости в ленту
	 * 
	 * @param int $requestId
	 * @param Event $event
	 * @param object $data
	 * @return Feed
	 */
	public static function pushTheNew($requestId, $event, $data = null) {
		
		$feed = new Feed();
		$feed->attributes = array(
			'request_id' => $requestId,
			'user_id' => Yii::app()->user->id,
			'event' => $event,
			'data' => $data,
			'created' => date('Y-m-d H:i:s', time()),
		);
		if ($feed->validate()) {
			$feed->save();
		}
		return $feed;
	}
	
	/**
	 * Лента новостей для конкретного юзера
	 * 
	 * @param int $userId
	 * @param int $firstId
	 * @return array
	 */
	public static function subscriptionFeed ($userId, $firstId = null) {
		
		$criteria = new CDbCriteria();
		$criteria->order = 'id DESC';
		$criteria->limit = self::LIMIT;
		if ($firstId) {
			$criteria->condition = 't.id<'.$firstId;
		}
		$criteria->join = 'JOIN request ON t.request_id=request.id 
			AND request.id IN (SELECT request_id FROM subscription WHERE user_id=' . $userId . ')';
		$feedObjects =  Feed::model()->findAll($criteria);		
		$result = array();
		foreach ($feedObjects as $feed) {
			
			$item = (array)$feed->attributes;
			$request = Request::model()->findByPk($feed->request->id);
			$item['request'] = RequestAdmin::additionalInfo(array($request));
			$result[] = $item;
		}
		return $result;
	}
	/**
	 * Количество подписок на заявку
	 * @param int $requestId
	 * @return subscription
	 */
	public static function subscriberCount ($requestId) {
		
		return Subscription::model()->count('request_id='.$requestId);
	}
} 

?>
