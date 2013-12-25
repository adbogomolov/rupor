<?php

class FileController extends Controller
{

	public function actionUpload()
	{

		foreach ($_FILES as $name => $value)
			;
		$requestId = isset($_POST['request_id']) ? $_POST['request_id'] : null;
		$file = CUploadedFile::getInstanceByName($name);
		$filename = md5(uniqid()) . '.' . $file->extensionName;
		$file->saveAs(Yii::app()->getBasePath(true) . '/../' . Settings::DATA . $filename);
		RequestAdmin::saveFile($filename, $requestId);
		Api::sendResponse(Api::OK, array('filename' => $filename, 'url' => Yii::app()->getbaseUrl(true) . Settings::DATA));
	}
}
