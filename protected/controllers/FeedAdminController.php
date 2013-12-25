<?php

class FeedAdminController extends BackendController
{
    public function actionIndex()
    {
        $model = new Feed('search');
        $model->unsetAttributes(); // clear any default values
		
		if (isset($_GET['Feed']))
            $model->attributes = $_GET['Feed'];
		
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
        $model = new Feed();
		
        if (!empty($_POST['Feed']))
        {
            $model->setAttributes($_POST['Feed']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormFeed')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', 'Новая активность добавлена');
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

        if (!empty($_POST['Feed']))
        {
			$model->setAttributes($_POST['Feed']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormFeed')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', 'Данные обновлены!');
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
			$model = Feed::model()->findByPk($_GET['id']);
		if (empty($model))
			throw new CHttpException(404);
		return $model;
    }
}