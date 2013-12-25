<?php

class RequestCommand extends CConsoleCommand
{
    public function actionIndex()
    {
		$dbConnection = Yii::app()->db;
		$model = Request::model();
		
		// Текущее время в формате UNIX Timestamp
		$time = time();
		
		// Использовать DAO чтобы не грузить память
		$dataReader = $dbConnection->createCommand()
			->from($model->tableName())
				->where(array('in', 'status', array(Request::STATUS_UNSOLVED, Request::STATUS_INPROGRESS)))
					->query();
		
		$dataReader->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, get_class($model));
		while(($request=$dataReader->read())!==false)
		{
			$created_time = strtotime($request->created); // время в формате UNIX Timestamp
			$available_officers = array();
			
			// Получить все организации для проблемы
			foreach($request->getAllOfficers() as $officer)
			{
				// проверика (было ли отправлено ранее)
				if (OfficerRequest::model()->exists('officer_id = :officer_id AND request_id = :request_id', array(
					'request_id' => $request->id,
					'officer_id' => $officer->id,
				))) continue;
				
				$available_time = $created_time + (60 * 60 * 24 * (int)$officer->days_count);
				
				// Если день доступности отправки для организации = текущему дню
				if (date('Ymd', $available_time) == date('Ymd'))
				{
					// TODO: Весь обьект ?
					$available_officers[] = $officer->post;
				}
			}
			
			// Нет доступных организаций
			if (empty($available_officers))
				continue;
			
			// отправляем уведомления подписчикам
			foreach($request->subscriptions as $subscribe)
			{
				$user = User::model()->findByPk($subscribe->user_id);
				// пользователь не найден
				if (!$user)
				{
					$subscribe->delete();
					continue;
				}
				
				$data = array(
					'days_passed' => ceil(($time-$created_time)/(3600*24)),
					// request
					'request_title' => $request->title,
					'request_address' => $request->address,
					'request_url' => Yii::app()->createAbsoluteUrl($request->getHref()),
					// user
					'user_name' => $user->getName(),
					'user_email' => $user->email,
					'user_address' => $user->getEavAttribute('address'),
					'user_phone' => $user->getEavAttribute('phone'),
					'officers' => implode(', ', $available_officers),
				);
				
				// отправка уведомления
				Mail::send('request_cron', $user->email, $data, array(
					'cc' => 'vk.tiamo@gmail.com'
				));
			}
		}
    }
}