<?php

class BackendController extends BaseController
{
	public $layout = '//layouts/admin';

	/**
	 * Filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function init()
	{
		parent::init();
		// disable jquery loading on ajax requests
		if (Yii::app()->request->isAjaxRequest)
		{
			$this->layout = '//layouts/ajax';
		}
	}

	/**
	 * Access Rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',
				'roles' => array(User::ROLE_ADMIN),
			),
			array('deny'),
		);
	}

    /**
     * Modules menu
     */
	public function getModulesMenu()
	{
		$items = array();
		$path = Yii::app()->request->requestUri;

		foreach(Yii::app()->getModules() as $name => $conf)
		{
			$module = Yii::app()->getModule($name);

			if (!$module instanceof Module)
				continue;

			// module navigation
			foreach($module->navigation as $item)
			{
				if (!empty($item['url']))
				{
					$item['active'] = (bool) ($path == CHtml::normalizeUrl($item['url']));
				}
				$items[] = $item;
			}

			// module settings
			if ($module->editableParams)
			{
				$settingsUrl = '/admin/settings/'.$module->id;
				$items[] = array(
					'url'=>$settingsUrl,
					'icon' => 'cog',
					'label'=>'Настройки',
					'active'=> (bool) ($path == $settingsUrl),
				);
			}
		}

		return $items;
	}

    /**
     * Manages all models.
     */
    public function beforeAction($action)
    {
		if ($this->id != 'admin')
		{
			// $this->breadcrumbs[str_replace('Admin', '', $this->id)] = array('/' . $this->id);

			switch($action->id){
			case('index'):
				$this->breadcrumbs[] = 'Управление';
				break;
			case('create'):
				$this->breadcrumbs[] = 'Добавление';
				break;
			case('update'):
				$this->breadcrumbs[] = 'Редактирование';
				break;
			case('view'):
				$this->breadcrumbs[] = 'Просмотр';
				break;
			}

			$this->pageTitle = end($this->breadcrumbs);
			if (is_array($this->pageTitle))
				$this->pageTitle = '';
		}

		Yii::app()->setComponent('bootstrap', Yii::createComponent(array(
			'class' => 'ext.booster.components.Bootstrap',
			// 'coreCss'       => false,
			// 'responsiveCss' => false,
			// 'yiiCss'        => false,
			// 'jqueryCss'     => false,
			// 'enableJS'      => false,
		)));

		// Yii::app()->theme = null;
		Yii::app()->preload[] = 'bootstrap';

		return parent::beforeAction($action);
    }

	/**
	 * @ignore
	 */
	public function actionSort()
	{
		if ($ids = Yii::app()->request->getPost('ids'))
		{
			$i = 0;
			foreach($ids as $id)
			{
				if ($item = $this->loadModel($id))
				{
					$item->position = $i;
					$item->update(array('position'));
					$i++;
				}
			}
		}
	}

	/**
	 * @ignore
	 */
    public function actionItemsSelected()
	{
        $ids = Yii::app()->request->getPost('ids');
        $action = Yii::app()->request->getPost('work');

        if ($ids && is_array($ids) && $work)
		{
            foreach($ids as $id)
			{
				if ($id = intval($id))
				{
					$model = $this->loadModel($id);
					switch($action) {
					case('delete'):
						$model->delete();
						break;
					case('activate'):
					case('deactivate'):
						if ($model->hasAttibute('active'))
						{
							$model->active = intval($action == 'activate');
							$model->update(array('active'));
						}
						break;
					}
				}
            }
        }
        if (!Yii::app()->request->isAjaxRequest)
		{
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }
}