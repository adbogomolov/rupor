<?php

class UploaderWidget extends CWidget
{
	public $options = array();
	public $htmlOptions = array();
	
	public function init()
	{
		// if (!empty($_FILES))
		// {
			// print_r($_FILES);
			// echo '{"success": true, "message": "test"}';
			// exit;
		// }
	}
	
	public function run()
	{
		$options = CMap::mergeArray(array(
			'element'=>'js:document.getElementById("'.$this->id.'")',
			'autoUpload'=>true,
			'validation'=>array(
				'sizeLimit' => 5*1024*1024 // 5 Mb
			),
			// 'deleteFile' => array(
				// 'enabled' => 1,
				// 'endpoint' => '/uploads',
			// ),
			'failedUploadTextDisplay' => array(
				'mode' => 'custom'
			),
			'showMessage' => 'js:function(message){return Rupor.box.error(message)}',
			'request'=>array(
				'params' => array(
					Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
					session_name() => session_id(),
				),
				'endpoint' => '' // загрузка из виджета
			),
			'classes'=>array(
				'buttonHover' => '',
				'buttonFocus' => '',
			),
			'messages'=>array(
				'tooManyFilesError' => 'Вы можете поместить только один файл.',
				'unsupportedBrowser' => 'Неустранимая ошибка - этот браузер не позволяет загрузку файлов.',
				'typeError' => "Файл {file} имеет недопустимое расширение. Только \"{extensions}\" допускается.",
				'sizeError' => "Файл {file} слишком большой, максимальный размер файла {sizeLimit}.",
				'minSizeError' => "Файл {file} слишком мал, минимальный размер файла {minSizeLimit}.",
				'emptyError' => "Файл {file} пуст, пожалуйста, выберите файлы еще раз.",
				'noFilesError' => "Нет файлов для загрузки.",
				'tooManyItemsError' => "Загрузить не удалось - вы достигли предела.",
				'retryFailTooManyItems' => "Повторить не удалось - вы достигли предела.",
				'onLeave' => "Файлы загружаются, если вы остановите загрузка будет отменена.",
			),
			'text'=>array(
				'uploadButton' => 'Загрузить файл',
				'cancelButton' => 'Отмена',
				'retryButton' => 'Повторить',
				'deleteButton' => 'Удалить',
				'failUpload' => 'Ошибка при загрузке',
				'dragZone' => 'Перетащите файлы для загрузки',
				'dropProcessing' => 'Перетащите файлы для загрузки',
				'dropProcessing' => 'Обработка перенесенных файлов',
				'formatProgress' => '{percent}% из {total_size}',
				'waitingForResponse' => 'Загрузка...',
			),
		), $this->options);
		
		$this->htmlOptions['id'] = $this->id;
		
		echo CHtml::openTag('div', $this->htmlOptions);
		echo '<noscript><p>Please enable JavaScript to use file uploader.</p></noscript>';
		echo CHtml::closeTag('div');
		
		$baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__).'/assets');
		$cs = Yii::app()->clientScript;
		
		$cs->registerCssFile($baseUrl . '/fineuploader.css');
		$cs->registerScriptFile($baseUrl . '/jquery.fineuploader.js');
		$cs->registerScript(__CLASS__ . $this->id, "var uploader = new qq.FineUploader(". CJavaScript::encode($options) .");", CClientScript::POS_READY);
	}
}