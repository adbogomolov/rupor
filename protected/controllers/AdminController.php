<?php

class AdminController extends BackendController
{
	/**
	 * This is the action to display dashboard page
	 */
    public function actionIndex()
    {
		$this->redirect('/admin/feed');
    }

	/**
	 * This is the action to display settings page
	 */
    public function actionSettings()
    {
		$this->pageTitle = 'Настройки';
		$this->breadcrumbs = array('Настройки');
		
		$module_id = Yii::app()->request->getQuery('module');
		
		if ($module_id)
		{
			$module = Yii::app()->getModule($module_id);
			if (!$module)
				throw new CHttpException(404);
			
			$this->pageTitle = $module->name .' | ' . $this->pageTitle;
			$this->breadcrumbs = array($module->name => $module->url) + $this->breadcrumbs;
		}
		
		// init settings form
		$form = new FormSettings($module_id);
		if (isset($_POST['FormSettings']))
		{
			$form->setAttributes($_POST['FormSettings']);
			if ($form->save())
			{
				Yii::app()->user->setFlash('success', Yii::t('admin', 'Настройки успешно сохранены!'));
			}
		}
		
		// render form
		$this->render('settings', array(
			'form' => $form
		));
	}
}