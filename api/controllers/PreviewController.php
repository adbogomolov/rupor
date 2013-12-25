<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PreviewController
 *
 * @author irina
 */
require_once 'BaseApiController.php';

class PreviewController extends BaseApiController{
	
	public function actionSaveFile() {
		
		$this->_checkRequest($this->request, array('filename', 'angle', 'request_id'));
		if (RequestAdmin::updateFile($this->request->filename, $this->request->request_id, $this->request->angle)) {
			Api::sendResponse(Api::OK);
		}
		Api::sendResponse(Api::INTERNAL_ERROR);
	}
	
	public function actionDeletePreview() {
		
		$this->_checkRequest($this->request, array('filename'));
		$file = RequestFile::model()->find('filename=:filename', array('filename' => $this->request->filename));
		if (!$file) {
			Api::sendResponse(Api::BAD_REQUEST);
		}
		if(!file_exists(Yii::app()->getBasePath(true) . '/../data/' . $file->filename)) {
			Api::sendResponse(Api::BAD_REQUEST);
		}
		unlink(Yii::app()->getBasePath(true) . '/../data/' . $file->filename);
		RequestFile::model()->deleteByPk($file->id);
		Api::sendResponse(Api::OK);
	}
}

?>
