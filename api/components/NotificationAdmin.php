<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotificationAdmin
 *
 * @author irina
 */
class NotificationAdmin {
	
	public static function check ($userId, $requestId) {
		
		$params = array('user_id' => $userId, 'request_id' => $requestId);
		$condition = 'user_id=:user_id AND request_id=:request_id';
		if (Notification::model()->exists($condition, $params)) {
			
			return Notification::model()->deleteAll($condition, $params);
		} 
		$notification = new Notification();
		$notification->attributes = array(
			'user_id' => $userId,
			'request_id' => $requestId,
		);
		return $notification->validate() && $notification->save();
	}
	
	public static function send ($request, $status) {
		
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN notification ON notification.user_id=t.id';
		$criteria->addCondition('notification.request_id=:request_id');
		$criteria->params = array('request_id' => $request->id);
		$recipients = User::model()->findAll($criteria);
		foreach ($recipients as $recipient) {
			$subject = 'Рупор: Результат рассмотрения заявки';
			$message = 'Заявка "'. $request->title.'" была завершена со статусом: '. RequestAdmin::status($status);
			$mail = new BRMail($recipient->email, $subject, $message);
			$mail->send();
		}
	}
        
        public static function notifiedList($user){
            return Request::model()->with('notifications')->findAll(array(
                'condition' => 'notifications.user_id=:user_id',
                'params' => array('user_id' => $user->id),
                'order' => 'created DESC',
            ));
        }
}

?>
