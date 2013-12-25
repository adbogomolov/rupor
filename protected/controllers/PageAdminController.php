<?php

class PageAdminController extends BackendController
{
    public function actionIndex()
    {
        $model = new StaticPage('search');
        $model->unsetAttributes(); // clear any default values
		
		if (isset($_GET['StaticPage']))
            $model->attributes = $_GET['StaticPage'];
		
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
        $model = new StaticPage();
		
        if (!empty($_POST['StaticPage']))
        {
            $model->setAttributes($_POST['StaticPage']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormStaticPage')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			
            if ($model->save())
            {
                Yii::app()->user->setFlash('success', 'Страница добавлена!');
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

        if (!empty($_POST['StaticPage']))
        {
			$model->setAttributes($_POST['StaticPage']);
			
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'FormStaticPage')
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
        if (Yii::app()->StaticPage->isPostStaticPage)
        {
            // we only allow deletion via POST StaticPage
            $this->loadModel()->delete();

            // if AJAX StaticPage (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid StaticPage. Please do not repeat this StaticPage again.');
    }

    /**
     * View model
     * @param integer id
     * @return void
     */
    public function actionView($id)
    {
        $model = $this->loadModel();
		$this->redirect($model->getHref());
	}
	
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return User
     */
    public function loadModel()
    {
		if (isset($_GET['id']))
			$model = StaticPage::model()->findByPk($_GET['id']);
		if (empty($model))
			throw new CHttpException(404);
		return $model;
    }
}