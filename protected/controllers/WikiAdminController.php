<?php

class WikiAdminController extends BackendController
{
    public function actionIndex()
    {
        $model = new Wiki('search');
        $model->unsetAttributes(); // clear any default values
		
		if (isset($_GET['Wiki']))
            $model->attributes = $_GET['Wiki'];
		
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
        $model = new Wiki();
		
        if (!empty($_POST['Wiki']))
        {
            $model->setAttributes($_POST['Wiki']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormWiki')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', 'Вики страница добавлена!');
                $this->redirect(array('view', 'id' => $model->id));                    
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

        if (!empty($_POST['Wiki']))
        {
			$model->setAttributes($_POST['Wiki']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormWiki')
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
        if (Yii::app()->Wiki->isPostWiki)
        {
            // we only allow deletion via POST Wiki
            $this->loadModel()->delete();

            // if AJAX Wiki (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid Wiki. Please do not repeat this Wiki again.');
    }
	
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return User
     */
    public function loadModel()
    {
		if (isset($_GET['id']))
			$model = Wiki::model()->findByPk($_GET['id']);
		if (empty($model))
			throw new CHttpException(404);
		return $model;
    }
}