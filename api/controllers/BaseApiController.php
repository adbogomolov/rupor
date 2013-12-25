<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Базовый контроллер принятия запросов веб-сервиса.
 *
 * @author irina
 */
class BaseApiController extends Controller {

    public $request;

    public function __construct() {

        if (Yii::app()->request->isPostRequest) {
            $this->request = Api::decodeRequest();
        } else {
            $this->request = $this->_getGetRequest();
        }
    }

    /**
     * Проверка существования параметров запроса
     * 
     * @param object $request
     * @param object $params
     * @return boolean
     */
    protected function _checkRequest($request, $params) {
        foreach ($params as $item) {
            if (!is_array($item)) {
                if (!isset($request->$item)) {
                    Api::sendResponse(Api::BAD_REQUEST, array('item' => $item));
                }
                if (key_exists($item, $params)) {
                    $this->_checkRequest($request->$item, $params[$item]);
                }
            }
        }
        return true;
    }

    /**
     * Авторизация по токену
     * 
     * @param string $token
     * @return object
     */
    protected function _authorize($token) {

        $criteria = new CDbCriteria();
        $criteria->condition = 't.token=:token';
        $criteria->params = array('token' => $token);
        $token = BRToken::model()->find($criteria);
        if ($token) {
            $user = BRUser::model()->findByPk($token->user_id);
            if ($user) {
                return $user;
            }
        }
        Api::sendResponse(Api::TOKEN_INVALID);
    }

    /**
     * Получение запроса, конвертация json в объект
     * 
     * @return object
     */
    public function _getGetRequest() {

        if (!isset($_GET['request'])) {
            Api::sendResponse(Api::BAD_REQUEST);
        }
        $json = $_GET['request'];
        $request = Api::arrayToObject($json);
        return $request->request;
    }

}

?>
