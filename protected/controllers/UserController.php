<?php

class UserController extends Controller
{
	public function accessRules()
	{
		return array(
            array('deny',
                'actions'=>array('index', 'settings', 'logout'),
				'users'=>array('?'),
            ),
		);
	}
	
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
            ),
		);
	}
	
	/**
	 * Профиль
	 * @return void
	 */
    public function actionIndex()
    {
		$user = Yii::app()->user->model;
		$this->render('index', array(
			'user' => $user,
		));
    }
	
	/**
	 * Настройки профиля
	 * @return void
	 */
    public function actionSettings()
    {
		if (Yii::app()->user->isGuest)
			throw new CHttpException(404);
		
		$user = Yii::app()->user->model;
		
		if (!empty($_POST['User']))
		{
			$user->setAttributes($_POST['User']);
			
			// EAV атрибуты
			if (isset($_POST['User']['eavAttributes']))
				$user->setEavAttributes($_POST['User']['eavAttributes']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormUser')
			{
				echo CActiveForm::validate($user);
				Yii::app()->end();
			}
			
			// if (empty($_POST['User']['avatar']))
			// {
				// $user->deleteAvatar();
			// }
			
			if ($user->save())
			{
				Yii::app()->user->setFlash('success', 'Информация успешно обновлена');
				$this->refresh();
			}
		}
		
		$this->render('settings', array(
			'model' => $user,
		));
    }
	
	/**
	 * Вход через логинзу
	 * @return void
	 */
    public function actionLoginza()
    {
		if (isset($_POST['token']))
		{
			Yii::import('ext.loginza.LoginzaUserIdentity');
			
			$identity = new LoginzaUserIdentity($_POST['token']);
			if ($identity->authenticate())
			{
				Yii::app()->user->login($identity, FormLogin::REMEMBER_DURATION);
				$this->redirect(Yii::app()->user->returnUrl);
			}
			elseif ($identity->errorCode == LoginzaUserIdentity::ERROR_UNKNOWN_IDENTITY)
			{
				$this->redirect(Yii::app()->user->loginUrl);
			}
		}
		$this->redirect(Yii::app()->homeUrl, true);
    }
	
	/**
	 * Вход
	 * @return void
	 */
    public function actionLogin()
    {
		if (!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->user->returnUrl);
		
		$model = new FormLogin();
		
		if (!empty($_POST['FormLogin']))
		{
			$model->setAttributes($_POST['FormLogin']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormLogin')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if ($model->validate())
				$this->redirect(Yii::app()->user->returnUrl);
			else
				Yii::log('Ошибка авторизации!', CLogger::LEVEL_ERROR);
		}
		
		$data = array(
			'ajax'=> (bool) (Yii::app()->request->isAjaxRequest||Yii::app()->request->getParam('ajax')),
			'model'=>$model,
		);
		
		if ($data['ajax'])
			$this->renderPartial('login', $data, false, true);
		else
			$this->render('login', $data);
    }
	
	/**
	 * Выход
	 * @return void
	 */
    public function actionLogout()
    {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->getBaseUrl(true));
    }
	
	/**
	 * Регистрация
	 * @return void
	 */
    public function actionRegister()
    {
		$model = new FormRegistration();
		
		if (!empty($_POST['FormRegistration']))
		{
			$model->setAttributes($_POST['FormRegistration']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormRegistration')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Регистрация завершена! Теперь Вы можете войти на сайт!');
				$this->redirect(Yii::app()->user->loginUrl);
			}
		}
		
		$this->render('register', array(
			'model' => $model
		));
    }
	
	/**
	 * Восстановление пароля
	 * @return void
	 */
    public function actionRecovery($hash=null)
    {
		$model = new FormRecovery();
		
		if ($hash)
		{
			$user = User::model()->find('recovery = :recovery', array(':recovery'=>$hash));
			if (!$user)
				throw new CHttpException(404);
			$model->setScenario('complete');
			$model->user = $user;
		}
		
		if (!empty($_POST['FormRecovery']))
		{
			$model->setAttributes($_POST['FormRecovery']);
			
			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']=='FormRecovery')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if ($model->save())
			{
				if ($model->scenario == 'complete')
				{
					Yii::app()->user->setFlash('success', 'Пароль изменен! Теперь вы можете войти на сайт.');
					$this->redirect(array('/user/login'));
				}
				
				Yii::app()->user->setFlash('success', 'Инструкции по восстановлению пароля отправлены на указанный адрес.');
				$this->refresh();
			}
		}
		
		$this->render('recovery', array(
			'model' => $model
		));
    }
}