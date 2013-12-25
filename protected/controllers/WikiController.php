<?php

class WikiController extends Controller
{
    public function filters()
	{
		return array();
	}
	
	/**
	 * Ошибка
	 */
	public function actionView($id)
	{
		$model = Wiki::model()->findByPk($id);
		if (!$model) throw new CHttpException(404);
		
		$this->render('view', array(
			'model' => $model
		));
	}
}