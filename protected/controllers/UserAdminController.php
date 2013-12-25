<?php

class UserAdminController extends BackendController
{
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes(); // clear any default values
		
		if (isset($_GET['User']))
            $model->attributes = $_GET['User'];
		
        $this->render('index', array(
			'model' => $model,
		));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User();
		
        if (!empty($_POST['User']))
        {
            $model->setAttributes($_POST['User']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormUser')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
			$model->password = md5($model->password);
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', Yii::t('user','Новый пользователь добавлен!'));
                $this->redirect(array('update', 'id' => $model->id));                    
            }
        }
		
        $this->render('create', array('model' => $model));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model = $this->loadModel();

        if (!empty($_POST['User']))
        {
			$model->setAttributes($_POST['User']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormUser')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', Yii::t('user','Данные обновлены!'));
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('update', array('model' => $model,));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
	
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return User
     */
    public function loadModel()
    {
		if (isset($_GET['id']))
			$model = User::model()->findByPk($_GET['id']);
		if (empty($model))
			throw new CHttpException(404);
		return $model;
    }
}