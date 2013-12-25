<?php

class PageController extends Controller
{
	/**
	 * Disable filters
	 */
    public function filters()
	{
		return array();
	}
	
	/**
	 * Просмотр страницы
	 * @param string url
	 * @rerun void
	 */
    public function actionView($url)
    {
		if (!$url || !$model = StaticPage::model()->find('keyword = :keyword', array('keyword'=>$url)))
			throw new CHttpException(404);
		
		$file = $this->getViewFile($model->keyword) ? $model->keyword : 'view';
		
		$this->render($file, array(
			'model' => $model,
		));
    }
}